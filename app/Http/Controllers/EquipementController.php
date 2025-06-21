<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\Equipement;
use App\Models\Local;
use Illuminate\Http\Request;

class EquipementController extends Controller
{
    public function index(){
        $equipement = Equipement::with(['local'])->get();
        return response()->json($equipement);
    }

    public function show($id){
        $equipement = Equipement::with(['local'])->findOrFail($id);

        return response()->json($equipement);
    }

    public function store(Request $request){
        $data = $request->validate([
            'id_local' => 'required|exists:locals,id',
            'nom' => 'required|string|max:255',
            'type' => 'required|in:mobilier, electromenager,informatique,chauffage,plomberie,electricite,autre',
            'etat' => 'required|in:neuf,bon,use,hors service'
        ]);

        $equipement = Equipement::create($data);
        $equipement->load('local');

        return response()->json($equipement);
    }

    public function update(Request $request, int $id){
        $equipement = Equipement::findOrFail($id);

        $data = $request->validate([
            'id_local' => 'sometimes|exists: locals,id',
            'nom' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:mobilier, electromenager,informatique,chauffage,plomberie,electricite,autre',
            'etat' => 'sometimes|in:neuf,bon,use,hors service'
        ]);

        $equipement->update($data);
        $equipement->load('local');

        return response()->json($equipement);
    }

    public function destroy($id){
        $equipement = Equipement::findOrFail($id);

        $equipement->delete();

        return response()->json([
            'message' => 'Equipement supprime'
        ]);
    }

    public function getStatistique(){
        $equipements = [
            'total' => Equipement::count(),
            'parPavillon' => Equipement::whereHas('local', function($query){
                $query->where('type', 'pavillon');
            })->count(),
            'parCantine' => Equipement::whereHas('local', function($query){
                $query->where('type', 'cantine');
            })->count(),
        ];

        return response()->json($equipements);
    }

    public function getParCantine(){

        $equipements = Equipement::whereHas('local', function($query){
            $query->where('type', 'cantine');
        })->with(['local'])->get();

        return response()->json($equipements);
    }

    public function getParPavillon($id){
        
        $local = Local::where('id', $id)->where('type', 'pavillon')->first();

        $equipementPavillon = Equipement::where('id_local', $id)->get();

        $equipementChambre = Equipement::whereHas('local', function($query)use ($id){
            $query->whereIn('id', function($Query) use ($id){
                $Query->select('id_local')
                      ->from('chambres')
                      ->where('id_pavillon', $id);
            });
        })->with(['local'])->get();

        $equipements = $equipementPavillon->merge($equipementChambre);

        return response()->json([
            'pavillon' => $local,
            'equipements' => $equipements
        ]);
    }

    public function getParChambre($id){

        $chambre = Chambre::with(['local', 'pavillon'])->findOrFail($id);

        $equipements = Equipement::where('id_local', $chambre->id_local)
                                  ->with(['local'])
                                  ->get();
    
        return response()->json([
            'chambre' => $chambre,
            'equipements' => $equipements
        ]);
    }

}
