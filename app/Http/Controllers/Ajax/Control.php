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
        if(isset($tpcateg, $tporden, $expediente, $inicio, $organo, $direccion, $area, $descripcion, $ndias, $contratista, $valor, $armadas, $hitos)) {
            $usuario = Auth::user();
            $fin = date("Y-m-d", strtotime($inicio . " + " . $ndias . " days"));
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
            $count = 0;
            $pagos_id = DB::table("ma_hitos_control")->where("des_hito","Pago")->select("id_hito")->first();
            $pagos_id = $pagos_id->id_hito;
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
                if($hid == $pagos_id) $count++;
                //id_detalle, id_proyecto, id_empresa, id_hito, id_catalogo
            }
            //
            if($count > 0) {
                $hitospago = DB::table("pr_proyecto_hitos")
                    ->where("id_proyecto", $id)
                    ->where("id_hito", $pagos_id)
                    ->select("id_detalle")
                    ->get();
                foreach($hitospago as $i => $row) {
                    DB::table("pr_proyecto_hitos")
                        ->where("id_detalle", $row->id_detalle)
                        ->where("id_proyecto", $id)
                        ->where("id_hito", $pagos_id)
                        ->update(["des_hito" => "Pago " . ($i + 1)]);
                }
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

    function ls_hitos_proyecto() {
        extract(Request::input());
        if(isset($proyecto)) {
            $usuario = Auth::user();
            $hitos = DB::table("pr_proyecto_hitos as pph")
                ->join("pr_catalogo_hitos as pch", function($join) {
                    $join->on("pph.id_empresa", "=", "pch.id_empresa")
                        ->on("pph.id_catalogo", "=", "pch.id_catalogo")
                        ->on("pph.id_hito", "=", "pch.id_hito");
                })
                ->join("sys_estados as sed", "pph.id_estado_documentacion", "=", "sed.id_estado")
                ->join("sys_estados as sep", "pph.id_estado_proceso", "=", "sep.id_estado")
                ->join("ma_hitos_control as mhc", function($join) {
                    $join->on("pph.id_hito", "=", "mhc.id_hito")
                        ->on("pph.id_empresa", "=", "mhc.id_empresa");
                })
                ->join("ma_puesto as mp", function($join) {
                    $join->on("pph.id_responsable", "=", "mp.id_puesto")
                        ->on("pph.id_empresa", "=", "mp.id_empresa");
                })
                ->join("pr_valoracion as pv", function($join) {
                    $join->on("pph.id_estado_proceso", "=", "pv.id_estado_p")
                        ->on("pph.id_estado_documentacion", "=", "pv.id_estado_c");
                })
                ->leftJoin("us_usuario_puesto as uup", function($join) {
                    $join->on("mp.id_puesto", "=", "uup.id_puesto")
                        ->on("mp.id_empresa", "=", "uup.id_empresa")
                        ->on("uup.st_vigente", "=", DB::raw("'Vigente'"));
                })
                ->leftJoin("ma_usuarios as mu", function($join) {
                    $join->on("uup.id_usuario", "=", "mu.id_usuario")
                        ->on("uup.id_empresa", "=", "mu.id_empresa")
                        ->on("mu.st_vigente", "=", DB::raw("'Vigente'"));
                })
                ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
                ->select(
                    "pph.id_proyecto as pid",
                    "pph.id_detalle as id",
                    "pph.id_hito as hid",
                    DB::raw("ifnull(pph.des_hito, mhc.des_hito) as hito"),
                    DB::raw("100 * pv.num_puntaje as avance"),
                    DB::raw("date_format(pph.fe_inicio,'%Y-%m-%d') as inicio"),
                    DB::raw("date_format(pph.fe_fin,'%Y-%m-%d') as fin"),
                    DB::raw("if(datediff(current_timestamp,pph.fe_fin) < 0,0,datediff(current_timestamp,pph.fe_fin)) as diasvcto"),
                    "mp.des_puesto as responsable",
                    DB::raw("ifnull(concat(me.des_nombre_1,' ',des_nombre_2,' ',des_nombre_3),'(sin asignar)') as nombre"),
                    "sed.des_estado as edocumentacion",
                    "sep.des_estado as eproceso",
                    "pph.des_observaciones as observaciones"
                )
                ->where("pph.id_proyecto", $proyecto)
                ->where("pph.id_empresa", $usuario->id_empresa)
                ->orderBy("pph.id_detalle", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "hitos" => $hitos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ls_estado_hito() {
        extract(Request::input());
        if(isset($proyecto, $hito)) {
            $usuario = Auth::user();
            $estado = DB::table("pr_proyecto_hitos")
                ->select(
                    DB::raw("date_format(fe_inicio,'%Y-%m-%d') as inicio"),
                    DB::raw("date_format(fe_fin,'%Y-%m-%d') as fin"),
                    "id_estado_documentacion as edocumentacion",
                    "id_estado_proceso as eproceso",
                    "des_observaciones as observaciones"
                )
                ->where("id_empresa", $usuario->id_empresa)
                ->where("id_proyecto", $proyecto)
                ->where("id_hito", $hito)
                ->first();
            return Response::json([
                "state" => "success",
                "data" => [
                    "estado" => $estado
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function upd_estado_hito() {
        extract(Request::input());
        if(isset($hito, $proyecto, $detalle, $inicio, $fin, $documentacion, $proceso, $observaciones)) {
            $usuario = Auth::user();
            DB::table("pr_proyecto_hitos")
                ->where("id_detalle", $detalle)
                ->where("id_proyecto", $proyecto)
                ->where("id_hito", $hito)
                ->where("id_empresa", $usuario->id_empresa)
                ->update([
                    "fe_inicio" => $inicio,
                    "fe_fin" => $fin,
                    "id_estado_documentacion" => $documentacion,
                    "id_estado_proceso" => $proceso,
                    "des_observaciones" => $observaciones
                ]);
            $proyectos = DB::table("pr_proyecto as pp")
                ->join("pr_catalogo_proyecto as pcp", "pp.id_catalogo", "=", "pcp.id_catalogo")
                ->join("ma_area_usuaria as mau", function($join) {
                    $join->on("pp.id_area", "=", "mau.id_area")
                        ->on("pp.id_direccion", "=", "mau.id_direccion")
                        ->on("pp.id_organo", "=", "mau.id_organo")
                        ->on("pp.id_empresa", "=", "mau.id_empresa");
                })
                ->select(
                    "pp.id_proyecto as id",
                    "pcp.des_catalogo as tipo",
                    DB::raw("if(pp.tp_orden = 'C','Compras','Servicios') as orden"),
                    "pp.des_expediente as expediente",
                    DB::raw("date_format(pp.created_at,'%Y-%m-%d') as femision"),
                    "mau.des_area as areausr",
                    "pp.des_proyecto as proyecto",
                    DB::raw("date_format(pp.fe_fin,'%Y-%m-%d') as fentrega"),
                    "pp.num_valor as valor",
                    "pp.num_armadas as armadas",
                    DB::raw("if(datediff(current_timestamp,pp.fe_fin) < 0,0,datediff(current_timestamp,pp.fe_fin)) as diasvence"),
                    "pp.des_observaciones as observaciones"
                )
                ->where("pp.id_empresa", $usuario->id_empresa)
                ->orderBy("pp.id_proyecto", "asc")
                ->get();
            //busca los ultimos hitos por proyecto
            foreach($proyectos as $idx => $proyecto) {
                $detalle = DB::table("pr_proyecto_hitos as pph")
                    ->join("ma_hitos_control as mhc", function($join) {
                        $join->on("pph.id_hito", "=", "mhc.id_hito")
                            ->on("pph.id_empresa", "=", "mhc.id_empresa");
                    })
                    ->join("ma_puesto as mp", function($join) {
                        $join->on("pph.id_responsable", "=", "mp.id_puesto")
                            ->on("pph.id_empresa", "=", "mp.id_empresa");
                    })
                    ->leftJoin("us_usuario_puesto as uup", function($join) {
                        $join->on("mp.id_puesto", "=", "uup.id_puesto")
                            ->on("mp.id_empresa", "=", "uup.id_empresa")
                            ->on("uup.st_vigente", "=", DB::raw("'Vigente'"));
                    })
                    ->leftJoin("ma_usuarios as mu", function($join) {
                        $join->on("uup.id_usuario", "=", "mu.id_usuario")
                            ->on("uup.id_empresa", "=", "mu.id_empresa");
                    })
                    ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
                    ->select(
                        DB::raw("ifnull(pph.des_hito, mhc.des_hito) as hito"),
                        DB::raw("if(me.cod_entidad is null, mp.des_puesto, concat(me.des_nombre_1,' ',me.des_nombre_2,' ',me.des_nombre_3)) as responsable"),
                        "pph.des_observaciones as observaciones"
                    )
                    ->where("pph.id_estado_proceso", 3)
                    ->where("pph.id_proyecto", $proyecto->id)
                    ->where("pph.id_empresa", $usuario->id_empresa)
                    ->orderBy("pph.id_detalle", "desc")
                    ->get();
                if(count($detalle) > 0) {
                    $detalle = $detalle[0];
                    $proyectos[$idx]->estado = $detalle->hito;
                    $proyectos[$idx]->responsable = $detalle->responsable;
                    $proyectos[$idx]->hobservaciones = $detalle->observaciones;
                }
                else {
                    $proyectos[$idx]->estado = "";
                    $proyectos[$idx]->responsable = "";
                    $proyectos[$idx]->hobservaciones = "";
                }
            }
            //listo
            return Response::json([
                "state" => "success",
                "data" => [
                    "proyectos" => $proyectos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

}