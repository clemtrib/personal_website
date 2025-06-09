<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Mail\BillMailable;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Customer;
use App\Services\BillPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BillController extends Controller
{

    const VALIDATION_RULES_GENERATE_INVOICE = [
        'customer_id' => 'required|integer|min:0',
        'label' => 'required|string|max:255',
        'hours' => 'required|integer|min:0',
        'date_range' => ['required', 'array', 'size:2'],
        'date_range.*' => ['required', 'date_format:Y-m-d'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Bills', [
            'bills' => Bill::where('is_cancelled', 0)->orderBy('id', 'DESC')->paginate(10)
        ]);
    }

    /**
     *
     */
    public function create()
    {

        return Inertia::render(
            'BillsForm',
            [
                'customers' => Customer::orderBy('name', 'ASC')->get()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, BillPdfService $pdfService)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES_GENERATE_INVOICE);

        $customer = Customer::findOrFail($validatedData['customer_id']);

        // Générer un hash unique de 16 caractères
        do {
            $hash = Str::random(128);
        } while (Bill::where('hash', $hash)->exists());

        try {

            $bill = new Bill();
            $bill->customer_id = $customer->id;
            $bill->hash = $hash;
            $bill->start_date = $validatedData['date_range'][0];
            $bill->end_date = $validatedData['date_range'][1];
            $bill->provider_name = getenv('BILLING_FULLNAME') ?? '';
            $bill->provider_address_line_1 = getenv('BILLING_ADDRESS_LINE_1') ?? '';
            $bill->provider_address_line_2 = getenv('BILLING_ADDRESS_LINE_2') ?? '';
            $bill->provider_zip_code = getenv('BILLING_ZIP_CODE') ?? '';
            $bill->provider_city = getenv('BILLING_CITY') ?? '';
            $bill->provider_province = getenv('BILLING_PROVINCE') ?? '';
            $bill->provider_country = getenv('BILLING_COUNTRY') ?? '';
            $bill->provider_logo = getenv('BILLING_LOGO') ?? '';
            $bill->provider_phone = getenv('BILLING_PHONE') ?? '';
            $bill->provider_mail = getenv('BILLING_EMAIL') ?? '';
            $bill->customer_name = $customer->name;
            $bill->customer_company = $customer->company ?? '';
            $bill->customer_address_line_1 = $customer->address_line_1 ?? '';
            $bill->customer_address_line_2 = $customer->address_line_2 ?? '';
            $bill->customer_zip_code = $customer->zip_code ?? '';
            $bill->customer_city = $customer->city ?? '';
            $bill->customer_province = $customer->province ?? '';
            $bill->customer_country = $customer->country ?? '';
            $bill->subtotal = $validatedData['hours'] * $customer->tjm;
            $bill->id_tps = getenv('NO_TPS') ?? null;
            $bill->id_tvq = getenv('NO_TVQ') ?? null;
            $bill->tps = $bill->id_tps && $bill->id_tvq ? $bill->subtotal * 0.05 : null;
            $bill->tvq = $bill->id_tps && $bill->id_tvq  ? $bill->subtotal * 0.09975 : null;
            $bill->total = $bill->subtotal + $bill->tps + $bill->tvq;
            $bill->save();

            $billDetail = new BillDetail();
            $billDetail->bill_id = $bill->id;
            $billDetail->product = $validatedData['label'];
            $billDetail->price = $customer->tjm;
            $billDetail->quantity = $validatedData['hours'];
            $billDetail->total = $validatedData['hours'] * $customer->tjm;
            $billDetail->save();

            $pdfService->generate($bill);

            return to_route('bills')->with('success', 'Facture créée avec succès');
            //return to_route('bills')->with('success', 'Facture créée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     *
     */
    public function downloadInvoice(Bill $bill, BillPdfService $pdfService)
    {
        $pdfPath = $pdfService->generate($bill);

        // Pour afficher dans le navigateur :
        return response()->file(
            $pdfPath,
            [
                'Content-Type' => 'application/pdf',
                // "inline" pour affichage, "attachment" pour téléchargement
                'Content-Disposition' => 'inline; filename="' . basename($pdfPath) . '"'
            ]
        );
    }

    /**
     *
     */
    public function sendByMail(Bill $bill, BillPdfService $pdfService)
    {
        $bill->is_send = true;
        try {
            $pdfPath = $pdfService->generate($bill);
            $r = Mail::to($bill->customer->email)->send(new BillMailable($bill, $pdfPath));
            if ($r) {
                $bill->save();
            }
            return to_route('bills')->with('success', 'Email envoyé');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }

    /**
     * Invoice is paid
     */
    public function isPaid(Bill $bill)
    {
        $bill->is_paid = true;
        try {
            $bill->save();
            return to_route('bills')->with('success', 'Facture payée');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        $bill->is_cancelled = true;
        try {
            $bill->save();
            return to_route('bills')->with('success', 'Facture annulée avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }
}
