<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notification de Maintenance</title>
</head>
<body>
    @if(is_object($maintenance))
        <p>Bonjour {{ $maintenance->technicien->prenom ?? 'Technicien' }},</p>

        <p>Une nouvelle tâche de maintenance vous a été assignée.</p>

        <p><strong>Détails :</strong></p>
        <ul>
            <li><strong>Description :</strong> {{ $maintenance->description ?? 'Non spécifiée' }}</li>
            <li><strong>Priorité :</strong> {{ isset($maintenance->priorite) ? ucfirst($maintenance->priorite) : 'Non définie' }}</li>
            <li><strong>Date prévue :</strong> {{ $maintenance->date_debut ?? 'Non définie' }}</li>
            <li><strong>Local :</strong> {{ $maintenance->reclamation->local->nom ?? 'Non précisé' }}</li>
        </ul>

        <p>Merci de consulter votre tableau de bord pour plus d'informations.</p>
    @else
        <p>Bonjour,</p>
        <p>Une nouvelle tâche de maintenance vous a été assignée.</p>
        <p><strong>Données de maintenance :</strong> {{ $maintenance }}</p>
        <p>Merci de consulter votre tableau de bord pour plus d'informations.</p>
    @endif

    <p>Cordialement,</p>
    <p>Équipe de Maintenance CROUS-T</p>
</body>
</html>