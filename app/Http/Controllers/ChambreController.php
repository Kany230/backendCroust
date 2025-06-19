<?php

namespace App\Http\Controllers;

use App\Mail\AffectationChambreMail;
use App\Models\Chambre;
use App\Models\Local;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ChambreController extends Controller
{
    public function index(){
        $chambres = Chambre::with(['pavillon', 'users', 'equipements'])->get();
        
        return response()->json($chambres);
    }

    //Voir une chambre
    public function show($id){

        $chambre = Chambre::with(['pavillon', 'equipements', 'users'])->find($id);

        $chambre->occupation = [
            'places_occupees' => $chambre->users->count(),
            'places_libres' => $chambre->capacite - $chambre->users->count(),
            'taux_occupation' => $chambre->capacite > 0 ? round(($chambre->users->count() / $chambre->capacite) * 100, 2) : 0 
        ];
        return response()->json($chambre);
    }

    public function store(Request $request){
        $data = $request->validate([
            'id_pavillon' => 'required|exists:locals,id',
            'nom' => 'required|string',
            'numero' => 'required|string',
            'superficie' => 'required|numeric',
            'capacite' => 'nullable|integer',
            'statut' => 'required|in:libre,occupe,en maintenance'
        ]);

        $pavillon = Local::where('id', $data['id_pavillon'])
                        ->where('type', 'pavillon')
                        ->first();
        
        if(!$pavillon){
            return response()->json([
                'message' => 'Id de n\'est pas un pavillon'
            ], 422);
        }

        $chambrePre = Chambre::where('id_pavillon', $data['id_pavillon'])
                             ->where('numero', $data['numero'])
                             ->first();
         if($chambrePre){
            return response()->json([
                'message' => 'Le numero de chambre existe deja dans le pavillon'
            ], 422);
        }

        $chambre = Chambre::create($data);
        $chambre->load(['pavillon', 'users']);

        return response()->json($chambre);
    }

    public function update(Request $request, int $id){
        $chambre = Chambre::findOrFail($id);

        $data = $request->validate([
            'id_pavillon' => 'sometimes|exists:locals,id',
            'nom' => 'nullable|string',
            'numero' => 'sometimes|string',
            'superficie' => 'sometimes|numeric',
            'capacite' => 'nullable|integer',
            'statut' => 'sometimes|in:libre,occupe,en maintenance'
        ]);
        
        if(isset($data['id_pavillon'])){
           $pavillon = Local::where('id', $data['id_pavillon'])
                        ->where('type', 'pavillon')
                        ->first();
            if(!$pavillon){
                return response()->json([
                    'message' => 'Id de n\'est pas un pavillon'
                ], 422);
            }
        }

        if(isset($data['numero'])&& isset($data['id_pavillon'])){

            $chambrePre = Chambre::where('id_pavillon', $data['id_pavillon'])
                             ->where('numero', $data['numero'])
                             ->where('id', '!=', $id)
                             ->first();

            if($chambrePre){
                return response()->json([
                    'message' => 'Le numero de chambre existe deja dans le pavillon'
                ], 422);
            }
        }

        if(isset($data['capacite'])){
            $nombreUsers = $chambre->users->count();
            if($data['capacite'] < $nombreUsers){
                return response()->json([
                    'message' => 'la capcite ne peut pas etre inferieur au nombre d\'utilisateurs actuels'
                ], 422);
            }
        }

        $chambre->update($data);
        $chambre->load(['pavillon', 'users']);

        return response()->json($chambre);
        
    }

    public function destroy($id){
        $chambre = Chambre::findOrFail($id);

        if($chambre->users()->count() > 0){
            return response()->json([
                'message' => 'Impossible de supprimer une chambre qui a deja des utilisateurs'
            ], 422);
        }

        if($chambre->equipements()->count() > 0){
            return response()->json([
                'message' => 'Impossible de supprimer une chambre qui a deja des equipements'
            ], 422);
        }

        $chambre->delete();

        return response()->json([
            'message' => 'Chambre supprimee'
        ]);
    }

    public function getParPavillon($id){
        $pavillon = Local::where('id', $id)
                          ->where('type', 'pavillon')
                          ->first();

        if(!$pavillon){
            return response()->json([
                'message' => 'Pavillon non trouve'
            ], 404);
        }

        $chambres = Chambre::where('id_pavillon', $id)
                            ->with(['equipements', 'users'])
                            ->orderBy('numero')
                            ->get();
        
        return response()->json([
            'pavillon' => $pavillon,
            'chambres' => $chambres,
            'total_chambres' => $chambres->count(),
            'capacite_total' => $chambres->sum('capacite'),
            'total_users' => $chambres->sum(function ($chambre){
                return $chambre->users->count();
            }),
            'places_libres' => $chambres->sum('capacite') - $chambres->sum(function ($chambre){
                return $chambre->users->count();
            })
        ]);
    }

    public function getDisponible(){
        $chambres = Chambre::disponibles()
                            ->with(['pavillon', 'users'])
                            ->orderBy('id_pavillon')
                            ->orderBy('numero')
                            ->get()
                            ->map(function($chambre){
                                $chambre->places_libres = $chambre->capacite - $chambre->users->count();
                                return $chambre;
                            });

        return response()->json($chambres);
    }
    

    public function dupliquerChambre($id){
        $chambreOri = Chambre::findOrFail($id);

        $lastNumero = Chambre::where('id_pavillon', $chambreOri->id_pavillon)
                             ->max('numero');
        
        $newNumero = str_pad((intval($lastNumero) + 1), 3, '0', STR_PAD_LEFT);

        $newChambre = Chambre::create([
            'id_pavillon' => $chambreOri->id_pavillon,
            'numero' => $newNumero,
            'nom' => 'Chambre ' . $newNumero,
            'capacite' => $chambreOri->capacite,
            'superficie' => $chambreOri->superficie,
            'statut' => $chambreOri->statut
        ]);

        $newChambre->load(['pavillon', 'users']);

        return response()->json($newChambre);
    }


    public function assignerUser(Request $request, int $IdChambre){
        $chambre = Chambre::with('users')->findOrFail($IdChambre);

        $data = $request->validate([
            'id_user' => 'required|exists:users,id'
        ]);

        if($chambre->users->count() >= $chambre->capacite){
            return response()->json([
                'message' => 'Chambre est pleine'
            ], 422);
        }

        if($chambre->users->contains($data['id_user'])){
            return response()->json([
                'message' => 'Cet etudiant est deja assigner a cette chambre'
            ], 422);
        }

        $chambre->users()->attach($data['id_user']);

        $user = User::find($data['id_user']);
        if($user->role !== 'etudiant'){
            return response()->json([
                'message' => 'Seuls les etudiants peuvent etre assignes aux chambres'
            ], 422);
        }

        if($user && $user->email){
            Mail::to($user->email)->send(new AffectationChambreMail($user, $chambre, 'assignation'));
        }

        $chambre->load(['users', 'pavillon']);

        return response()->json([
            'message' => 'Etudiant assigne',
            'chambre' => $chambre
        ]);
    }

    public function retirerUser(int $idChambre, int $idUser){
        $chambre = Chambre::with('users')->findOrFail($idChambre);

        if(!$chambre->users->contains($idUser)){
            return response()->json([
                'message' => 'Cet etudiant n\'est pas assigner a cet chambre'
            ], 422);
        }

        $chambre->users()->detach($idUser);

        $user = User::find($idUser);
        
        if($user && $user->email){
            Mail::to($user->email)->send(new AffectationChambreMail($user, $chambre, 'retrait'));
        }
        $chambre->load(['users', 'pavillon']);

        return response()->json([
            'message' => 'Etudiant retire',
            'chambre' => $chambre
        ]);
    }
    
}
