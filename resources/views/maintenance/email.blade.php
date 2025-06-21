<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rapport de maintenance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-termine {
            background-color: #d4edda;
            color: #155724;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th,
        .details-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .details-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .rapport-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔧 Rapport de maintenance</h1>
        <p>
            <strong>Maintenance #{{ $maintenance->id ?? 'N/A' }}</strong>
            @if(isset($maintenance->statut))
                <span class="status-badge status-{{ $maintenance->statut }}">{{ ucfirst($maintenance->statut) }}</span>
            @endif
        </p>
    </div>

    <p>Bonjour,</p>
    
    <p>La maintenance <strong>#{{ $maintenance->id ?? 'N/A' }}</strong> a été <strong>{{ $maintenance->statut ?? 'terminée' }}</strong>.</p>
    
    <table class="details-table">
        @if(isset($maintenance->description))
        <tr>
            <th>Description</th>
            <td>{{ $maintenance->description }}</td>
        </tr>
        @endif
        
        @if(isset($maintenance->priorite))
        <tr>
            <th>Priorité</th>
            <td>
                @switch($maintenance->priorite)
                    @case('urgente')
                        🔴 {{ ucfirst($maintenance->priorite) }}
                        @break
                    @case('eleve')
                        🟠 Élevée
                        @break
                    @case('normal')
                        🟡 Normale
                        @break
                    @case('faible')
                        🟢 Faible
                        @break
                    @default
                        {{ ucfirst($maintenance->priorite) }}
                @endswitch
            </td>
        </tr>
        @endif
        
        @if(isset($maintenance->date_debut) && $maintenance->date_debut)
        <tr>
            <th>Date de début</th>
            <td>
                @if(is_string($maintenance->date_debut))
                    {{ $maintenance->date_debut }}
                @else
                    {{ $maintenance->date_debut->format('d/m/Y à H:i') }}
                @endif
            </td>
        </tr>
        @endif
        
        @if(isset($maintenance->date_fin_reelle) && $maintenance->date_fin_reelle)
        <tr>
            <th>Date de fin</th>
            <td>
                @if(is_string($maintenance->date_fin_reelle))
                    {{ $maintenance->date_fin_reelle }}
                @else
                    {{ $maintenance->date_fin_reelle->format('d/m/Y à H:i') }}
                @endif
            </td>
        </tr>
        @endif
        
        @if(isset($maintenance->technicien) && $maintenance->technicien)
        <tr>
            <th>Technicien</th>
            <td>{{ $maintenance->technicien->name ?? 'N/A' }}</td>
        </tr>
        @endif
        
        @if(isset($maintenance->materiel_utilise))
            @php
                $materiels = is_string($maintenance->materiel_utilise) ? json_decode($maintenance->materiel_utilise, true) : $maintenance->materiel_utilise;
            @endphp
            @if(is_array($materiels) && count($materiels) > 0)
            <tr>
                <th>Matériel utilisé</th>
                <td>
                    @foreach($materiels as $materiel)
                        • {{ $materiel }}<br>
                    @endforeach
                </td>
            </tr>
            @endif
        @endif
    </table>
    
    @if(isset($maintenance->rapport_final) && $maintenance->rapport_final)
    <div class="rapport-section">
        <h3>📝 Rapport final :</h3>
        <p>{{ $maintenance->rapport_final }}</p>
    </div>
    @endif

    @if(isset($maintenance->remarques) && $maintenance->remarques)
    <div class="rapport-section">
        <h3>💬 Remarques :</h3>
        <p>{{ $maintenance->remarques }}</p>
    </div>
    @endif
    
    @if(isset($maintenance->rapport_pdf_path) && $maintenance->rapport_pdf_path)
    <p>📎 <strong>Le rapport détaillé est disponible en pièce jointe de cet email.</strong></p>
    @endif

    @if(isset($maintenance->reclamation) && $maintenance->reclamation)
    <p><small>Cette maintenance était liée à la réclamation #{{ $maintenance->reclamation->id ?? 'N/A' }}.</small></p>
    @endif
    
    <div class="footer">
        <p>Cordialement,<br>
        <strong>L'équipe de maintenance</strong><br>
        {{ config('app.name') }}</p>
        
        <p><small>Cet email a été généré automatiquement le {{ now()->format('d/m/Y à H:i') }}.</small></p>
    </div>
</body>
</html>