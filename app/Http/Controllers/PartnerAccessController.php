<?php

namespace App\Http\Controllers;

use App\Models\PartnerUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PartnerAccessController
{
    public function setLocale(Request $request, string $locale): RedirectResponse
    {
        $request->session()->put('partner_locale', $locale);

        if ($user = $request->user('partner')) {
            $user->forceFill(['locale' => $locale])->save();
        }

        $returnTo = (string) $request->input('return_to', '/partner-panel/login');

        return redirect(Str::startsWith($returnTo, '/') ? $returnTo : '/partner-panel/login');
    }

    public function requestCode(Request $request): RedirectResponse
    {
        $email = Str::lower((string) $request->validate(['email' => ['required', 'email:rfc,dns', 'max:255']])['email']);

        if (config('mail.default') !== 'smtp') {
            Log::warning('Partner access code requested while SMTP is not configured.', [
                'mailer' => config('mail.default'),
            ]);

            return back()->withErrors(['email' => $this->message('email_unavailable')])->onlyInput('email');
        }

        $rateLimitKey = 'partner-access-code:' . hash('sha256', $email . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            return back()->withErrors(['email' => $this->message('too_many_requests')])->onlyInput('email');
        }

        $code = (string) random_int(100000, 999999);
        $locale = $this->locale($request, $email);

        try {
            Mail::raw($this->mailBody($code, $locale), function ($message) use ($email, $locale): void {
                $message->to($email)->subject($locale === 'en' ? 'Your FLYN Partner access code' : 'Din tilgangskode til FLYN Partner');
            });
        } catch (\Throwable $exception) {
            Log::error('Partner access code could not be delivered.', [
                'exception' => $exception,
            ]);

            return back()->withErrors(['email' => $this->message('email_unavailable')])->onlyInput('email');
        }

        DB::table('partner_email_login_codes')->updateOrInsert(
            ['email' => $email],
            [
                'code_hash' => Hash::make($code),
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0,
                'locale' => $locale,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        RateLimiter::hit($rateLimitKey, 600);

        return redirect()->route('partner.access.verify', ['email' => $email])
            ->with('status', $this->message('code_sent'));
    }

    public function showVerifyForm(Request $request): View
    {
        return view('partner.verify-access-code', [
            'email' => (string) $request->query('email'),
            'locale' => $this->locale($request),
        ]);
    }

    public function verifyCode(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'code' => ['required', 'digits:6'],
        ]);
        $email = Str::lower($data['email']);

        $user = DB::transaction(function () use ($email, $data, $request): ?PartnerUser {
            $record = DB::table('partner_email_login_codes')->where('email', $email)->lockForUpdate()->first();

            if (! $record || $record->expires_at < now() || $record->attempts >= 5 || ! Hash::check($data['code'], $record->code_hash)) {
                if ($record) {
                    DB::table('partner_email_login_codes')->where('email', $email)->increment('attempts');
                }

                return null;
            }

            $user = PartnerUser::query()->firstOrCreate(
                ['email' => $email],
                [
                    'locale' => $record->locale,
                    'email_verified_at' => now(),
                    'is_active' => true,
                ],
            );

            $user->forceFill([
                'email_verified_at' => $user->email_verified_at ?: now(),
                'locale' => $record->locale,
            ])->save();

            DB::table('partner_email_login_codes')->where('email', $email)->delete();

            return $user;
        });

        if (! $user || ! $user->is_active) {
            return back()->withErrors(['code' => $this->message('invalid_code')])->withInput();
        }

        Auth::guard('partner')->login($user, true);
        $request->session()->regenerate();
        $request->session()->put('partner_locale', $user->locale);

        return redirect()->route('partner.onboarding');
    }

    private function locale(Request $request, ?string $email = null): string
    {
        $sessionLocale = $request->session()->get('partner_locale');

        if (in_array($sessionLocale, ['nb', 'en'], true)) {
            return $sessionLocale;
        }

        $storedLocale = $email
            ? PartnerUser::query()->where('email', $email)->value('locale')
            : null;

        return in_array($storedLocale, ['nb', 'en'], true) ? $storedLocale : 'nb';
    }

    private function message(string $key): string
    {
        return [
            'code_sent' => app()->getLocale() === 'en' ? 'Check your email for a six-digit access code.' : 'Sjekk e-posten din for en sekssifret tilgangskode.',
            'invalid_code' => app()->getLocale() === 'en' ? 'The code is invalid, expired, or has been used already.' : 'Koden er ugyldig, utløpt eller allerede brukt.',
            'too_many_requests' => app()->getLocale() === 'en' ? 'Please wait a few minutes before requesting another code.' : 'Vent noen minutter før du ber om en ny kode.',
            'email_unavailable' => app()->getLocale() === 'en' ? 'Email delivery is temporarily unavailable. Please try again later or contact FLYN.' : 'E-postlevering er midlertidig utilgjengelig. Prøv igjen senere eller kontakt FLYN.',
        ][$key];
    }

    private function mailBody(string $code, string $locale): string
    {
        return $locale === 'en'
            ? "Your FLYN Partner access code is: {$code}\n\nThe code expires in 10 minutes."
            : "Din tilgangskode til FLYN Partner er: {$code}\n\nKoden utløper om 10 minutter.";
    }
}
