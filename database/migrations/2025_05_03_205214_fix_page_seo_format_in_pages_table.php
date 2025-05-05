<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (\App\Models\Page::all() as $page) {
            if ($page->page_seo && is_string($page->page_seo)) {
                // Remplace les backticks par des guillemets doubles
                $fixed = str_replace('`', '"', $page->page_seo);
                // Vérifie si c'est décodable
                $decoded = json_decode($fixed, true);
                if ($decoded) {
                    $page->page_seo = $decoded;
                    $page->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
