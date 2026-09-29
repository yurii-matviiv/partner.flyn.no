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

        // A profile is optional. Its missing data produces one persistent
        // notification, but it must never block access to panel pages.
        // Access control for future sensitive areas belongs to their own
        // policies, not to this reminder middleware.
        return $next($request);
    }
}
