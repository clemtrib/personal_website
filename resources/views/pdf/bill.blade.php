<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $bill->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { margin-bottom: 20px; }
        .footer { margin-top: 30px; font-size: 0.9em; color: #555; }
        table { width: 100%; border-collapse: collapse; border: 1px solid #333;  margin-bottom: 20px; }
        th { background-color: #cccccc; }
        th, td { border: 1px solid #333333; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Facture #{{ $bill->id }}</h2>
        <p>Date : {{ \Carbon\Carbon::parse($bill->created_at)->format('d/m/Y') }}</p>
        <p>Période du {{ \Carbon\Carbon::parse($bill->start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($bill->end_date)->format('d/m/Y') }}</p>
    </div>

    <h3>De :</h3>
    <p>
        {{ $bill->provider_name }}<br />
        {{ $bill->provider_address_line_1 }}<br />
        {{ $bill->provider_address_line_2 }}<br />
        {{ $bill->provider_city }} {{ $bill->provider_province }} {{ $bill->provider_zip_code }}<br />
    </p>

    <h3>À :</h3>
    <p>
        {{ $bill->customer_name }}<br />
        {{ $bill->customer_address_line_1 }}<br />
        {{ $bill->customer_address_line_2 }}<br />
        {{ $bill->customer_zip_code }} {{ $bill->customer_province }} {{ $bill->customer_city }}<br />
    </p>

    <h3>Détails</h3>
    <table>
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
