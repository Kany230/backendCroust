<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contrat</title>
</head>
<body>
    <h1>Bonjour {{ $contract->user->firstname }} {{ $contract->user->lastname }},</h1>

    <p>Veuillez trouver ci-joint votre contrat de location pour la réservation :</p>

    <ul>
        <li><strong>Référence :</strong> {{ $contract->reference }}</li>
        <li><strong>Date de début :</strong> {{ $contract->dateDebut->format('d/m/Y') }}</li>
        <li><strong>Date de fin :</strong> {{ $contract->dateFin->format('d/m/Y') }}</li>
        <li><strong>Montant :</strong> {{ number_format($contract->montant, 0, ',', ' ') }} FCFA</li>
    </ul>

    <p>Merci de respecter les conditions stipulées dans le contrat.</p>

    <p>Cordialement,<br>L'administration</p>
</body>
</html>
