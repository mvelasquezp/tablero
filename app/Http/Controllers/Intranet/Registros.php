<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use App\User as User;

class Registros extends Controller {
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

    public function usuarios() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        //
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu
        ];
        return view("registro.usuarios")->with($arr_data);
    }

    public function organigrama() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $ancestros = DB::table("ma_puesto")
            ->where("id_empresa", $usuario->id_empresa)
            ->select("id_puesto as value", "des_puesto as text")
            ->orderBy("text", "asc")
            ->get();
        $oficinas = DB::table("ma_oficina")
            ->where("id_empresa", $usuario->id_empresa)
            ->select("id_oficina as value", "des_oficina as text")
            ->orderBy("text", "asc")
            ->get();
        //
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "ancestros" => $ancestros,
            "oficinas" => $oficinas
        ];
        return view("registro.organigrama")->with($arr_data);
    }

}