<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Rapport de Maintenance #{{ data_get($maintenance, 'id', 'N/A') }}</title>
    <style>
        /* Ton style ici */
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
        .company-info { text-align: right; font-size: 12px; color: #666; margin-bottom: 20px; }
        .maintenance-info { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .details-table th, .details-table td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        .details-table th { background-color: #f8f9fa; font-weight: bold; }
        .section { margin: 20px 0; page-break-inside: avoid; }
        .section-title { font-size: 16px; font-weight: bold; color: #007bff; border-bottom: 1px solid #007bff; padding-bottom: 5px; margin-bottom: 15px; }
        .status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .status-termine { background-color: #d4edda; color: #155724; }
        .footer { position: fixed; bottom: 20px; left: 20px; right: 20px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="company-info">
        <strong>{{ config('app.name') }}</strong><br>
        Date de génération : {{ now()->format('d/m/Y à H:i') }}
    </div>

    <div class="header">
        <h1>RAPPORT DE MAINTENANCE</h1>
        <h2>Maintenance #{{ data_get($maintenance, 'id', 'N/A') }}</h2>
        @if(data_get($maintenance, 'statut'))
            <span class="status-badge status-{{ data_get($maintenance, 'statut') }}">
                {{ strtoupper(data_get($maintenance, 'statut')) }}
            </span>
        @endif
    </div>

    <div class="maintenance-info">
        <h3>Informations générales</h3>
        <p><strong>Description :</strong> {{ data_get($maintenance, 'description', 'N/A') }}</p>
        <p><strong>Priorité :</strong> {{ ucfirst(data_get($maintenance, 'priorite', 'N/A')) }}</p>
        <p><strong>Date de signalement :</strong> 
            {{ optional(data_get($maintenance, 'date_signalement'))->format('d/m/Y à H:i') ?? 'N/A' }}
        </p>
    </div>

    <div class="section">
        <div class="section-title">Détails de la maintenance</div>
        <table class="details-table">
            @if(data_get($maintenance, 'date_debut'))
            <tr>
                <th>Date de début</th>
                <td>
                    {{ is_string(data_get($maintenance, 'date_debut')) 
                        ? data_get($maintenance, 'date_debut') 
                        : optional(data_get($maintenance, 'date_debut'))->format('d/m/Y à H:i') 
                    }}
                </td>
            </tr>
            @endif

            @if(data_get($maintenance, 'date_fin_prevue'))
            <tr>
                <th>Date de fin prévue</th>
                <td>
                    {{ is_string(data_get($maintenance, 'date_fin_prevue')) 
                        ? data_get($maintenance, 'date_fin_prevue') 
                        : optional(data_get($maintenance, 'date_fin_prevue'))->format('d/m/Y à H:i') 
                    }}
                </td>
            </tr>
            @endif

            @if(data_get($maintenance, 'date_fin_reelle'))
            <tr>
                <th>Date de fin réelle</th>
                <td>
                    {{ is_string(data_get($maintenance, 'date_fin_reelle')) 
                        ? data_get($maintenance, 'date_fin_reelle') 
                        : optional(data_get($maintenance, 'date_fin_reelle'))->format('d/m/Y à H:i') 
                    }}
                </td>
            </tr>
            @endif

            @if(data_get($maintenance, 'technicien.nom'))
            <tr>
                <th>Technicien assigné</th>
                <td>{{ data_get($maintenance, 'technicien.nom') }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if(!empty(data_get($maintenance, 'materiel_utilise')))
        @php
            $materiels = is_string(data_get($maintenance, 'materiel_utilise')) 
                ? json_decode(data_get($maintenance, 'materiel_utilise'), true) 
                : data_get($maintenance, 'materiel_utilise');
        @endphp
        @if(is_array($materiels) && count($materiels) > 0)
        <div class="section">
            <div class="section-title">Matériel utilisé</div>
            <ul>
                @foreach($materiels as $materiel)
                    <li>{{ $materiel }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    @endif

    @if(!empty(data_get($maintenance, 'rapport_final')))
    <div class="section">
        <div class="section-title">Rapport final</div>
        <p>{{ data_get($maintenance, 'rapport_final') }}</p>
    </div>
    @endif

    @if(!empty(data_get($maintenance, 'remarques')))
    <div class="section">
        <div class="section-title">Remarques</div>
        <p>{{ data_get($maintenance, 'remarques') }}</p>
    </div>
    @endif

    @if(data_get($maintenance, 'reclamation.id'))
    <div class="section">
        <div class="section-title">Réclamation associée</div>
        <p><strong>ID Réclamation :</strong> #{{ data_get($maintenance, 'reclamation.id') }}</p>
        @if(!empty(data_get($maintenance, 'reclamation.description')))
            <p><strong>Description :</strong> {{ data_get($maintenance, 'reclamation.description') }}</p>
        @endif
    </div>
    @endif

    @if(data_get($maintenance, 'reclamation.user.nom'))
    <div class="section">
        <div class="section-title">Informations de l'utilisateur</div>
        <p><strong>Nom :</strong> {{ data_get($maintenance, 'reclamation.user.nom') }}</p>
        <p><strong>Email :</strong> {{ data_get($maintenance, 'reclamation.user.email', 'N/A') }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Rapport généré automatiquement par {{ config('app.name') }} - {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
