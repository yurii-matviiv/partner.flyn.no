<?php

namespace App\Http\Controllers;

use App\Models\PartnerContact;
use App\Models\PartnerCustomerCompany;
use App\Models\PartnerProfile;
use App\Models\PartnerUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PartnerProfileController
{
    public function skip(Request $request): RedirectResponse
    {
        /** @var PartnerUser $user */
        $user = $request->user('partner');

        PartnerProfile::query()->updateOrCreate(
            ['partner_user_id' => $user->id],
            ['deferred_at' => now()],
        );

        return redirect('/partner-panel')->with('status', $this->message('profile_deferred'));
    }

    public function edit(Request $request): View
    {
        /** @var PartnerUser $user */
        $user = $request->user('partner');
        $profile = PartnerProfile::query()->where('partner_user_id', $user->id)->first();

        return view('partner.profile', [
            'user' => $user,
            'profile' => $profile,
            'company' => $profile?->customer_company_id
                ? PartnerCustomerCompany::query()->find($profile->customer_company_id)
                : null,
            'contact' => $profile?->contact_client_id
                ? PartnerContact::query()->find($profile->contact_client_id)
                : null,
            'locale' => app()->getLocale(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var PartnerUser $user */
        $user = $request->user('partner');
        $data = $request->validate([
            'legal_name' => ['required', 'string', 'max:255'],
            'org_number' => ['required', 'string', 'max:30'],
            'legal_form' => ['required', Rule::in(['as', 'asa', 'enk', 'ans', 'da', 'nuf', 'sa', 'stiftelse', 'other'])],
            'address_line' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'regex:/^\\d{4}$/'],
            'city' => ['required', 'string', 'max:100'],
            'invoice_email' => ['required', 'email:rfc,dns', 'max:150'],
            'company_phone' => ['required', 'string', 'min:5', 'max:20'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'contact_job_title' => ['nullable', 'string', 'max:150'],
            'contact_phone' => ['required', 'string', 'min:5', 'max:20'],
            'authorised_to_represent' => ['accepted'],
        ]);

        $orgNumber = preg_replace('/\D+/', '', $data['org_number']);

        if (! $this->isValidNorwegianOrganisationNumber($orgNumber)) {
            throw ValidationException::withMessages([
                'org_number' => $this->message('invalid_org_number'),
            ]);
        }

        $profile = PartnerProfile::query()->where('partner_user_id', $user->id)->first();
        $errors = [];

        $companyWithOrgNumber = PartnerCustomerCompany::query()->where('org_number', $orgNumber)->first();
        if ($companyWithOrgNumber && $companyWithOrgNumber->id !== $profile?->customer_company_id) {
            $errors['org_number'] = $this->message('duplicate_org_number');
        }

        $companyWithPhone = PartnerCustomerCompany::query()->where('phone', $data['company_phone'])->first();
        if ($companyWithPhone && $companyWithPhone->id !== $profile?->customer_company_id) {
            $errors['company_phone'] = $this->message('duplicate_company_phone');
        }

        $contactWithCompanyPhone = PartnerContact::query()->where('phone', $data['company_phone'])->first();
        if ($contactWithCompanyPhone && $contactWithCompanyPhone->id !== $profile?->contact_client_id) {
            $errors['company_phone'] = $this->message('duplicate_company_phone');
        }

        $contactWithPhone = PartnerContact::query()->where('phone', $data['contact_phone'])->first();
        if ($contactWithPhone && $contactWithPhone->id !== $profile?->contact_client_id && $contactWithPhone->email !== $user->email) {
            $errors['contact_phone'] = $this->message('duplicate_contact_phone');
        }

        $companyWithContactPhone = PartnerCustomerCompany::query()->where('phone', $data['contact_phone'])->first();
        if ($companyWithContactPhone && $companyWithContactPhone->id !== $profile?->customer_company_id) {
            $errors['contact_phone'] = $this->message('duplicate_contact_phone');
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        DB::transaction(function () use ($data, $orgNumber, $user): void {
            $company = PartnerCustomerCompany::query()
                ->where('org_number', $orgNumber)
                ->lockForUpdate()
                ->first();

            // The duplicate check above rejects a company owned by anyone else.
            // An existing company can only be reused by its own Partner profile.
            $company ??= PartnerCustomerCompany::query()->create([
                'legal_name' => $data['legal_name'],
                'org_number' => $orgNumber,
                'legal_form' => $data['legal_form'],
                'address_line' => $data['address_line'],
                'postal_code' => $data['postal_code'],
                'city' => $data['city'],
                'country' => 'Norway',
                'email' => Str::lower($data['invoice_email']),
                'phone' => $data['company_phone'],
                'customer_type' => 'new',
            ]);

            $contact = PartnerContact::query()
                ->where('email', $user->email)
                ->lockForUpdate()
                ->first();

            $contact ??= PartnerContact::query()->create([
                'type' => 'private',
                'customer_type' => 'new',
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $user->email,
                'phone' => $data['contact_phone'],
                'locale' => $this->locale(),
                'source' => 'partner_portal',
            ]);

            if (! DB::table('customer_company_contacts')
                ->where('company_id', $company->id)
                ->where('client_id', $contact->id)
                ->exists()) {
                DB::table('customer_company_contacts')->insert([
                    'company_id' => $company->id,
                    'client_id' => $contact->id,
                    'is_primary' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            PartnerProfile::query()->updateOrCreate(
                ['partner_user_id' => $user->id],
                [
                    'customer_company_id' => $company->id,
                    'contact_client_id' => $contact->id,
                    'contact_job_title' => $data['contact_job_title'] ?? null,
                    'deferred_at' => null,
                    'completed_at' => now(),
                ],
            );

            $user->forceFill([
                'name' => trim($data['first_name'] . ' ' . $data['last_name']),
                'locale' => $this->locale(),
            ])->save();

        });

        app(\App\Services\PartnerProfileReminderService::class)->clear($user);

        return redirect('/partner-panel')
            ->with('status', $this->message('profile_saved'));
    }

    private function isValidNorwegianOrganisationNumber(string $number): bool
    {
        if (! preg_match('/^\d{9}$/', $number)) {
            return false;
        }

        $weights = [3, 2, 7, 6, 5, 4, 3, 2];
        $sum = 0;

        foreach ($weights as $position => $weight) {
            $sum += (int) $number[$position] * $weight;
        }

        $checkDigit = 11 - ($sum % 11);
        $checkDigit = $checkDigit === 11 ? 0 : $checkDigit;

        return $checkDigit !== 10 && $checkDigit === (int) $number[8];
    }

    private function locale(): string
    {
        return app()->getLocale() === 'en' ? 'en' : 'nb';
    }

    private function message(string $key): string
    {
        return [
            'invalid_org_number' => $this->locale() === 'en'
                ? 'Enter a valid Norwegian organisation number with nine digits.'
                : 'Skriv inn et gyldig norsk organisasjonsnummer med ni sifre.',
            'profile_saved' => $this->locale() === 'en'
                ? 'Your company and contact details have been saved.'
                : 'Bedrifts- og kontaktinformasjonen din er lagret.',
            'profile_deferred' => $this->locale() === 'en'
                ? 'You can complete your company and contact details later.'
                : 'Du kan fylle ut bedrifts- og kontaktopplysningene senere.',
            'duplicate_org_number' => $this->locale() === 'en'
                ? 'A company with this organisation number already exists. Contact FLYN if this is your company.'
                : 'En bedrift med dette organisasjonsnummeret finnes allerede. Kontakt FLYN hvis dette er din bedrift.',
            'duplicate_company_phone' => $this->locale() === 'en'
                ? 'This company phone number is already registered.'
                : 'Dette telefonnummeret for bedriften er allerede registrert.',
            'duplicate_contact_phone' => $this->locale() === 'en'
                ? 'This contact phone number is already registered to another contact.'
                : 'Dette telefonnummeret er allerede registrert på en annen kontaktperson.',
        ][$key];
    }
}
