<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use App\Models\Abelha;
use App\Models\Planta;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class MainController extends BaseController
{
    public function index(Request $request){
        $abelhaFilter = $request->input('abelhas') ?? "";
        $mesesFilter = $request->input('meses_floracao') ?? "";

        if(!empty($abelhaFilter)){
            $plantas = Planta::whereHas('abelhas',
                function (Builder $query) use ($abelhaFilter){
                    $query->whereIn('id', $abelhaFilter);
                })->where(function(Builder $query) use ($mesesFilter){
                    foreach($mesesFilter as $mes){
                        $query->orWhere("meses_florada", "like", "%{$mes}%");
                    }
                })->paginate(10)->withQueryString();
        }else if(!empty($mesesFilter)){
            $plantas = Planta::where(function($query) use ($mesesFilter){
                foreach($mesesFilter as $mes){
                    $query->orWhere("meses_florada", "like", "%{$mes}%");
                }
            })->paginate(10)->withQueryString();
        }else{
            $plantas = Planta::paginate(10);

        }


        $abelhas = Abelha::all();



        $json = [
            "abelhas" => $abelhas,
            "plantas" => $plantas
        ];

        return response()->json($json);
    }


    public function novaAbelha(Request $request){
        $data = filter_var_array($request->all(), FILTER_SANITIZE_STRIPPED);

        if(!isset($data['nome']) || !isset($data['especie'])){
            $json = [
                "message" => "Todos os valores obrigatórios e devem ser preenchidos!"
            ];

            return response()->json($json, 400);
        }

        $abelha = Abelha::create($data);

        if($abelha){
            $json = ["message" => "Abelha adicionada"];
            return response()->json($json, 500);
        }

        $json = ["message" => "Erro ao adicionar abelha"];
        return response()->json($json, 500);
    }

    public function editarAbelha(Request $request, $id){
        $data = filter_var_array($request->all(), FILTER_SANITIZE_STRIPPED);

        $abelha = Abelha::find($id);

        if(!$abelha){
            $json = ["message" => "Abelha não encontrada"];
            return response()->json($json, 400);
        }
        if($abelha->update($data)){
            $json = ["message" => "Abelha editada com sucesso"];

            return response()->json($json);
        }

        $json = ["message" => "Erro ao editar abelha"];
        return response()->json($json, 500);
    }

    public function deletarAbelha( $id){


        $abelha = Abelha::find($id);

        if(!$abelha){
            $json = ["message" => "Abelha não encontrada"];
            return response()->json($json, 400);
        }
        $abelha->plantas()->detach();

        if(!$abelha->delete()){
            $json = ["message" => "Erro ao deletar abelha"];
            return response()->json($json, 500);
        }

        $json = ["message" => "Abelha excluída!"];
        return response()->json($json);

    }

    public function novaPlanta(Request $request){
        if(!$request->filled('nome') || !$request->filled('especie')
            || !$request->filled('meses_florada') || !$request->filled('abelhas')
        ){
            $json = [
                "message" => "É necessário informar nome, espécie, meses de floração e abelhas polinizadoras para cadrastrar uma planta, verifique os dados",
            ];

            return response()->json($json);
        }

        $abelhas = $request->input('abelhas');

        $data = $request->except(['abelhas']);
        $data['meses_florada'] = implode(",", $data['meses_florada']);
        $planta = Planta::create($data);

        if($planta){
            $planta->abelhas()->attach($abelhas);

            if($planta->abelhas){
                return redirect()->route('home');
            }
            $planta->delete();
            $json = [
                "message" => "Erro ao cadastrar a planta",
            ];

            return response()->json($json, 500);
        }

        $json = [
            "message" => "Erro ao cadastrar a planta",
        ];

        return response()->json($json, 500);
    }

    public function editarPlanta(Request $request, $id){
        $abelhas = $request->input('abelhas');

        $data = $request->except(['abelhas']);
        $data['meses_florada'] = implode(",", $data['meses_florada']);
        $planta = Planta::find($id);

        if(!$planta){
            $json = [
                "message" => "Planta não encontrada",
            ];

            return response()->json($json, 500);
        }

        $planta->abelhas()->detach();

        if($planta->update($data)){
            $planta->abelhas()->attach($abelhas);

            $json = [
                "message" => "Planta atualizada!"
            ];
            return response()->json($json);
        }

        $json = [
            "message" => "Erro ao atualizar a planta",
        ];

        return response()->json($json, 500);
    }

    public function deletarPlanta( $id){


        $planta = Planta::find($id);

        if(!$planta){
            $json = ["message" => "Planta não encontrada"];
            return response()->json($json, 400);
        }
        $planta->abelhas()->detach();

        if(!$planta->delete()){
            $json = ["message" => "Erro ao deletar planta"];
            return response()->json($json, 500);
        }

        $json = ["message" => "Planta excluída!"];
        return response()->json($json);

    }
}
