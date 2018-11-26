<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use App\User as User;

class Main extends Controller {
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

    public function home() {
        /*$usuario = Auth::user();
        $menu = DB::table("sys_permisos as sp")
            ->join("ma_menu as mm", "sp.id_item", "=", "mm.id_item")
            ->where("sp.id_empresa", $usuario->id_empresa)
            ->where("sp.id_usuario", $usuario->id_usuario)
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
                ->select(
                    "sp.id_item as id",
                    "mm.des_nombre as nombre",
                    "mm.des_url as url"
                )
                ->orderBy("mm.id_item", "asc")
                ->get();
            $menu[$i]->items = $submenu;
        }
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu
        ];
        return view("intranet.home")->with($arr_data);*/
        return redirect("intranet/seguimiento/resumen");
    }

    public function mail() {
        \Mail::send("mails.prueba", [], function($message) {
            $message->to("mvelasquezp88@gmail.com")
                ->subject("Prueba");
            $message->from(env("MAIL_FROM"), env("MAIL_NAME"));
        });
        return "listo";
    }

    public function perfil() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $datos = DB::table("ma_usuarios as mu")
            ->join("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
            ->leftJoin("us_usuario_puesto as uup", function($join) {
                $join->on("uup.id_usuario", "=", "mu.id_usuario")
                    ->on("uup.id_empresa", "=", "mu.id_empresa");
            })
            ->leftJoin("ma_puesto as mp", function($join) {
                $join->on("uup.id_puesto", "=", "mp.id_puesto")
                    ->on("uup.id_empresa", "=", "mp.id_empresa");
            })
            ->leftJoin("ma_oficina as mo", function($join) {
                $join->on("mp.id_oficina", "=", "mo.id_oficina")
                    ->on("mp.id_empresa", "=", "mp.id_empresa");
            })
            ->select(
                "mu.id_usuario as id",
                "me.cod_entidad as dni",
                "me.des_nombre_1 as apepat",
                "me.des_nombre_2 as apemat",
                "me.des_nombre_3 as nombres",
                "mu.des_email as email",
                "mu.des_telefono as telefono",
                "mu.des_alias as alias",
                "mu.st_verifica_mail as verificado",
                "mu.created_at as fcrea",
                "mp.des_puesto as puesto",
                "mo.des_oficina as oficina"
            )
            ->where("mu.id_usuario", $usuario->id_usuario)
            ->where("mu.id_empresa", $usuario->id_empresa)
            ->first();
        $ImgPath = implode(DIRECTORY_SEPARATOR, [env("APP_STORAGE_PATH"), $datos->dni . ".jpg"]);
        $foto = file_exists($ImgPath) ? 1 : 0;
        $arrData = [
            "usuario" => $usuario,
            "menu" => $menu,
            "datos" => $datos,
            "foto" => $foto,
            "path" => $ImgPath,
        ];
        return view("intranet.perfil")->with($arrData);
    }

}