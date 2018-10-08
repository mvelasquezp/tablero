<?php

namespace App\Http\Controllers\Ajax;

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

    public function sv_usuario() {
        extract(Request::input());
        if(isset($apemat, $apepat, $dni, $mail, $nombres, $tpdoc, $cargo)) {
            $usuario = Auth::user();
            $telefono = isset($telefono) ? $telefono : "";
            //genera la entidad
            DB::table("ma_entidad")->insert([
                "cod_entidad" => $dni,
                "des_nombre_1" => strtoupper($apepat),
                "des_nombre_2" => strtoupper($apemat),
                "des_nombre_3" => strtoupper($nombres),
                "tp_documento" => $tpdoc
            ]);
            //genera el usuario
            $password = substr($apepat,0,2) . substr($apemat,0,2) . $dni;
            $id = DB::table("ma_usuarios")->insertGetId([
                "des_alias" => strtolower(substr($nombres,0,1) . $apepat . substr($apemat,0,1)),
                "des_email" => $mail,
                "des_telefono" => $telefono,
                "tp_usuario" => "U",
                "password" => \Hash::make($password),
                "st_verifica_mail" => "N",
                "id_empresa" => $usuario->id_empresa,
                "cod_entidad" => $dni
            ]);
            if(isset($_FILES["foto"])) {
                $sourcePath = $_FILES["foto"]["tmp_name"];
                $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
                $targetPath = env("APP_STORAGE_PATH") . DIRECTORY_SEPARATOR . $dni . "." . $ext;
                move_uploaded_file($sourcePath, $targetPath);
            }
            //asigna puesto
            if($cargo != 0) {
                DB::table("us_usuario_puesto")->insert([
                    "id_usuario" => $id,
                    "id_empresa" => $usuario->id_empresa,
                    "id_puesto" => $cargo,
                    "st_vigente" => "Vigente"
                ]);
            }
            return Response::json([
                "state" => "success"
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ls_puestos() {
        $usuario = Auth::user();
        $puestos = DB::table("ma_puesto as mp")
            ->leftJoin("us_usuario_puesto as uup", function($join) {
                $join->on("mp.id_puesto", "=", "uup.id_puesto")
                    ->on("mp.id_empresa", "=", "uup.id_empresa")
                    ->on("uup.st_vigente", "=", DB::raw("'Vigente'"));
            })
            ->leftJoin("ma_usuarios as mu", function($join) {
                $join->on("uup.id_usuario", "=", "mu.id_usuario")
                    ->on("uup.id_empresa", "=", "mu.id_empresa");
            })
            ->leftJoin("ma_oficina as mo", function($join) {
                $join->on("mp.id_oficina", "=", "mo.id_oficina")
                    ->on("mp.id_empresa", "=", "mo.id_empresa");
            })
            ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
            ->where("mp.st_vigente", "Vigente")
            ->where("mp.id_empresa", $usuario->id_empresa)
            ->select(
                "mp.id_puesto as id",
                "mp.id_superior as parentId",
                "mp.des_puesto as name",
                DB::raw("ifnull(concat(me.des_nombre_1, ' ', me.des_nombre_2, ' ', me.des_nombre_3),'(sin asignar)') as title"),
                DB::raw("ifnull(mu.des_telefono,'-') as phone"),
                DB::raw("ifnull(mu.des_email,'-') as mail"),
                DB::raw("ifnull(mo.des_oficina, '(sin asignar)') as oficina"),
                "mp.st_vigente as vigencia"
            )
            ->orderBy("mp.des_puesto", "asc")
            ->get();
        return Response::json($puestos);
    }

    public function sv_puesto() {
        extract(Request::input());
        if(isset($nombre)) {
            $usuario = Auth::user();
            $nuevoPuesto = [
                "des_puesto" => $nombre,
                "num_jerarquia" => 1,
                "id_empresa" => $usuario->id_empresa
            ];
            if(isset($ancestro)) {
                $jerarquia = DB::table("ma_puesto")
                    ->where("id_empresa", $usuario->id_empresa)
                    ->where("id_puesto", $ancestro)
                    ->select("num_jerarquia as value")
                    ->first();
                $nuevoPuesto["num_jerarquia"] = $jerarquia->value + 1;
                $nuevoPuesto["id_superior"] = $ancestro;
            }
            if(isset($oficina)) {
                $nuevoPuesto["id_oficina"] = $oficina;
            }
            DB::table("ma_puesto")->insert($nuevoPuesto);
            //carga datos
            $puestos = DB::table("ma_puesto as mp")
                ->leftJoin("us_usuario_puesto as uup", function($join) {
                    $join->on("mp.id_puesto", "=", "uup.id_puesto")
                        ->on("mp.id_empresa", "=", "uup.id_empresa")
                        ->on("uup.st_vigente", "=", DB::raw("'Vigente'"));
                })
                ->leftJoin("ma_usuarios as mu", function($join) {
                    $join->on("uup.id_usuario", "=", "mu.id_usuario")
                        ->on("uup.id_empresa", "=", "mu.id_empresa");
                })
                ->leftJoin("ma_oficina as mo", function($join) {
                    $join->on("mp.id_oficina", "=", "mo.id_oficina")
                        ->on("mp.id_empresa", "=", "mo.id_empresa");
                })
                ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
                ->where("mp.st_vigente", "Vigente")
                ->where("mp.id_empresa", $usuario->id_empresa)
                ->select(
                    "mp.id_puesto as id",
                    "mp.id_superior as parentId",
                    "mp.des_puesto as name",
                    DB::raw("ifnull(concat(me.des_nombre_1, ' ', me.des_nombre_2, ' ', me.des_nombre_3),'(sin asignar)') as title"),
                    DB::raw("ifnull(mu.des_telefono,'-') as phone"),
                    DB::raw("ifnull(mu.des_email,'-') as mail"),
                    DB::raw("ifnull(mo.des_oficina, '(sin asignar)') as oficina"),
                    "mp.st_vigente as vigencia"
                )
                ->get();
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
            return Response::json([
                "state" => "success",
                "data" => [
                    "puestos" => $puestos,
                    "ancestros" => $ancestros,
                    "oficinas" => $oficinas
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ls_permisos() {
        extract(Request::input());
        if(isset($usuario)) {
            $user = Auth::user();
            $accesos = DB::table("sys_permisos")
                ->where("id_empresa", $user->id_empresa)
                ->where("id_usuario", $usuario)
                ->where("st_habilitado", "S")
                ->where("st_vigente", "Vigente")
                ->select("id_item as id")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "accesos" => $accesos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_permisos() {
        extract(Request::input());
        if(isset($usuario, $accesos)) {
            $user = Auth::user();
            DB::table("sys_permisos")
                ->where("id_empresa", $user->id_empresa)
                ->where("id_usuario", $usuario)
                ->delete();
            $arrToInsert = [];
            foreach($accesos as $acceso) {
                $arrToInsert[] = [
                    "id_item" => $acceso,
                    "id_usuario" => $usuario,
                    "id_empresa" => $user->id_empresa
                ];
            }
            DB::table("sys_permisos")->insert($arrToInsert);
            return Response::json([
                "state" => "success",
                "data" => [
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

}