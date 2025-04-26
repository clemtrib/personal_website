<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'page_slug',
        'page_name',
        'hero_subtitle',
        'hero_title',
        'hero_description',
        'hero_image',
        'content_text',
        'content_image',
        'page_seo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [];

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
