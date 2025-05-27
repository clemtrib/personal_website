<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'hash',
        'provider_name',
        'provider_address_line_1',
        'provider_address_line_2',
        'provider_zip_code',
        'provider_city',
        'provider_province',
        'provider_country',
        'customer_name',
        'customer_address_line_1',
        'customer_address_line_2',
        'customer_zip_code',
        'customer_city',
        'customer_province',
        'customer_country',
        'subtotal',
        'tps',
        'tvq',
        'total',
        'id_tps',
        'id_tvq',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'hash',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
