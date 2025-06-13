<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f9f9f9;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .company-details {
            text-align: right;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .invoice-details h3, .customer-details h3, .order-items h3 {
            margin-top: 0;
            color: #6366F1;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .customer-details {
            margin-bottom: 30px;
        }
        .order-items table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .order-items th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 10px;
        }
        .order-items td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .totals {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .totals table {
            width: 300px;
        }
        .totals td {
            padding: 8px;
        }
        .totals .grand-total {
            font-weight: bold;
            font-size: 1.2em;
            border-top: 2px solid #eee;
        }
        .notes {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.8em;
        }
        .status-pending { background-color: #FEF3C7; color: #92400E; }
        .status-confirmed { background-color: #DBEAFE; color: #1E40AF; }
        .status-shipped { background-color: #E0F2FE; color: #0369A1; }
        .status-delivered { background-color: #D1FAE5; color: #065F46; }
        .status-canceled { background-color: #FEE2E2; color: #B91C1C; }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            @page {
                margin: 1cm;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="logo">
                <h1>KOMPLEMON</h1>
            </div>
            <div class="company-details">
                <h2>Facture</h2>
                <p>KOMPLEMON SARL<br>
                123 Avenue des Protéines<br>
                75001 Paris, France<br>
                Tél: +33 1 23 45 67 89<br>
                Email: contact@komplemon.com</p>
            </div>
        </div>
        
        <div class="invoice-details-grid">
            <div class="invoice-details">
                <h3>Détails de la commande</h3>
                <p><strong>Commande N°:</strong> #{{ $order->id }}</p>
                <p><strong>Date:</strong> {{ $order->date_commande->format('d/m/Y H:i') }}</p>
                <p><strong>Statut:</strong> 
                    <span class="status 
                        @if($order->statut == 'en attente') status-pending
                        @elseif($order->statut == 'confirmé') status-confirmed
                        @elseif($order->statut == 'expédié') status-shipped
                        @elseif($order->statut == 'livré') status-delivered
                        @elseif($order->statut == 'annulé') status-canceled
                        @endif">
                        {{ $order->statut }}
                    </span>
                </p>
            </div>
            
            <div class="customer-details">
                <h3>Client</h3>
                <p><strong>Nom:</strong> {{ $order->client->nom ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $order->client->email ?? 'N/A' }}</p>
                <p><strong>Téléphone:</strong> {{ $order->client->telephone ?? 'N/A' }}</p>
                <p><strong>Adresse:</strong> {{ $order->client->adresse ?? 'N/A' }}</p>
            </div>
        </div>
        
        <div class="order-items">
            <h3>Articles commandés</h3>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->nom ?? 'Produit inconnu' }}</td>
                        <td>{{ number_format($item->prix_unitaire, 2) }} €</td>
                        <td>{{ $item->quantite }}</td>
                        <td>{{ number_format($item->prix_unitaire * $item->quantite, 2) }} €</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="totals">
                <table>
                    <tr>
                        <td>Sous-total:</td>
                        <td>{{ number_format($order->total, 2) }} €</td>
                    </tr>
                    <tr>
                        <td>TVA (20%):</td>
                        <td>{{ number_format($order->total * 0.2, 2) }} €</td>
                    </tr>
                    <tr class="grand-total">
                        <td>Total:</td>
                        <td>{{ number_format($order->total * 1.2, 2) }} €</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="notes">
            <h3>Notes</h3>
            <p>Merci pour votre commande! Si vous avez des questions concernant cette facture, veuillez contacter notre service client.</p>
        </div>
        
        <div class="footer">
            <p>KOMPLEMON SARL - SIRET: 123 456 789 00015 - TVA: FR12 345 678 901</p>
        </div>
        
        <div class="no-print" style="margin-top: 30px; text-align: center;">
            <button onclick="window.print()" style="padding: 10px 20px; background: #6366F1; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                Imprimer cette facture
            </button>
            <button onclick="window.close()" style="padding: 10px 20px; background: #6B7280; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; margin-left: 10px;">
                Fermer
            </button>
        </div>
    </div>
</body>
</html>
