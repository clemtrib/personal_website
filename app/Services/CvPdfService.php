<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CvPdfService
{
    public function generate(array $data): string
    {
        $filename = 'cv.pdf';

        // Vérifier si le fichier existe déjà
        if (Storage::disk('local')->exists($filename)) {
            return Storage::disk('local')->path($filename);
        }

        // Générer le PDF depuis une vue Blade (ex: resources/views/pdf/cv.blade.php)
        Carbon::setLocale('fr');
        $pdf = Pdf::loadView('pdf.cv', $data);

        // Sauvegarder le PDF dans le dossier storage/app
        Storage::disk('local')->put($filename, $pdf->output());

        return Storage::disk('local')->path($filename);
    }
}
