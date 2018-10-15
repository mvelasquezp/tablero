<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Excel;
use App\User as User;

class Export extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        //
    }

    public function informe() {
        $usuario = Auth::user();
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
            ->groupBy("id", "tipo", "orden", "expediente", "femision", "areausr", "proyecto", "fentrega", "valor", "armadas", "diasvence", "observaciones")
            ->orderBy("pp.id_proyecto", "asc")
            ->get();
        //busca los ultimos hitos por proyecto y carga el detalle
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
                    DB::raw("if(pph.id_estado_proceso = 3,(if(datediff(current_timestamp, pph.fe_fin) > 0,'#f44336',if(datediff(pph.fe_fin, current_timestamp) < mhc.nu_dias_disparador,'#4caf50','#fdd835'))),'#90a4ae') as indicador")
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
            }
            else {
                $proyectos[$idx]->estado = "";
                $proyectos[$idx]->responsable = "";
                $proyectos[$idx]->hobservaciones = "";
                $proyectos[$idx]->indicador = "secondary";
            }
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
                    "pph.des_observaciones as observaciones",
                    DB::raw("if(pph.id_estado_proceso = 3,(if(datediff(current_timestamp, pph.fe_fin) > 0,'danger',if(datediff(pph.fe_fin, current_timestamp) < mhc.nu_dias_disparador,'success','warning'))),'secondary') as indicador")
                )
                ->where("pph.id_proyecto", $proyecto->id)
                ->where("pph.id_empresa", $usuario->id_empresa)
                ->orderBy("pph.id_detalle", "asc")
                ->get();
            foreach($hitos as $jdx => $hito) {
                $hitos[$jdx]->atributos = DB::table("pr_proyecto_hitos_campos as pphc")
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
                    ->where("pphc.id_proyecto", $proyecto->id)
                    ->where("pphc.id_hito", $hito->hid)
                    ->where("pphc.id_detalle", $hito->id)
                    ->where("mc.st_obligatorio", "N")
                    ->orderBy("pphc.id_hito", "asc")
                    ->get();
            }
            $proyectos[$idx]->hitos = $hitos;
        }
        Excel::create("informe", function($excel) use($proyectos) {
            $excel->setTitle("Resumen de proyectos");
            $excel->setCreator("mvelasquezp")
                ->setCompany("Ministerio de Salud");
            $excel->setDescription("Informe resumen de proyectos");
            $excel->sheet("Resumen", function($sheet) use($proyectos) {
                $sheet->row(1, ["ID", "Tipo proyecto", "Tipo orden", "N° Expediente", "Fecha emisión", "Área usuaria", "Descripción", "Fecha entrega", "Valor", "N° pagos", "% Avance", "Indicador", "Días vencimiento", "Estado actual", "Responsable", "Observaciones"]);
                $sheet->cell("A1:P1", function($cell) {
                    $cell->setBackground("#00897b")
                        ->setFontColor("#f0f0f0");
                });
                $idxRow = 2;
                foreach($proyectos as $idx => $proyecto) {
                    $iRow = [
                        $proyecto->id,
                        $proyecto->tipo,
                        $proyecto->orden,
                        $proyecto->expediente,
                        $proyecto->femision,
                        $proyecto->areausr,
                        $proyecto->proyecto,
                        $proyecto->fentrega,
                        $proyecto->valor,
                        $proyecto->armadas,
                        $proyecto->avance,
                        "",
                        $proyecto->diasvence,
                        $proyecto->estado,
                        $proyecto->responsable,
                        $proyecto->hobservaciones
                    ];
                    $sheet->row($idxRow + $idx, $iRow);
                    $sheet->cell("L" . ($idxRow + $idx), function($cell) use($proyecto) {
                        $cell->setBackground($proyecto->indicador);
                    });
                }
            });
            //una hoja para cada proyecto
            foreach($proyectos as $i => $proyecto) {
                $arr_proyecto_hd = ["% Avance", "Fecha límite", "Días vcto.", "Responsable", "Control documentario", "Control del proceso", "Observaciones"];
                $bg_rows = ["#ffffff", "#f0f0f0"];
                $excel->sheet($proyecto->tipo . "-" . $proyecto->id, function($sheet) use($proyecto, $arr_proyecto_hd, $bg_rows) {
                    //
                    $cabecera_1 = ["", "", "", "", "", "", "", "", "", "", "", "", "", "", ""];
                    $cabecera_2 = ["ID", "Tipo proyecto", "Tipo orden", "N° Expediente", "Fecha emisión", "Área usuaria", "Descripción", "Fecha entrega", "Valor", "N° pagos", "% Avance", "Días vencimiento", "Estado actual", "Responsable", "Observaciones"];
                    $iRow = [
                        $proyecto->id, //A
                        $proyecto->tipo,
                        $proyecto->orden,
                        $proyecto->expediente,
                        $proyecto->femision,
                        $proyecto->areausr,
                        $proyecto->proyecto,
                        $proyecto->fentrega,
                        $proyecto->valor,
                        $proyecto->armadas,
                        $proyecto->avance,
                        $proyecto->diasvence,
                        $proyecto->estado,
                        $proyecto->responsable,
                        $proyecto->hobservaciones //P
                    ];
                    $hitos = $proyecto->hitos;
                    foreach($hitos as $j => $hito) {
                            foreach($arr_proyecto_hd as $z => $hd) {
                                $cabecera_1[] = $z == 0 ? $hito->hito : "";
                                $cabecera_2[] = $hd;
                            }
                        array_push($iRow, $hito->avance, $hito->fin, $hito->diasvcto, $hito->responsable, $hito->edocumentacion, $hito->eproceso, $hito->observaciones);
                        $atributos = $hito->atributos;
                        foreach($atributos as $k => $atributo) {
                                $cabecera_1[] = "";
                                $cabecera_2[] = $atributo->nombre;
                            $iRow[] = $atributo->value;
                        }
                    }
                    $sheet->row(3, $iRow);
                    $sheet->row(1, $cabecera_1);
                    $sheet->row(1, function($row) {
                        $row->setBackground("#d8d8d8")->setFontColor("#404040");
                    });
                    $sheet->row(2, $cabecera_2);
                    $sheet->row(2, function($row) {
                        $row->setBackground("#00897b")->setFontColor("#f0f0f0");
                    });
                });
            }
            //hoja con Terceros
        })->download("xlsx");
    }

    public function estadistica() {
        $usuario = Auth::user();
        $data1 = DB::table("pr_proyecto as pp")
            ->join("pr_catalogo_proyecto as pcp", function($join) {
                $join->on("pp.id_catalogo", "=", "pcp.id_catalogo")
                    ->on("pp.id_empresa", "=", "pcp.id_empresa");
            })
            ->select(
                "pcp.des_catalogo as catalogo",
                DB::raw("count(pp.id_proyecto) as cantidad")
            )
            ->groupBy("catalogo")
            ->get();
        $data2 = DB::table("pr_proyecto")
            ->select(
                DB::raw("if(tp_orden = 'C', 'Compras', 'Servicios') as tipo"),
                DB::raw("count(id_proyecto) as cantidad")
            )
            ->where("id_catalogo", 1)
            ->groupBy("tipo")
            ->get();
        $data31 = DB::table("pr_proyecto as pp")
            ->join("ma_area_usuaria as maa", function($join) {
                $join->on("pp.id_area", "=", "maa.id_area")
                    ->on("pp.id_direccion", "=", "maa.id_direccion")
                    ->on("pp.id_organo", "=", "maa.id_organo")
                    ->on("pp.id_empresa", "=", "maa.id_empresa");
            })
            ->where("pp.id_catalogo", 1)
            ->select(
                "maa.des_area as area",
                DB::raw("count(if(pp.tp_orden = 'C',1,null)) as compras"),
                DB::raw("count(if(pp.tp_orden = 'S',1,null)) as servicios"),
                DB::raw("count(pp.id_proyecto) as total")
            )
            ->groupBy("area")
            ->get();
        $data32 = DB::table("pr_proyecto as pp")
            ->join("ma_area_usuaria as maa", function($join) {
                $join->on("pp.id_area", "=", "maa.id_area")
                    ->on("pp.id_direccion", "=", "maa.id_direccion")
                    ->on("pp.id_organo", "=", "maa.id_organo")
                    ->on("pp.id_empresa", "=", "maa.id_empresa");
            })
            ->where("pp.id_catalogo", 2)
            ->select(
                "maa.des_area as area",
                DB::raw("count(pp.id_proyecto) as total")
            )
            ->groupBy("area")
            ->get();
        $data41 = DB::table("pr_proyecto as pp")
            ->leftJoin("pr_proyecto_hitos as pph", function($join) {
                $join->on("pp.id_proyecto", "=", "pph.id_proyecto")
                    ->on("pp.id_empresa", "=", "pph.id_empresa")
                    ->on("pp.id_catalogo", "=", "pph.id_catalogo")
                    ->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
            })
            ->leftJoin("us_usuario_puesto as uup", function($join) {
                $join->on("pph.id_responsable", "=", "uup.id_puesto")
                    ->on("pph.id_empresa", "=", "uup.id_empresa");
            })
            ->leftJoin("ma_puesto as mp", function($join) {
                $join->on("pph.id_empresa", "=", "mp.id_empresa")
                    ->on("pph.id_responsable", "=", "mp.id_puesto");
            })
            ->leftJoin("ma_usuarios as mu", function($join) {
                $join->on("uup.id_usuario", "=", "mu.id_usuario")
                    ->on("uup.id_empresa", "=", "mu.id_empresa");
            })
            ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
            ->select(
                DB::raw("if(pph.id_responsable is null, '(sin agisnar)', if(uup.id_usuario is null,mp.des_puesto,concat(me.des_nombre_1,' ',me.des_nombre_2,' ',me.des_nombre_3))) as responsable"),
                DB::raw("count(if(pp.tp_orden = 'C',1,null)) as compras"),
                DB::raw("count(if(pp.tp_orden = 'S',1,null)) as servicios"),
                DB::raw("count(pp.id_proyecto) as total")
            )
            ->where("pp.id_catalogo", 1)
            ->groupBy("responsable")
            ->get();
        $data42 = DB::table("pr_proyecto as pp")
            ->leftJoin("pr_proyecto_hitos as pph", function($join) {
                $join->on("pp.id_proyecto", "=", "pph.id_proyecto")
                    ->on("pp.id_empresa", "=", "pph.id_empresa")
                    ->on("pp.id_catalogo", "=", "pph.id_catalogo")
                    ->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
            })
            ->leftJoin("us_usuario_puesto as uup", function($join) {
                $join->on("pph.id_responsable", "=", "uup.id_puesto")
                    ->on("pph.id_empresa", "=", "uup.id_empresa");
            })
            ->leftJoin("ma_puesto as mp", function($join) {
                $join->on("pph.id_empresa", "=", "mp.id_empresa")
                    ->on("pph.id_responsable", "=", "mp.id_puesto");
            })
            ->leftJoin("ma_usuarios as mu", function($join) {
                $join->on("uup.id_usuario", "=", "mu.id_usuario")
                    ->on("uup.id_empresa", "=", "mu.id_empresa");
            })
            ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
            ->select(
                DB::raw("if(pph.id_responsable is null, '(sin agisnar)', if(uup.id_usuario is null,mp.des_puesto,concat(me.des_nombre_1,' ',me.des_nombre_2,' ',me.des_nombre_3))) as responsable"),
                DB::raw("count(pp.id_proyecto) as total")
            )
            ->where("pp.id_catalogo", 2)
            ->groupBy("responsable")
            ->get();
        $data51 = DB::table("pr_proyecto as pp")
            ->leftJoin("pr_proyecto_hitos as pph", function($join) {
                $join->on("pp.id_proyecto", "=", "pph.id_proyecto")
                    ->on("pp.id_empresa", "=", "pph.id_empresa")
                    ->on("pp.id_catalogo", "=", "pph.id_catalogo")
                    ->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
            })
            ->select(
                DB::raw("if(datediff(current_timestamp, pph.fe_fin) > 0, datediff(current_timestamp, pph.fe_fin), 0) as dias"),
                DB::raw("count(if(pp.tp_orden = 'C',1,null)) as compras"),
                DB::raw("count(if(pp.tp_orden = 'S',1,null)) as servicios"),
                DB::raw("count(pp.id_proyecto) as total")
            )
            ->where("pp.id_catalogo", 1)
            ->groupBy("dias")
            ->get();
        $data52 = DB::table("pr_proyecto as pp")
            ->leftJoin("pr_proyecto_hitos as pph", function($join) {
                $join->on("pp.id_proyecto", "=", "pph.id_proyecto")
                    ->on("pp.id_empresa", "=", "pph.id_empresa")
                    ->on("pp.id_catalogo", "=", "pph.id_catalogo")
                    ->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
            })
            ->select(
                DB::raw("if(datediff(current_timestamp, pph.fe_fin) > 0, datediff(current_timestamp, pph.fe_fin), 0) as dias"),
                DB::raw("count(pp.id_proyecto) as total")
            )
            ->where("pp.id_catalogo", 2)
            ->groupBy("dias")
            ->get();
        $data61 = DB::table("pr_proyecto as pp")
            ->leftJoin("pr_proyecto_hitos as pph", function($join) {
                $join->on("pp.id_proyecto", "=", "pph.id_proyecto")
                    ->on("pp.id_empresa", "=", "pph.id_empresa")
                    ->on("pp.id_catalogo", "=", "pph.id_catalogo")
                    ->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
            })
            ->leftJoin("ma_hitos_control as mhc", function($join) {
                $join->on("pph.id_hito", "=", "mhc.id_hito")
                    ->on("pph.id_empresa", "=", "mhc.id_empresa");
            })
            ->select(
                DB::raw("ifnull(ifnull(pph.des_hito,mhc.des_hito),'(sin iniciar)') as hito"),
                DB::raw("count(if(pp.tp_orden = 'C',1,null)) as compras"),
                DB::raw("count(if(pp.tp_orden = 'S',1,null)) as servicios"),
                DB::raw("count(pp.id_proyecto) as total")
            )
            ->where("pp.id_catalogo", 1)
            ->groupBy("hito")
            ->get();
        $data62 = DB::table("pr_proyecto as pp")
            ->leftJoin("pr_proyecto_hitos as pph", function($join) {
                $join->on("pp.id_proyecto", "=", "pph.id_proyecto")
                    ->on("pp.id_empresa", "=", "pph.id_empresa")
                    ->on("pp.id_catalogo", "=", "pph.id_catalogo")
                    ->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
            })
            ->leftJoin("ma_hitos_control as mhc", function($join) {
                $join->on("pph.id_hito", "=", "mhc.id_hito")
                    ->on("pph.id_empresa", "=", "mhc.id_empresa");
            })
            ->select(
                DB::raw("ifnull(ifnull(pph.des_hito,mhc.des_hito),'(sin iniciar)') as hito"),
                DB::raw("count(pp.id_proyecto) as total")
            )
            ->where("pp.id_catalogo", 2)
            ->groupBy("hito")
            ->get();
        Excel::create("informe", function($excel) use($data1, $data2, $data31, $data32, $data41, $data42, $data51, $data52, $data61, $data62) {
            $excel->setTitle("Estadística de proyectos");
            $excel->setCreator("mvelasquezp")
                ->setCompany("Ministerio de Salud");
            $excel->setDescription("Informe estadístico de proyectos");
            //hoja 1
            $excel->sheet("Tipos_Proyecto", function($sheet) use($data1) {
                $sheet->row(1, ["Tipo proyecto", "Cantidad"]);
                $sheet->cell("A1:B1", function($cell) {
                    $cell->setBackground("#00897b")
                        ->setFontColor("#f0f0f0");
                });
                $idxRow = 2;
                foreach($data1 as $idx => $data) {
                    $iRow = [];
                    foreach($data as $celda) $iRow[] = $celda;
                    $sheet->row($idxRow + $idx, $iRow);
                }
            });
            //hoja 2
            $excel->sheet("Tipos_Orden", function($sheet) use($data2) {
                $sheet->row(1, ["Tipo orden", "Cantidad"]);
                $sheet->cell("A1:B1", function($cell) {
                    $cell->setBackground("#00897b")
                        ->setFontColor("#f0f0f0");
                });
                $idxRow = 2;
                foreach($data2 as $idx => $data) {
                    $iRow = [];
                    foreach($data as $celda) $iRow[] = $celda;
                    $sheet->row($idxRow + $idx, $iRow);
                }
            });
            //hoja 3
            $excel->sheet("Area_usuaria", function($sheet) use($data31, $data32) {
                $sheet->row(1, ["ASP", "", "", "", "", "Terceros", ""]);
                $sheet->row(2, ["Area usuaria", "Compras", "Servicios", "Total", "", "Area usuaria", "Servicios"]);
                $sheet->cell("A2:D2", function($cell) {
                    $cell->setBackground("#00897b")
                        ->setFontColor("#f0f0f0");
                });
                $sheet->cell("F2:G2", function($cell) {
                    $cell->setBackground("#00897b")
                        ->setFontColor("#f0f0f0");
                });
                $idxRow = 3;
                foreach($data32 as $idx => $data) {
                    $iRow = ["","","","",""];
                    foreach($data as $celda) $iRow[] = $celda;
                    $sheet->row($idxRow + $idx, $iRow);
                }
                foreach($data31 as $idx => $data) {
                    $iRow = [];
                    foreach($data as $celda) $iRow[] = $celda;
                    $sheet->row($idxRow + $idx, $iRow);
                }
            });
            //hoja 4
            $excel->sheet("Responsables", function($sheet) use($data41, $data42) {
                $sheet->row(1, ["ASP", "", "", "", "", "Terceros", ""]);
                $sheet->row(2, ["Responsable", "Compras", "Servicios", "Total", "", "Responsable", "Servicios"]);
                $sheet->cell("A2:D2", function($cell) {
                    $cell->setBackground("#00897b")
                        ->setFontColor("#f0f0f0");
                });
                $sheet->cell("F2:G2", function($cell) {
                    $cell->setBackground("#00897b")
                        ->setFontColor("#f0f0f0");
                });
                $idxRow = 3;
                foreach($data42 as $idx => $data) {
                    $iRow = ["","","","",""];
                    foreach($data as $celda) $iRow[] = $celda;
                    $sheet->row($idxRow + $idx, $iRow);
                }
                foreach($data41 as $idx => $data) {
                    $iRow = [];
                    foreach($data as $celda) $iRow[] = $celda;
                    $sheet->row($idxRow + $idx, $iRow);
                }
            });
            //hoja 5
            $excel->sheet("Dias_vencimiento", function($sheet) use($data51, $data52) {
                $sheet->row(1, ["ASP", "", "", "", "", "Terceros", ""]);
                $sheet->row(2, ["Días vencidos", "Compras", "Servicios", "Total", "", "Días vencidos", "Servicios"]);
                $sheet->cell("A2:D2", function($cell) {
                    $cell->setBackground("#00897b")
                        ->setFontColor("#f0f0f0");
                });
                $sheet->cell("F2:G2", function($cell) {
                    $cell->setBackground("#00897b")
                        ->setFontColor("#f0f0f0");
                });
                $idxRow = 3;
                foreach($data52 as $idx => $data) {
                    $iRow = ["","","","",""];
                    foreach($data as $celda) $iRow[] = $celda;
                    $sheet->row($idxRow + $idx, $iRow);
                }
                foreach($data51 as $idx => $data) {
                    $iRow = [];
                    foreach($data as $celda) $iRow[] = $celda;
                    $sheet->row($idxRow + $idx, $iRow);
                }
            });
            //hoja 4
            $excel->sheet("Estado_actual", function($sheet) use($data61, $data62) {
                $sheet->row(1, ["ASP", "", "", "", "", "Terceros", ""]);
                $sheet->row(2, ["Estado actual", "Compras", "Servicios", "Total", "", "Estado actual", "Servicios"]);
                $sheet->cell("A2:D2", function($cell) {
                    $cell->setBackground("#00897b")
                        ->setFontColor("#f0f0f0");
                });
                $sheet->cell("F2:G2", function($cell) {
                    $cell->setBackground("#00897b")
                        ->setFontColor("#f0f0f0");
                });
                $idxRow = 3;
                foreach($data62 as $idx => $data) {
                    $iRow = ["","","","",""];
                    foreach($data as $celda) $iRow[] = $celda;
                    $sheet->row($idxRow + $idx, $iRow);
                }
                foreach($data61 as $idx => $data) {
                    $iRow = [];
                    foreach($data as $celda) $iRow[] = $celda;
                    $sheet->row($idxRow + $idx, $iRow);
                }
            });
        })->download("xlsx");
    }

}