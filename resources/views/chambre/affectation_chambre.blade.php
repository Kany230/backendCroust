<!DOCTYPE html>
<html>
<head>
    <title>Notification de chambre</title>
</head>
<body>
    <p>Bonjour {{ $user->prenom }},</p>

    @if($type === 'assignation')
        <p>Vous avez été assigné à la chambre numéro <strong>{{ $chambre->numero }}</strong> dans le pavillon <strong>{{ $chambre->pavillon->nom }}</strong>.</p>
    @else
        <p>Vous avez été retiré de la chambre numéro <strong>{{ $chambre->numero }}</strong> dans le pavillon <strong>{{ $chambre->pavillon->nom }}</strong>.</p>
    @endif

    <p>Cordialement,</p>
    <p>L'équipe de gestion des chambres</p>
</body>
</html>
