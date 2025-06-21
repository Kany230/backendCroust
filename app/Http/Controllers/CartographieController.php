<?php

namespace App\Http\Controllers;

use App\Models\Cartographie;
use App\Models\Local;
use App\Models\Site;


class CartographieController extends Controller
{
    public function index(){
        //Recuperer les sites avec leurs locaux
        $sites = Site::with(['locals', 'cartographie'])->get();

        return response()->json($sites);
    }

    //Retourne tous les elements pour leaflet
    //Leaflet est une bibliothèque JavaScript open source très populaire pour créer des cartes interactives sur le web
    public function getDonneeCartographie(){
        
        $cartographies = Cartographie::with(['sites', 'local'])->get()->map(function($item){
            return [
                'id' => $item->id,
                'latitude' => $item->coordonnees_y,
                'longitude' => $item->coordonnees_x,
                'type' => $item->type,
                'label' => $item->label,
                'details' => $item->details,
                'nomSite' => $item->sites->nom ?? '',
                'nomLocal' => $item->local->nom ?? '',
                'style' => [
                    'couleur' => $item->couleur,
                    'largeur' => $item->largeur,
                    'hauteur' => $item->hauteur,
                    'rotation' => $item->rotation
                ]
            ];
        });

        return response()->json($cartographies);
    }

    //Recupere les donnees d'un site
    public function getDonneeSite($id_site){
        $site = Site::with(['locals', 'cartographie'])->findOrFail($id_site);

        $cartographies = $site->cartographie()->with('local')->get()->map(function($item){
            return [
                'id' => $item->id,
                'latitude' => $item->coordonnees_y,
                'longitude' => $item->coordonnees_x,
                'type' => $item->type,
                'label' => $item->label,
                'details' => $item->details,
                'nomLocal' => $item->local->nom ?? '',
                'style' => [
                    'couleur' => $item->couleur,
                    'largeur' => $item->largeur,
                    'hauteur' => $item->hauteur,
                    'rotation' => $item->rotation
                ]
            ];
        });

        return response()->json([
            'site' => [
                'id' => $site->id,
                'name' => $site->name,
                'description' => $site->description,
                'center' => [
                    'latitude' => $site->latitude,
                    'longitude' => $site->longitude
                ]
                ],
                'cartographies'=> $cartographies
            ]);
    }


    //La liste des locaux d'un site
    public function getDonneeLocal($id_site){
        $locals = Local::where('id_site', $id_site)
        ->with('cartographie')
        ->get()
        ->map(function($item){
            return [
                'id' => $item->id,
                'nom' => $item->nom,
                'type' => $item->type,
                'capacite' => $item->capacite,
                'description' => $item->description
            ];
        });

        return response()->json($locals);
    }


    //Details d'un elements
    public function getDetailsElements($id){

        $cartographie = Cartographie::with(['sites', 'local'])->findOrFail($id);

        return response()->json([
           'id' => $cartographie->id,
            'latitude' => $cartographie->coordonnees_y,
            'longitude' => $cartographie->coordonnees_x,
            'type' => $cartographie->type,
            'label' => $cartographie->label,
            'details' => $cartographie->details,
            'style' => [
                'couleur' => $cartographie->couleur,
                'largeur' => $cartographie->largeur,
                'hauteur' => $cartographie->hauteur,
                'rotation' => $cartographie->rotation
            ],
            'site' => [
                'id' => $cartographie->sites->id,
                'nomSite' => $cartographie->sites->nom
            ],
            'local' => [
                'id' => $cartographie->local->id,
                'nomLocal' => $cartographie->local->nom,
                'type' => $cartographie->sites->type,
            ] 
        ]);
    }

    //Voir un site
    public function show($id_site) {
        $site = Site::with('locals', 'cartographie')->findOrFail($id_site);
        return response()->json($site);
    }


}
