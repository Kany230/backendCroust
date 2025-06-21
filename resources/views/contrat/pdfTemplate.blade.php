<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat {{ $contrat->reference }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

          .logos {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .logos img {
            height: 50px;
            margin: 0 15px;
            vertical-align: middle;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .reference {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 30%;
            padding: 5px 10px 5px 0;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        
        .financial-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        
        .financial-amount {
            font-size: 16px;
            font-weight: bold;
            color: #27ae60;
        }
        
        .conditions {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #3498db;
            margin: 20px 0;
        }
        
        .conditions h4 {
            margin-top: 0;
            color: #2c3e50;
        }
        
        .conditions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .conditions li {
            margin-bottom: 8px;
        }
        
        .signatures {
            display: table;
            width: 100%;
            margin-top: 50px;
        }
        
        .signature-section {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 10px;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #bdc3c7;
            padding-top: 15px;
        }
        
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

   <div class="logos">
        <img src="{{ asset('images/crous-t-logo.jpg') }}" alt="CROUS-T">
        <img src="{{ asset('images/sigepal-logo.jpg') }}" alt="SIGePaL">
    </div>

    <div class="header">
        <div class="title">CONTRAT DE {{ strtoupper($contrat->type) }}</div>
        <div class="reference">Référence: {{ $contrat->reference }}</div>
    </div>

    <div class="section">
        <div class="section-title">INFORMATIONS GÉNÉRALES</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Type de contrat:</div>
                <div class="info-value">{{ ucfirst($contrat->type) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de début:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($contrat->dateDebut)->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de fin:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($contrat->dateFin)->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Durée:</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($contrat->dateDebut)->diffInMonths(\Carbon\Carbon::parse($contrat->dateFin)) }} mois
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Statut:</div>
                <div class="info-value">{{ ucfirst($contrat->statut) }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">INFORMATIONS LOCATAIRE</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nom complet:</div>
                <div class="info-value">{{ $user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $user->email }}</div>
            </div>
            @if(isset($user->telephone))
            <div class="info-row">
                <div class="info-label">Téléphone:</div>
                <div class="info-value">{{ $user->telephone }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">DÉTAILS DE LA RÉSERVATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Référence réservation:</div>
                <div class="info-value">{{ $reservation->reference ?? 'N/A' }}</div>
            </div>
            @if(isset($reservation->bien))
            <div class="info-row">
                <div class="info-label">Bien loué:</div>
                <div class="info-value">{{ $reservation->bien->nom ?? 'N/A' }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">INFORMATIONS FINANCIÈRES</div>
        <div class="financial-box">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Montant:</div>
                    <div class="info-value">
                        <span class="financial-amount">{{ number_format($contrat->montant, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fréquence de paiement:</div>
                    <div class="info-value">{{ ucfirst($contrat->frequence_paiement) }}</div>
                </div>
            </div>
        </div>
    </div>

    @if($contrat->conditions)
    <div class="section">
        <div class="section-title">CONDITIONS GÉNÉRALES</div>
        <div class="conditions">
            {!! nl2br(e($contrat->conditions)) !!}
        </div>
    </div>
    @endif

    <div class="signatures">
        <div class="signature-section">
            <div><strong>LE BAILLEUR</strong></div>
            <div class="signature-line">
                Signature et date
            </div>
        </div>
        <div class="signature-section">
            <div><strong>LE LOCATAIRE</strong></div>
            <div class="signature-line">
                {{ $user->name }}<br>
                Signature et date
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Document généré par SIGePaL-CROUST-T.</p>
        <p>www.croust.sn</p>
    </div>
</body>
</html>