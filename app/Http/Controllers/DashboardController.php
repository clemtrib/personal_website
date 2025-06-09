<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Mail\BillMailable;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Customer;
use App\Models\Message;
use App\Models\Timeslot;


use App\Services\BillPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DashboardController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $sumByYears = Bill::selectRaw('YEAR(created_at) as year');
        if (getenv('DB_CONNECTION') === 'sqlite') {
            $sumByYears = Bill::selectRaw("strftime('%Y', created_at) as year");
        }

        return Inertia::render('Dashboard', [
            'annualSummary' => $sumByYears
                ->selectRaw('SUM(subtotal) as total_subtotal')
                ->selectRaw('SUM(tps) as total_tps')
                ->selectRaw('SUM(tvq) as total_tvq')
                ->selectRaw('SUM(total) as total_total')
                ->where('is_cancelled', 0)
                ->groupBy('year')
                ->orderByDesc('year')
                ->take(3)
                ->get(),

            'recentBills' => Bill::where('is_cancelled', 0)
                ->orderByDesc('created_at')
                ->take(3)
                ->get([
                    'id',
                    'customer_name',
                    'subtotal',
                    'total',
                    'created_at'
                ]),

            'messages' => Message::orderBy('created_at', 'desc')
                ->take(3)
                ->get(),

            'meets' => Timeslot::where('start_datetime', '>=', \Illuminate\Support\Carbon::now())
                ->whereNotNull('summary')
                ->whereNotNull('recipient_fullname')
                ->whereNotNull('recipient_email')
                ->orderBy('start_datetime')
                ->take(4)
                ->get()
        ]);
    }
}
