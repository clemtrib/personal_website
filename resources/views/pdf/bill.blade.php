<!DOCTYPE html>
<html lang="fr">

@php
use Illuminate\Support\Str;
@endphp

<head>
    <meta charset="UTF-8">
    <title>
        facture_#{{ $bill->id }}_{{ Str::ascii(str_replace(' ', '', $bill->provider_name)) }}_{{ Str::ascii(str_replace(' ', '', $bill->customer_name)) }}
    </title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        .header {
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #555;
        }

        table#products {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #333;
            margin-bottom: 20px;
        }

        table#products th {
            background-color: #cccccc;
        }

        table#products th,
        table#products td {
            border: 1px solid #333333;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Facture #{{ $bill->id }}</h2>
        <p>Date : {{ \Carbon\Carbon::parse($bill->created_at)->format('d/m/Y') }}</p>
        <p>Période du {{ \Carbon\Carbon::parse($bill->start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($bill->end_date)->format('d/m/Y') }}</p>
    </div>

    <h3>De :</h3>
    <table width="100%" style="margin-bottom: 20px;">
        <tr style="height: 80px;">
            <td style="vertical-align: top; width: 50%;">
                <!-- Infos fournisseur comme avant -->
                @if(!empty($bill->provider_name))
                {{ $bill->provider_name }}<br />
                @endif
                @if(!empty($bill->provider_address_line_1))
                {{ $bill->provider_address_line_1 }}<br />
                @endif
                @if(!empty($bill->provider_address_line_2))
                {{ $bill->provider_address_line_2 }}<br />
                @endif
                @if(!empty($bill->provider_city) || !empty($bill->provider_province) || !empty($bill->provider_zip_code))
                {{ trim($bill->provider_city.' '.$bill->provider_province.' '.$bill->provider_zip_code) }}<br />
                @endif
                @if(!empty($bill->provider_mail))
                <a href="mailto:{{ $bill->provider_mail }}">{{ $bill->provider_mail }}</a><br />
                @endif
                @if(!empty($bill->provider_website))
                <a href="{{ $bill->provider_website }}">{{ $bill->provider_website }}</a><br />
                @endif
                @if(!empty($bill->provider_phone))
                {{ $bill->provider_phone }}<br />
                @endif
            </td>
            <td style="vertical-align: top; width: 50%; text-align: right;">
                @if($logoExists)
                <img src="{{ $logo }}" style="height: 80px; width: auto;">
                @endif
            </td>
        </tr>
    </table>

    <h3>À :</h3>
    <p>
        @if(!empty($bill->customer_name))
        {{ $bill->customer_name }}<br />
        @endif
        @if(!empty($bill->customer_company))
        {{ $bill->customer_company }}<br />
        @endif
        @if(!empty($bill->customer_address_line_1))
        {{ $bill->customer_address_line_1 }}<br />
        @endif
        @if(!empty($bill->customer_address_line_2))
        {{ $bill->customer_address_line_2 }}<br />
        @endif
        @if(!empty($bill->customer_city) || !empty($bill->customer_province) || !empty($bill->customer_zip_code))
        {{ trim($bill->customer_city.' '.$bill->customer_province.' '.$bill->customer_zip_code) }}<br />
        @endif
    </p>

    <h3>Détails</h3>
    <table id="products">
        <tr>
            <th>Libellé</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Total</th>
        </tr>
        @foreach($bill->details as $detail)
        <tr>
            <td>{{ $detail->product }}</td>
            <td>{{ $detail->quantity }}</td>
            <td>{{ number_format($detail->price, 2) }} $</td>
            <td>{{ number_format($detail->total, 2) }} $</td>
        </tr>
        @endforeach
    </table>

    <p>Sous-total : {{ number_format($bill->subtotal, 2) }} $</p>
    <p>TPS (5%) : {{ $bill->tps !== null ? number_format($bill->tps, 2) . ' $' : 'N/A' }}</p>
    <p>TVQ (9,975%) : {{ $bill->tvq !== null ? number_format($bill->tvq, 2) . ' $' : 'N/A' }}</p>
    <h3>Total à payer : {{ number_format($bill->total, 2) }} $</h3>

    @if($no_taxes)
    <p class="footer">Non inscrit aux taxes – TPS et TVQ non applicables.</p>
    @endif
</body>

</html>
