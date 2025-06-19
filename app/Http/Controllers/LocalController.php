<?php

namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\Http\Request;


class LocalController extends Controller
{
    //Listes de tous les locaux
    public function index()
    {
        // Récupérer tous les locaux
        $locals = Local::all();

        // Retourner la réponse JSON
        return response()->json($locals);
    }

    //Créer un nouveau local
    public function store(Request $request)
    {
        // Valider les données de la requête
        $data = $request->validate([
            "id_site"=> "integer|exists:sites,id",
            'nom' => 'required|string|max:255',
            'superficie' => 'required|numeric',
            'capacite' => 'nullable|integer',
            'disponible' => 'nullable|boolean',
            'statutConforme' => 'nullable|boolean',
            'type' => 'nullable|string|max:255',
        ]);

        // Créer un nouveau local
        $local = Local::create($data);

        // Retourner la réponse JSON
        return response()->json(['message'=> 'Local crée avec succès', 'data' => $local , ], 201);
    }

    //Afficher un local spécifique
    public function show($id)
    {
        // Trouver le local par son ID
        $local = Local::findOrFail($id);
        if (!$local) {
            return response()->json(['message' => 'Local non trouvé'], 404);
        }

        // Retourner la réponse JSON
        return response()->json($local);
    }

    //Mettre à jour un local spécifique
    public function update(Request $request, $id)
    {
        // Trouver le local par son ID
        $local = Local::findOrFail($id);
        if (!$local) {
            return response()->json(['message' => 'Local non trouvé'], 404);
        }

        // Valider les données de la requête
        $data = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'superficie' => 'sometimes|required|numeric',
            'capacite' => 'sometimes|nullable|integer',
            'disponible' => 'sometimes|nullable|boolean',
            'statutConforme' => 'sometimes|nullable|boolean',
            'type' => 'sometimes|nullable|string|max:255',
            "id_site"=> "sometimes|integer|exists:sites,id",
        ]);

        // Mettre à jour le local
        $local->update($data);

        // Retourner la réponse JSON
        return response()->json(['message'=> 'Local mis à jour avec succès', 'data' => $local]);
    }

    //Supprimer un local spécifique
    public function destroy($id)
    {
        // Trouver le local par son ID
        $local = Local::findOrFail($id);
        if (!$local) {
            return response()->json(['message' => 'Local non trouvé'], 404);
        }

        // Supprimer le local
        $local->delete();

        // Retourner la réponse JSON
        return response()->json(['message' => 'Local supprimé avec succès']);
    }
}
