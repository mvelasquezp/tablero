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
        $usuarios = DB::table("ma_usuarios as mu")
            ->join("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
            ->leftJoin("us_usuario_puesto as uup", function($join) {
                $join->on("mu.id_empresa", "=", "uup.id_empresa")
                    ->on("mu.id_usuario", "=", "uup.id_usuario")
                    ->on("uup.st_vigente", "=", DB::raw("'Vigente'"));
            })
            ->leftJoin("ma_puesto as mp", function($join) {
                $join->on("uup.id_empresa", "=", "mp.id_empresa")
                    ->on("uup.id_puesto", "=", "mp.id_puesto");
            })
            ->leftJoin("ma_oficina as mo", function($join) {
                $join->on("mp.id_oficina", "=", "mo.id_oficina")
                    ->on("mp.id_empresa", "=", "mo.id_empresa");
            })
            ->select(
                "mu.id_usuario as id",
                "mu.cod_entidad as dni",
                "me.des_nombre_1 as apepat",
                "me.des_nombre_2 as apemat",
                "me.des_nombre_3 as nombres",
                DB::raw("date_format(mu.created_at,'%Y-%m-%d') as fingreso"),
                "mu.des_alias as alias",
                "mp.des_puesto as puesto",
                "mo.des_oficina as oficina",
                "mu.des_telefono as telefono",
                "mu.des_email as email",
                "mu.st_vigente as vigencia"
            )
            ->where("mu.id_empresa", 1)
            ->orderBy("vigencia", "desc")
            ->orderBy("apepat", "asc")
            ->orderBy("apemat", "asc")
            ->orderBy("nombres", "asc")
            ->get();
        //
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "usuarios" => $usuarios,
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

    public function administradores() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $usuarios = DB::table("ma_usuarios as mu")
            ->join("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
            ->leftJoin("us_usuario_puesto as uup", function($join) {
                $join->on("mu.id_empresa", "=", "uup.id_empresa")
                    ->on("mu.id_usuario", "=", "uup.id_usuario")
                    ->on("uup.st_vigente", "=", DB::raw("'Vigente'"));
            })
            ->leftJoin("ma_puesto as mp", function($join) {
                $join->on("uup.id_empresa", "=", "mp.id_empresa")
                    ->on("uup.id_puesto", "=", "mp.id_puesto");
            })
            ->leftJoin("ma_oficina as mo", function($join) {
                $join->on("mp.id_oficina", "=", "mo.id_oficina")
                    ->on("mp.id_empresa", "=", "mo.id_empresa");
            })
            ->select(
                "mu.id_usuario as id",
                "mu.cod_entidad as dni",
                "me.des_nombre_1 as apepat",
                "me.des_nombre_2 as apemat",
                "me.des_nombre_3 as nombres",
                DB::raw("ifnull(mp.des_puesto,'(sin asignar)') as puesto")
            )
            ->where("mu.id_empresa", 1)
            ->orderBy("apepat", "asc")
            ->orderBy("apemat", "asc")
            ->orderBy("nombres", "asc")
            ->get();
        $opciones = DB::table("ma_menu as mm")
            ->select(
                "mm.id_item as id",
                "mg.des_nombre as menu",
                "mm.des_nombre as item"
            )
            ->leftJoin("ma_menu as mg", "mm.id_ancestro", "=", "mg.id_item")
            ->whereNotNull("mm.id_ancestro")
            ->orderBy("mg.id_item", "asc")
            ->orderBy("mm.id_item", "asc")
            ->get();
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "usuarios" => $usuarios,
            "opciones" => $opciones,
        ];
        return view("registro.administradores")->with($arr_data);
    }

    public function bienvenida() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $mensaje = DB::table("sys_mensaje")
            ->select("des_titulo as saludo", "des_cuerpo as cuerpo", "des_boton as despedida")
            ->where("id_mensaje", 2)
            ->first();
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "mensaje" => $mensaje,
        ];
        return view("registro.bienvenida")->with($arr_data);
    }

}