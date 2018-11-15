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
            $responsables = DB::table("ma_puesto as mp")
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
                    "mp.id_puesto as value",
                    "uup.id_usuario as usuario",
                    DB::raw("if(uup.id_usuario is null,concat(mp.des_puesto, ' (sin asignar)'),concat(mp.des_puesto,' | ',me.des_nombre_1,' ',me.des_nombre_2,' ',me.des_nombre_3)) as text")
                )
                ->where("mp.st_vigente", "Vigente")
                ->orderBy("mp.des_puesto", "asc")
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
        if(isset($tpcateg, $tporden, $expediente, $inicio, $organo, $direccion, $area, $armadas, $hitos)) {
            //, $descripcion, $ndias, $contratista, $valor
            $user = Auth::user();
            $arrToInsert = [
                "id_empresa" => $user->id_empresa,
                "id_catalogo" => $tpcateg,
                "tp_orden" => $tporden,
                "des_expediente" => $expediente,
                "fe_inicio" => $inicio,
                "id_organo" => $organo,
                "id_direccion" => $direccion,
                "id_area" => $area,
                //"des_proyecto" => $descripcion,
                //"fe_fin" => $fin,
                //"des_contratista" => $contratista,
                //"num_valor" => $valor,
                "num_armadas" => $armadas,
                //"num_dias" => $ndias, //calcular
            ];
            if(isset($descripcion)) $arrToInsert["des_proyecto"] = $descripcion;
            if(isset($ndias)) {
                $arrToInsert["num_dias"] = $ndias;
                $arrToInsert["fe_fin"] = date("Y-m-d", strtotime($inicio . " + " . $ndias . " days"));
            }
            if(isset($contratista)) $arrToInsert["des_contratista"] = $contratista;
            if(isset($valor)) $arrToInsert["num_valor"] = $valor;
            $id = DB::table("pr_proyecto")->insertGetId($arrToInsert);
            //inserta los hitos
            $count = 0;
            $pagos_id = DB::table("ma_hitos_control")->where("des_hito","PAGOS")->select("id_hito")->first();
            $pagos_id = $pagos_id->id_hito;
            foreach($hitos as $idx => $hito) {
                extract($hito);
                $dataToInsert = [
                    "id_detalle" => ($idx+ 1),
                    "id_proyecto" => $id,
                    "id_hito" => $hid,
                    "id_empresa" => $user->id_empresa,
                    "id_catalogo" => $tpcateg,
                    "id_estado_proceso" => 1,
                    "id_estado_documentacion" => 2,
                    "id_responsable" => $responsable,
                    "fe_inicio" => $inicio,
                    "nu_dias" => $ndias,
                    "fe_fin" => $fin,
                ];
                if(isset($usuario)) $dataToInsert["id_responsable_usuario"] = $usuario;
                DB::table("pr_proyecto_hitos")->insert($dataToInsert);
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
                        ->on("pph.id_responsable_usuario", "=", "uup.id_usuario")
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
                    "pph.des_observaciones as observaciones",
                    DB::raw("if(pph.id_estado_proceso = 3,(if(datediff(current_timestamp, pph.fe_fin) > 0,'danger',if(datediff(pph.fe_fin, current_timestamp) < mhc.nu_dias_disparador,'success','warning'))),'secondary') as indicador")
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
        if(isset($proyecto, $hito, $id)) {
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
            $atributos = DB::table("pr_proyecto_hitos_campos as pphc")
                ->join("ma_campos as mc", function($join) {
                    $join->on("pphc.id_campo", "=", "mc.id_campo")
                        ->on("pphc.id_empresa", "=", "mc.id_empresa");
                })
                ->join("sys_tipos_dato as std", "mc.id_tipo", "=", "std.id_tipo")
                ->select(
                    "pphc.id_proyecto as proyecto",
                    "pphc.id_hito as hito",
                    "pphc.id_campo as campo",
                    "pphc.id_detalle as detalle",
                    "mc.des_campo as nombre",
                    "mc.id_tipo as tipo",
                    "pphc.des_valor as value"
                )
                ->where("pphc.id_proyecto", $proyecto)
                ->where("pphc.id_hito", $hito)
                ->where("pphc.id_detalle", $id)
                ->where("mc.st_obligatorio", "N")
                ->orderBy("pphc.id_hito", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "estado" => $estado,
                    "atributos" => $atributos
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
        if(isset($hito, $proyecto, $detalle, $fin, $documentacion, $proceso)) {
            $usuario = Auth::user();
            DB::table("pr_proyecto_hitos")
                ->where("id_detalle", $detalle)
                ->where("id_proyecto", $proyecto)
                ->where("id_hito", $hito)
                ->where("id_empresa", $usuario->id_empresa)
                ->update([
                    "fe_fin" => $fin,
                    "id_estado_documentacion" => $documentacion,
                    "id_estado_proceso" => $proceso,
                    "des_observaciones" => isset($observaciones) ? $observaciones : ""
                ]);
            //actualiza los atributos adicionales
            foreach($atributos as $atributo) {
                extract($atributo);
                DB::table("pr_proyecto_hitos_campos")
                    ->where("id_detalle", $adetalle)
                    ->where("id_proyecto", $aproyecto)
                    ->where("id_hito", $ahito)
                    ->where("id_empresa", $usuario->id_empresa)
                    ->where("id_campo", $acampo)
                    ->update([
                        "updated_at" => date("Y-m-d H:i:s"),
                        "des_valor" => $avalor
                    ]);
            }
            //carga la lista de proyectos
            $proyectos = DB::table("pr_proyecto as pp")
                ->join("pr_catalogo_proyecto as pcp", "pp.id_catalogo", "=", "pcp.id_catalogo")
                ->join("ma_area_usuaria as mau", function($join) {
                    $join->on("pp.id_area", "=", "mau.id_area")
                        ->on("pp.id_direccion", "=", "mau.id_direccion")
                        ->on("pp.id_organo", "=", "mau.id_organo")
                        ->on("pp.id_empresa", "=", "mau.id_empresa");
                })
                ->join("pr_proyecto_hitos as pph", function($join) {
                    $join->on("pp.id_proyecto", "=", "pph.id_proyecto")
                        ->on("pp.id_empresa", "=", "pph.id_empresa");
                })
                ->join("pr_catalogo_hitos as pch", function($join) {
                    $join->on("pph.id_empresa", "=", "pch.id_empresa")
                        ->on("pph.id_hito", "=", "pch.id_hito")
                        ->on("pph.id_catalogo", "=", "pch.id_catalogo");
                })
                ->join("pr_valoracion as pv", function($join) {
                    $join->on("pph.id_estado_proceso", "=", "pv.id_estado_p")
                        ->on("pph.id_estado_documentacion", "=", "pv.id_estado_c");
                })
                ->select(
                    "pp.id_proyecto as id",
                    "pp.id_catalogo as catalogo",
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
                    "pp.des_observaciones as observaciones",
                    DB::raw("100 * sum(pch.nu_peso * pv.num_puntaje)/sum(pch.nu_peso) as avance")
                )
                ->where("pp.id_empresa", $usuario->id_empresa)
                ->groupBy("id", "catalogo", "tipo", "orden", "expediente", "femision", "areausr", "proyecto", "fentrega", "valor", "armadas", "diasvence", "observaciones")
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
                        "pph.des_observaciones as observaciones",
                        DB::raw("if(pph.id_estado_proceso = 3,(if(datediff(current_timestamp, pph.fe_fin) > 0,'danger',if(datediff(pph.fe_fin, current_timestamp) < mhc.nu_dias_disparador,'success','warning'))),'secondary') as indicador"),
                        DB::raw("if(datediff(current_timestamp,pph.fe_fin) < 0,0,datediff(current_timestamp,pph.fe_fin)) as diasvence")
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
                    $proyectos[$idx]->indicador = $detalle->indicador;
                    $proyectos[$idx]->diasvence = $detalle->diasvence;
                }
                else {
                    $proyectos[$idx]->estado = "";
                    $proyectos[$idx]->responsable = "";
                    $proyectos[$idx]->hobservaciones = "";
                    $proyectos[$idx]->indicador = "secondary";
                    $proyectos[$idx]->diasvence = 0;
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

    function fl_busca_campo() {
        extract(Request::input());
        if(isset($tipo, $texto, $campo)) {
            $usuario = Auth::user();
            switch($tipo) {
                case 0:
                case 3:
                    $texto = "%" . strtolower($texto) . "%";
                    $ls_proyectos = DB::table("pr_proyecto_hitos_campos")
                        ->where("id_empresa", $usuario->id_empresa)
                        ->where(DB::raw("lower(des_valor)"), "like", $texto)
                        ->where(function($sql) use($campo) {
                            $sql->where(DB::raw("0"), $campo)
                                ->orWhere("id_campo", $campo);
                        })
                        ->select("id_proyecto as id")
                        ->distinct();
                    break;
                case 1:
                case 2:
                case 4:
                case 5:
                case 6:
                    $texto = strtolower($texto);
                    $ls_proyectos = DB::table("pr_proyecto_hitos_campos")
                        ->where("id_empresa", $usuario->id_empresa)
                        ->where(function($sql) use($campo) {
                            $sql->where(DB::raw("0"), $campo)
                                ->orWhere("id_campo", $campo);
                        })
                        ->where(DB::raw("lower(des_valor)"), $texto)
                        ->select("id_proyecto as id")
                        ->distinct();
                    break;
                default:
                    $ls_proyectos = [];
                    break;
            }
            //busca en los atributos de los proyectos
            if($campo == 4) {
                $ls_proyectos = DB::table("pr_proyecto_hitos")
                    ->where("des_observaciones", "like", $texto)
                    ->select("id_proyecto as id")
                    ->distinct()
                    ->union($ls_proyectos);
            }
            //
            return Response::json([
                "state" => "success",
                "data" => [
                    "proyectos" => $ls_proyectos->get()
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    function sv_mensaje() {
        extract(Request::input());
        if(isset($saludo, $cuerpo, $boton)) {
            DB::table("sys_mensaje")
                ->where("id_mensaje", 1)
                ->update([
                    "des_titulo" => $saludo,
                    "des_cuerpo" => $cuerpo,
                    "des_boton" => $boton,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            return Response::json([
                "state" => "success",
                "data" => []
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    function dt_proyecto() {
        extract(Request::input());
        if(isset($id)) {
            $usuario = Auth::user();
            $proyecto = DB::table("pr_proyecto")
                ->where("id_proyecto", $id)
                ->where("id_empresa", $usuario->id_empresa)
                ->select(
                    "des_expediente as expediente",
                    DB::raw("date_format(fe_inicio,'%Y-%m-%d') as frecepcion"),
                    "des_proyecto as descripcion",
                    "num_dias as plazo",
                    "des_contratista as contratista",
                    "num_valor as valor"
                )
                ->first();
            if(isset($proyecto)) return Response::json([
                "state" => "success",
                "data" => [
                    "proyecto" => $proyecto
                ]
            ]);
            return Response::json([
                "state" => "error",
                "msg" => "No se encontró el proyecto seleccionado"
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    function upd_proyecto() {
        extract(Request::input());
        if(isset($id, $expediente, $frecepcion, $descripcion, $plazo, $contratista, $valor)) {
            $usuario = Auth::user();
            DB::table("pr_proyecto")
                ->where("id_proyecto", $id)
                ->update([
                    "des_expediente" => $expediente,
                    "fe_inicio" => $frecepcion,
                    "des_proyecto" => $descripcion,
                    "num_dias" => $plazo,
                    "des_contratista" => $contratista,
                    "num_valor" => $valor
                ]);
            $proyectos = DB::table("pr_proyecto as pp")
                ->join("pr_catalogo_proyecto as pcp", "pp.id_catalogo", "=", "pcp.id_catalogo")
                ->join("ma_area_usuaria as mau", function($join) {
                    $join->on("pp.id_area", "=", "mau.id_area")
                        ->on("pp.id_direccion", "=", "mau.id_direccion")
                        ->on("pp.id_organo", "=", "mau.id_organo")
                        ->on("pp.id_empresa", "=", "mau.id_empresa");
                })
                ->join("ma_direccion_central as mdc", function($join) {
                    $join->on("mau.id_direccion", "=", "mdc.id_direccion")
                        ->on("mau.id_organo", "=", "mdc.id_organo")
                        ->on("mau.id_empresa", "=", "mdc.id_empresa");
                })
                ->join("ma_organo_control as moc", function($join) {
                    $join->on("mdc.id_organo", "=", "moc.id_organo")
                        ->on("mdc.id_empresa", "=", "moc.id_empresa");
                })
                ->join("pr_proyecto_hitos as pph", function($join) {
                    $join->on("pp.id_proyecto", "=", "pph.id_proyecto")
                        ->on("pp.id_empresa", "=", "pph.id_empresa");
                })
                ->join("pr_catalogo_hitos as pch", function($join) {
                    $join->on("pph.id_empresa", "=", "pch.id_empresa")
                        ->on("pph.id_hito", "=", "pch.id_hito")
                        ->on("pph.id_catalogo", "=", "pch.id_catalogo");
                })
                ->join("pr_valoracion as pv", function($join) {
                    $join->on("pph.id_estado_proceso", "=", "pv.id_estado_p")
                        ->on("pph.id_estado_documentacion", "=", "pv.id_estado_c");
                })
                ->select(
                    "pp.id_proyecto as id",
                    "pp.id_catalogo as catalogo",
                    "pcp.des_catalogo as tipo",
                    DB::raw("if(pp.tp_orden = 'C','Compras','Servicios') as orden"),
                    "pp.des_expediente as expediente",
                    DB::raw("date_format(pp.created_at,'%Y-%m-%d') as femision"),
                    DB::raw("concat(moc.des_organo,' - ',mdc.des_direccion,' - ',mau.des_area) as areausr"),
                    "pp.des_proyecto as proyecto",
                    "pp.des_contratista as contratista",
                    "pp.num_dias as ndias",
                    DB::raw("date_format(pp.fe_fin,'%Y-%m-%d') as fentrega"),
                    "pp.num_valor as valor",
                    "pp.num_armadas as armadas",
                    DB::raw("if(datediff(current_timestamp,pp.fe_fin) < 0,0,datediff(current_timestamp,pp.fe_fin)) as diasvence"),
                    "pp.des_observaciones as observaciones",
                    DB::raw("100 * sum(pch.nu_peso * pv.num_puntaje)/sum(pch.nu_peso) as avance")
                )
                ->where("pp.id_empresa", $usuario->id_empresa)
                ->groupBy("id", "catalogo", "tipo", "orden", "expediente", "femision", "areausr", "proyecto", "contratista", "ndias", "fentrega", "valor", "armadas", "diasvence", "observaciones")
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
                        "pph.des_observaciones as observaciones",
                        DB::raw("if(pph.id_estado_proceso = 3,(if(datediff(current_timestamp, pph.fe_fin) > 0,'danger',if(datediff(pph.fe_fin, current_timestamp) < mhc.nu_dias_disparador,'success','warning'))),'secondary') as indicador"),
                        DB::raw("if(datediff(current_timestamp,pph.fe_fin) < 0,0,datediff(current_timestamp,pph.fe_fin)) as diasvence")
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
                    $proyectos[$idx]->indicador = $detalle->indicador;
                    $proyectos[$idx]->diasvence = $detalle->diasvence;
                }
                else {
                    $proyectos[$idx]->estado = "";
                    $proyectos[$idx]->responsable = "";
                    $proyectos[$idx]->hobservaciones = "";
                    $proyectos[$idx]->indicador = "secondary";
                    $proyectos[$idx]->diasvence = 0;
                }
            }
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

    function upd_responsable_proyecto() {
        extract(Request::input());
        if(isset($id, $proyecto, $hito, $responsable)) {
            $user = Auth::user();
            $arrToUpdate = [
                "id_responsable" => $responsable,
                "updated_at" => date("Y-m-d H:i:s")
            ];
            if(isset($usuario)) $arrToUpdate["id_responsable_usuario"] = $usuario;
            DB::table("pr_proyecto_hitos")
                ->where("id_proyecto", $proyecto)
                ->where("id_hito", $hito)
                ->where("id_detalle", $id)
                ->where("id_empresa", $user->id_empresa)
                ->update($arrToUpdate);
            $proyectos = DB::table("pr_proyecto as pp")
                ->join("pr_catalogo_proyecto as pcp", "pp.id_catalogo", "=", "pcp.id_catalogo")
                ->join("ma_area_usuaria as mau", function($join) {
                    $join->on("pp.id_area", "=", "mau.id_area")
                        ->on("pp.id_direccion", "=", "mau.id_direccion")
                        ->on("pp.id_organo", "=", "mau.id_organo")
                        ->on("pp.id_empresa", "=", "mau.id_empresa");
                })
                ->join("ma_direccion_central as mdc", function($join) {
                    $join->on("mau.id_direccion", "=", "mdc.id_direccion")
                        ->on("mau.id_organo", "=", "mdc.id_organo")
                        ->on("mau.id_empresa", "=", "mdc.id_empresa");
                })
                ->join("ma_organo_control as moc", function($join) {
                    $join->on("mdc.id_organo", "=", "moc.id_organo")
                        ->on("mdc.id_empresa", "=", "moc.id_empresa");
                })
                ->join("pr_proyecto_hitos as pph", function($join) {
                    $join->on("pp.id_proyecto", "=", "pph.id_proyecto")
                        ->on("pp.id_empresa", "=", "pph.id_empresa");
                })
                ->join("pr_catalogo_hitos as pch", function($join) {
                    $join->on("pph.id_empresa", "=", "pch.id_empresa")
                        ->on("pph.id_hito", "=", "pch.id_hito")
                        ->on("pph.id_catalogo", "=", "pch.id_catalogo");
                })
                ->join("pr_valoracion as pv", function($join) {
                    $join->on("pph.id_estado_proceso", "=", "pv.id_estado_p")
                        ->on("pph.id_estado_documentacion", "=", "pv.id_estado_c");
                })
                ->select(
                    "pp.id_proyecto as id",
                    "pp.id_catalogo as catalogo",
                    "pcp.des_catalogo as tipo",
                    DB::raw("if(pp.tp_orden = 'C','Compras','Servicios') as orden"),
                    "pp.des_expediente as expediente",
                    DB::raw("date_format(pp.created_at,'%Y-%m-%d') as femision"),
                    DB::raw("concat(moc.des_organo,' - ',mdc.des_direccion,' - ',mau.des_area) as areausr"),
                    "pp.des_proyecto as proyecto",
                    "pp.des_contratista as contratista",
                    "pp.num_dias as ndias",
                    DB::raw("date_format(pp.fe_fin,'%Y-%m-%d') as fentrega"),
                    "pp.num_valor as valor",
                    "pp.num_armadas as armadas",
                    DB::raw("if(datediff(current_timestamp,pp.fe_fin) < 0,0,datediff(current_timestamp,pp.fe_fin)) as diasvence"),
                    "pp.des_observaciones as observaciones",
                    DB::raw("100 * sum(pch.nu_peso * pv.num_puntaje)/sum(pch.nu_peso) as avance")
                )
                ->where("pp.id_empresa", $user->id_empresa)
                ->groupBy("id", "catalogo", "tipo", "orden", "expediente", "femision", "areausr", "proyecto", "contratista", "ndias", "fentrega", "valor", "armadas", "diasvence", "observaciones")
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
                        "pph.des_observaciones as observaciones",
                        DB::raw("if(pph.id_estado_proceso = 3,(if(datediff(current_timestamp, pph.fe_fin) > 0,'danger',if(datediff(pph.fe_fin, current_timestamp) < mhc.nu_dias_disparador,'success','warning'))),'secondary') as indicador"),
                        DB::raw("if(datediff(current_timestamp,pph.fe_fin) < 0,0,datediff(current_timestamp,pph.fe_fin)) as diasvence")
                    )
                    ->where("pph.id_estado_proceso", 3)
                    ->where("pph.id_proyecto", $proyecto->id)
                    ->where("pph.id_empresa", $user->id_empresa)
                    ->orderBy("pph.id_detalle", "desc")
                    ->get();
                if(count($detalle) > 0) {
                    $detalle = $detalle[0];
                    $proyectos[$idx]->estado = $detalle->hito;
                    $proyectos[$idx]->responsable = $detalle->responsable;
                    $proyectos[$idx]->hobservaciones = $detalle->observaciones;
                    $proyectos[$idx]->indicador = $detalle->indicador;
                    $proyectos[$idx]->diasvence = $detalle->diasvence;
                }
                else {
                    $proyectos[$idx]->estado = "";
                    $proyectos[$idx]->responsable = "";
                    $proyectos[$idx]->hobservaciones = "";
                    $proyectos[$idx]->indicador = "secondary";
                    $proyectos[$idx]->diasvence = 0;
                }
            }
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

    public function sv_nueva_area() {
        extract(Request::input());
        if(isset($organo, $direccion, $area, $abrev)) {
            $usuario = Auth::user();
            DB::table("ma_area_usuaria")
                ->insert([
                    "id_direccion" => $direccion,
                    "id_organo" => $organo,
                    "id_empresa" => $usuario->id_empresa,
                    "des_area" => $area,
                    "des_abreviatura" => $abrev
                ]);
            $areas = DB::table("ma_area_usuaria")
                ->where("id_organo", $organo)
                ->where("id_direccion", $direccion)
                ->where("id_empresa", $usuario->id_empresa)
                ->select("id_area as value", "des_area as text")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "areas" => $areas
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

}