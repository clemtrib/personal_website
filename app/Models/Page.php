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
     * @var list<string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'page_seo' => 'array',
    ];

    /**
     * Mutateur pour encoder correctement les données avant l'enregistrement
     *
     * @param array|string $value
     */
    public function setPageSeoAttribute(array|string $value)
    {
        // Si c'est déjà un array, encode-le
        if (is_array($value)) {
            $this->attributes['page_seo'] = json_encode($value, JSON_UNESCAPED_SLASHES);
        }
        // Si c'est déjà une chaîne JSON valide, stocke-la telle quelle
        elseif (is_string($value)) {
            // Optionnel : vérifier si c'est un JSON valide
            json_decode($value);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->attributes['page_seo'] = $value;
            } else {
                // Tenter de réparer les anciens formats (ex: backticks)
                $fixed = str_replace('`', '"', $value);
                json_decode($fixed);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->attributes['page_seo'] = $fixed;
                } else {
                    // Dernier recours, stocke un JSON vide
                    $this->attributes['page_seo'] = '{}';
                }
            }
        }
        // Sinon, stocke un JSON vide
        else {
            $this->attributes['page_seo'] = '{}';
        }
    }
}
