<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    // Afficher toutes les réclamations
    public function index(){

        $reclamations = Reclamation::with(['local', 'user', 'maintenance'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'reclamations' => $reclamations
        ], 200);
    }

    // Afficher une réclamation par son ID
    public function show($id)
    {
        $reclamation = Reclamation::with(['local', 'user', 'maintenance'])
            ->find($id);

        if (!$reclamation) {
            return response()->json([
                'message' => 'Réclamation introuvable'
            ], 404);
        }

        return response()->json([
            'reclamation' => $reclamation
        ], 200);
    }

    //Faire une réclamation
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_local' => 'required|exists:locals,id',
            'id_user' => 'required|exists:users,id',
            'objet' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        $data['statut'] = 'En attente'; // Statut par défaut

        $reclamation = Reclamation::create($data);

        return response()->json([
            'message' => 'Réclamation créée avec succès',
            'reclamation' => $reclamation
        ], 201);
    }

    // Mettre à jour une réclamation
    public function update(Request $request, $id)
    {
        $reclamation = Reclamation::find($id);

        if (!$reclamation) {
            return response()->json([
                'message' => 'Réclamation introuvable'
            ], 404);
        }

        $data = $request->validate([
            'objet' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'statut' => 'sometimes|nullable|string|max:50'
        ]);

        $reclamation->update($data);

        return response()->json([
            'message' => 'Réclamation mise à jour avec succès',
            'reclamation' => $reclamation
        ], 200);
    }
    // Supprimer une réclamation
    public function destroy($id)
    {
        $reclamation = Reclamation::find($id);

        if (!$reclamation) {
            return response()->json([
                'message' => 'Réclamation introuvable'
            ], 404);
        }

        $reclamation->delete();

        return response()->json([
            'message' => 'Réclamation supprimée avec succès'
        ], 200);
    }
    // Afficher les réclamations d'un utilisateur
    public function userReclamations($userId)
    {
        $reclamations = Reclamation::where('id_user', $userId)
            ->with(['local', 'maintenance'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($reclamations->isEmpty()) {
            return response()->json([
                'message' => 'Aucune réclamation trouvée pour cet utilisateur'
            ], 404);
        }

        return response()->json([
            'reclamations' => $reclamations
        ], 200);
    }
    // Afficher les réclamations d'un local
    public function localReclamations($localId)
    {
        $reclamations = Reclamation::where('id_local', $localId)
            ->with(['user', 'maintenance'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($reclamations->isEmpty()) {
            return response()->json([
                'message' => 'Aucune réclamation trouvée pour ce local'
            ], 404);
        }

        return response()->json([
            'reclamations' => $reclamations
        ], 200);
    }
    // Afficher les réclamations en attente de maintenance
    public function pendingMaintenanceReclamations()
    {
        $reclamations = Reclamation::whereNull('id_maintenance')
            ->with(['local', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($reclamations->isEmpty()) {
            return response()->json([
                'message' => 'Aucune réclamation en attente de maintenance'
            ], 404);
        }

        return response()->json([
            'reclamations' => $reclamations
        ], 200);
    }
}
