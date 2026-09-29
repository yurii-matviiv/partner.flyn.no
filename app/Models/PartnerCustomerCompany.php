<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Read/write adapter for the ERM billing company. Partner never keeps a
 * duplicate company record: this is the same company used by invoices.
 */
class PartnerCustomerCompany extends Model
{
    protected $table = 'customer_companies';

    protected $fillable = [
        'legal_name',
        'org_number',
        'legal_form',
        'address_line',
        'postal_code',
        'city',
        'country',
        'email',
        'phone',
        'customer_type',
    ];

    public function setOrgNumberAttribute(?string $value): void
    {
        $this->attributes['org_number'] = $value === null ? null : preg_replace('/\D+/', '', $value);
    }
}
