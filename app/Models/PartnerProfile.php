<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerProfile extends Model
{
    protected $table = 'partner_profiles';

    protected $fillable = [
        'partner_user_id',
        'customer_company_id',
        'contact_client_id',
        'contact_job_title',
        'deferred_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'deferred_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}
