<?php

namespace App\Http\Middleware;

use App\Models\PartnerProfile;
use App\Models\PartnerUser;
use App\Services\PartnerProfileReminderService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePartnerProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('partner')->user();

        // The dashboard is an intentionally data-free overview. An
        // email-verified user may explore it before finishing the profile;
        // every future private page stays behind the completion gate.
        $isComplete = $user && PartnerProfile::query()
            ->where('partner_user_id', $user->getAuthIdentifier())
            ->whereNotNull('completed_at')
            ->exists();

        if ($user instanceof PartnerUser && ! $isComplete) {
            app(PartnerProfileReminderService::class)->ensure($user);
        }

        // Dashboard and the profile page are always available. Otherwise an
        // incomplete profile would redirect from /profile back to itself.
        if (in_array($request->path(), ['partner-panel', 'partner-panel/profile'], true)) {
            return $next($request);
        }

        if ($user && ! $isComplete) {
            return redirect(\App\Filament\Pages\PartnerProfilePage::getUrl());
        }

        return $next($request);
    }
}
