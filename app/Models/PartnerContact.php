<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Contact person stored in the existing ERM clients table. */
class PartnerContact extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'type',
        'customer_type',
        'first_name',
        'last_name',
        'phone',
        'email',
        'locale',
        'source',
    ];
}
