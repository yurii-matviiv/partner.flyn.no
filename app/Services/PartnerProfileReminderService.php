<?php

namespace App\Services;

use App\Models\PartnerProfile;
use App\Models\PartnerUser;
use App\Notifications\PartnerProfileIncompleteNotification;

class PartnerProfileReminderService
{
    public function ensure(PartnerUser $user): void
    {
        $isComplete = PartnerProfile::query()
            ->where('partner_user_id', $user->id)
            ->whereNotNull('completed_at')
            ->exists();

        if ($isComplete || $this->hasReminder($user)) {
            return;
        }

        $user->notify(new PartnerProfileIncompleteNotification($user->locale));
    }

    public function clear(PartnerUser $user): void
    {
        $user->notifications()
            ->where('data->partner_key', PartnerProfileIncompleteNotification::KEY)
            ->delete();
    }

    private function hasReminder(PartnerUser $user): bool
    {
        return $user->notifications()
            ->where('data->partner_key', PartnerProfileIncompleteNotification::KEY)
            ->exists();
    }
}
