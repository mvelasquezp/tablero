<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use App\User as User;

class Estandarizacion extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        //
    }

    private function ObtenerMenu($usuario) {
        $menu = DB::table("sys_permisos as sp")
            ->join("ma_menu as mm", "sp.id_item", "=", "mm.id_item")
            ->where("sp.id_empresa", $usuario->id_empresa)
            ->where("sp.id_usuario", $usuario->id_usuario)
            ->where("mm.st_vigente", "Vigente")
            ->whereNull("mm.id_ancestro")
            ->select(
                "sp.id_item as id",
                "mm.des_nombre as nombre",
                "mm.des_url as url"
            )
            ->orderBy("mm.id_item", "asc")
            ->get();
        foreach($menu as $i => $item) {
            $submenu = DB::table("sys_permisos as sp")
                ->join("ma_menu as mm", "sp.id_item", "=", "mm.id_item")
                ->where("sp.id_empresa", $usuario->id_empresa)
                ->where("sp.id_usuario", $usuario->id_usuario)
                ->where("mm.id_ancestro", $item->id)
                ->where("mm.st_vigente", "Vigente")
                ->select(
                    "sp.id_item as id",
                    "mm.des_nombre as nombre",
                    "mm.des_url as url"
                )
                ->orderBy("mm.id_item", "asc")
                ->get();
            $menu[$i]->items = $submenu;
        }
        return $menu;
    }

    public function maestros() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $tipos = DB::table("sys_tipos_dato")
            ->select("id_tipo as value", "des_tipo as text")
            ->orderBy("text", "asc")
            ->get();
        $puestos = DB::table("ma_puesto")
            ->where("id_empresa", $usuario->id_empresa)
            ->select("id_puesto as value", "des_puesto as text")
            ->orderBy("text", "asc")
            ->get();
        //
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "tipos" => $tipos,
            "puestos" => $puestos
        ];
        return view("estandarizacion.maestros")->with($arr_data);
    }

    public function procesos() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $tipos_proyecto = DB::table("pr_catalogo_proyecto")
            ->where("id_empresa", $usuario->id_empresa)
            ->where("st_vigente", "Vigente")
            ->select(
                "id_catalogo as value",
                "des_catalogo as text"
            )
            ->orderBy("text", "asc")
            ->get();
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "tipos" => $tipos_proyecto,
        ];
        return view("estandarizacion.procesos")->with($arr_data);
    }

    public function valoracion() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $cestados = DB::table("sys_estados")
            ->where("tp_estado", "C")
            ->select(
                "id_estado as id",
                "des_estado as estado",
                "tp_estado as tipo"
            )
            ->orderBy("id_estado", "asc")
            ->get();
        $pestados = DB::table("sys_estados")
            ->where("tp_estado", "P")
            ->select(
                "id_estado as id",
                "des_estado as estado",
                "tp_estado as tipo"
            )
            ->orderBy("id_estado", "asc")
            ->get();
        $puntajes = DB::table("pr_valoracion")
            ->select("id_estado_p as pest", "id_estado_c as cest", "num_puntaje as puntaje")
            ->get();
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "cestados" => $cestados,
            "pestados" => $pestados,
            "puntajes" => $puntajes,
        ];
        return view("estandarizacion.matrizvaloracion")->with($arr_data);
    }

    public function usuarios() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $organos = DB::table("ma_organo_control")
            ->where("id_empresa", $usuario->id_empresa)
            ->select(
                "id_organo as id",
                "des_organo as organo",
                "des_abreviatura as abrev"
            )
            ->orderBy("des_organo", "asc")
            ->get();
        $direcciones = DB::table("ma_direccion_central as mdc")
            ->join("ma_organo_control as moc", function($join) {
                $join->on("mdc.id_empresa", "=", "moc.id_empresa")
                    ->on("mdc.id_organo", "=", "moc.id_organo");
            })
            ->select(
                "mdc.id_direccion as id",
                "moc.des_organo as organo",
                "mdc.des_direccion as direccion",
                "mdc.des_abreviatura as abrev"
            )
            ->where("mdc.id_empresa", $usuario->id_empresa)
            ->orderBy("moc.des_organo", "asc")
            ->orderBy("mdc.des_direccion", "asc")
            ->get();
        $areas = DB::table("ma_area_usuaria as mau")
            ->join("ma_direccion_central as mdc", function($join) {
                $join->on("mau.id_empresa", "=", "mdc.id_empresa")
                    ->on("mau.id_direccion", "=", "mdc.id_direccion")
                    ->on("mau.id_organo", "=", "mdc.id_organo");
            })
            ->join("ma_organo_control as moc", function($join) {
                $join->on("mdc.id_empresa", "=", "moc.id_empresa")
                    ->on("mdc.id_organo", "=", "moc.id_organo");
            })
            ->select(
                "mau.id_area as id",
                "moc.des_organo as organo",
                "mdc.des_direccion as direccion",
                "mau.des_area as area",
                "mau.des_abreviatura as abrev"
            )
            ->where("mau.id_empresa", $usuario->id_empresa)
            ->orderBy("moc.des_organo", "asc")
            ->orderBy("mdc.des_direccion", "asc")
            ->orderBy("mau.des_area", "asc")
            ->get();
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "organos" => $organos,
            "direcciones" => $direcciones,
            "areas" => $areas,
        ];
        return view("estandarizacion.usuarios")->with($arr_data);
    }

}