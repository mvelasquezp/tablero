<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use App\User as User;

class Control extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        //
    }

    public function ls_hitos_control() {
        extract(Request::input());
        if(isset($catalogo)) {
            $usuario = Auth::user();
            $hitos = DB::table("pr_catalogo_hitos as pch")
                ->join("ma_hitos_control as mhc", function($join) {
                    $join->on("pch.id_empresa", "=", "mhc.id_empresa")
                        ->on("pch.id_hito", "=", "mhc.id_hito");
                })
                ->select(
                    "pch.id_hito as id",
                    "pch.nu_orden as orden",
                    "mhc.des_hito as hito",
                    "pch.nu_peso as peso"
                )
                ->where("pch.id_empresa", $usuario->id_empresa)
                ->where("pch.id_catalogo", $catalogo)
                ->where("pch.st_vigente", "Vigente")
                ->where("mhc.st_vigente", "Vigente")
                ->orderBy("pch.nu_orden", "asc")
                ->get();
            $responsables = DB::table("ma_puesto")
                ->select("id_puesto as value", "des_puesto as text")
                ->where("st_vigente", "Vigente")
                ->orderBy("des_puesto", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "hitos" => $hitos,
                    "responsables" => $responsables
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_proyecto() {
        extract(Request::input());
        if(isset($tpcateg, $tporden, $expediente, $inicio, $organo, $direccion, $area, $descripcion, $fin, $contratista, $valor, $armadas, $hitos)) {
            $usuario = Auth::user();
            $ndias = round((strtotime($fin) - strtotime($inicio)) / (24 * 3600));
            $id = DB::table("pr_proyecto")->insertGetId([
                "id_empresa" => $usuario->id_empresa,
                "id_catalogo" => $tpcateg,
                "tp_orden" => $tporden,
                "des_expediente" => $expediente,
                "fe_inicio" => $inicio,
                "id_organo" => $organo,
                "id_direccion" => $direccion,
                "id_area" => $area,
                "des_proyecto" => $descripcion,
                "fe_fin" => $fin,
                "des_contratista" => $contratista,
                "num_valor" => $valor,
                "num_armadas" => $armadas,
                "num_dias" => $ndias, //calcular
            ]);
            //inserta los hitos
            foreach($hitos as $idx => $hito) {
                extract($hito);
                DB::table("pr_proyecto_hitos")->insert([
                    "id_detalle" => ($idx+ 1),
                    "id_proyecto" => $id,
                    "id_hito" => $hid,
                    "id_empresa" => $usuario->id_empresa,
                    "id_catalogo" => $tpcateg,
                    "id_estado_proceso" => 1,
                    "id_estado_documentacion" => 2,
                    "id_responsable" => $responsable,
                    "fe_inicio" => $inicio,
                    "nu_dias" => $ndias,
                    "fe_fin" => $fin,
                ]);
            }
            //inserta los campos
            $campos = DB::table("pr_proyecto_hitos as pph")
                ->join("ma_campos as mc", "mc.st_obligatorio", "=", DB::raw("'S'"))
                ->select(
                    "pph.id_detalle as detalle",
                    "pph.id_proyecto as proyecto",
                    "pph.id_hito as hito",
                    "pph.id_empresa as empresa",
                    "pph.id_catalogo as catalogo",
                    "mc.id_campo as campo"
                )
                ->where("pph.id_proyecto", $id);
            $hitoscampo = DB::table("pr_proyecto_hitos as pph")
                ->join("pr_hitos_campo as phc", function($join) {
                    $join->on("pph.id_hito", "=", "phc.id_hito")
                        ->on("pph.id_empresa", "=", "phc.id_empresa");
                })
                ->select(
                    "pph.id_detalle as detalle",
                    "pph.id_proyecto as proyecto",
                    "pph.id_hito as hito",
                    "pph.id_empresa as empresa",
                    "pph.id_catalogo as catalogo",
                    "phc.id_campo as campo"
                )
                ->where("pph.id_proyecto", $id)
                ->union($campos)
                ->get();
            $arr_pphc = [];
            foreach($hitoscampo as $hc) {
                $arr_pphc[] = [
                    "id_detalle" => $hc->detalle,
                    "id_proyecto" => $hc->proyecto,
                    "id_hito" => $hc->hito,
                    "id_empresa" => $hc->empresa,
                    "id_catalogo" => $hc->catalogo,
                    "id_campo" => $hc->campo
                ];
            }
            DB::table("pr_proyecto_hitos_campos")->insert($arr_pphc);
            return Response::json([
                "state" => "success"
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

}