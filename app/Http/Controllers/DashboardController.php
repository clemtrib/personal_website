<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Bill;
use App\Models\Message;
use App\Models\Timeslot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{

    /**
     * Afficher la page Dashboard avec :
     * - le total des ventes HT pour les 3 dernières années avec un lien pour les exporter au format CSV.
     * - les 3 dernières factures.
     * - les 3 derniers messages envoyés avec le formulaire de contact.
     * - les 4 prochains rendez-vous pris avec Google Meet.
     * - le total des ventes pour chaque mois de la dernière année.
     * Un bouton pour nettoyer le cache.
     * Un bouton pour nettoyer la table Timeslots.
     */
    public function index()
    {

        $allBills = Bill::where('is_cancelled', 0)
            ->orderByDesc('created_at')
            ->get();

        $annualSummary = $allBills->groupBy(function ($bill) {
            return $bill->created_at->format('Y');
        })
            ->map(function ($bills, $year) {
                return [
                    'year' => $year,
                    'total_subtotal' => $bills->sum('subtotal'),
                    'total_tps' => $bills->sum('tps'),
                    'total_tvq' => $bills->sum('tvq'),
                    'total_total' => $bills->sum('total'),
                ];
            })
            ->sortByDesc('year')
            ->take(3)
            ->values();

        /** HT sur le mois en cours et les 12 précedents */

        // Génère la liste des 13 derniers mois (mois en cours inclus)
        $now = Carbon::now();
        $last13Months = collect();
        for ($i = 0; $i < 13; $i++) {
            $date = $now->copy()->subMonths($i);
            $last13Months->push([
                'year_month' => $date->format('Y-m'),
                'month_name' => $date->format('Y-m')
            ]);
        }
        $last13Months = $last13Months->sortBy('year_month');

        // Requête pour compter les ventes par mois
        $salesByMonth = Bill::where('is_cancelled', 0)
            ->where('created_at', '>=', $now->copy()->subMonths(12)->startOfMonth())
            ->get()
            ->groupBy(function ($bill) {
                return $bill->created_at->format('Y-m');
            })
            ->map(function ($bills, $yearMonth) {
                return [
                    'year_month' => $yearMonth,
                    'total_subtotal_month' => $bills->sum('subtotal'),
                ];
            })
            ->sortKeys()
            ->values();

        // Jointure pour afficher tous les mois, même sans vente
        $last13MonthsSales = $last13Months->map(function ($month) use ($salesByMonth) {
            $sale = $salesByMonth->firstWhere('year_month', $month['year_month']);
            return [
                'month' => $month['month_name'],
                'total_subtotal_month' => $sale ? $sale['total_subtotal_month'] : 0
            ];
        })->values()->all();

        /** Affichage */
        return Inertia::render('Dashboard', [
            'annualSummary' => $annualSummary,

            'last12MonthsSales' => $last13MonthsSales,

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

    /**
     * Nettoyer le cache
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return to_route('dashboard')->with('success', 'Cache effacé avec succès');
    }

    /**
     * Nettoyer la table Timeslots
     */
    public function clearTimeslotsTable()
    {
        Artisan::call('app:timeslots-command');
        return to_route('dashboard')->with('success', 'Table Timeslots nettoyée avec succès');
    }
}
