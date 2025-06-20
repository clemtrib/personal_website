<?php

namespace App\Services;

use App\Models\Bill;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class BillPdfService
{
    public function generate(Bill $bill): string
    {
        $filename = sprintf('factures/facture_%s_%s.pdf', $bill->id, $bill->hash);

        // Vérifier si le fichier existe déjà
        if (Storage::disk('local')->exists($filename)) {
            return Storage::disk('local')->path($filename);
        }

        // Vérifier la présence de provider_logo et préparer les chemins
        $providerLogo = $bill->provider_logo ?? null;
        $logoPath = $providerLogo ? public_path($providerLogo) : null;
        $logoExists = $logoPath && file_exists($logoPath);

        // Préparer les données pour la vue
        $data = [
            'bill' => $bill,
            'no_taxes' => is_null($bill->tps) && is_null($bill->tvq),
            'logo' => $logoPath,
            'logoExists' => $logoExists,
        ];

        // Générer le PDF depuis une vue Blade (ex: resources/views/pdf/bill.blade.php)
        $pdf = Pdf::loadView('pdf.bill', $data);

        // Sauvegarder le PDF dans le dossier storage/app/factures
        Storage::disk('local')->put($filename, $pdf->output());

        return Storage::disk('local')->path($filename);
    }
}
