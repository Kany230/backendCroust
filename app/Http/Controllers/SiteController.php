<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    //Afficher tous les sites
    public function index(){
         $sites = Site::with('locals')
            ->orderBy('created_at', 'desc')
            ->get();

         return  response()->json([ 
            'sites' => $sites
         ], 200);
    }

    //Afficher un site par son id
    public function show($id){
        $site = Site::find($id);
        if(!$site){
            return response()->json([
                'message' => 'Site introuvable'
            ], 404);
        }

        return response()->json([
            'site' => $site
        ], 200);    
}

    //Créer un site
    public function store(Request $request){
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'superficie' => 'required|numeric',
            'dateConstruction' => 'required|date',
            'localisation_lat' => 'required|numeric',
            'localisation_lng' => 'required|numeric',
        ]);

        $site = Site::create($data);

        return response()->json([
            'message' => 'Site est crée avec succès',
            'site' => $site
        ], 201);
    }

    //Supprimer un site

    public function destroy($id){
        $site = Site::find($id);
        if(!$site){
            return response()->json([
                'message' => 'Site introuvable'
            ], 404);
        }

        $site->delete();

        return response()->json([
            'message' => 'Site supprimé avec succès'
        ], 200);
    }
} 
