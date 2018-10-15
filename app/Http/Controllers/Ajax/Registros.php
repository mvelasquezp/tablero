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
        if(isset($apemat, $apepat, $dni, $mail, $nombres, $tpdoc, $cargo, $vigencia)) {
            $usuario = Auth::user();
            $telefono = isset($telefono) ? $telefono : "";
            if(isset($_FILES["foto"]) && $_FILES["foto"]["size"] > 0) {
                $sourcePath = $_FILES["foto"]["tmp_name"];
                $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
                if(strcmp($ext, "JPG") != 0 && strcmp($ext, "jpg") != 0) {
                    return Response::json([
                        "state" => "error",
                        "msg" => "La imagen proporcionada debe estar en formato JPEG"
                    ]);
                }
                $targetPath = env("APP_STORAGE_PATH") . DIRECTORY_SEPARATOR . $dni . "." . strtolower($ext);
                move_uploaded_file($sourcePath, $targetPath);
            }
            //genera la entidad
            /*DB::table("ma_entidad")->insert([
                "cod_entidad" => $dni,
                "des_nombre_1" => strtoupper($apepat),
                "des_nombre_2" => strtoupper($apemat),
                "des_nombre_3" => strtoupper($nombres),
                "tp_documento" => $tpdoc
            ]);*/
            //genera el usuario
            $alias = strtolower(substr($nombres,0,1) . $apepat . substr($apemat,0,1));
            $password = substr($apepat,0,2) . substr($apemat,0,2) . $dni;
            /*$id = DB::table("ma_usuarios")->insertGetId([
                "des_alias" => $alias,
                "des_email" => $mail,
                "des_telefono" => $telefono,
                "tp_usuario" => "U",
                "password" => \Hash::make($password),
                "st_verifica_mail" => "N",
                "id_empresa" => $usuario->id_empresa,
                "cod_entidad" => $dni,
                "st_vigente" => $vigencia
            ]);*/
            //asigna puesto
            if($cargo != 0) {
                /*DB::table("us_usuario_puesto")->insert([
                    "id_usuario" => $id,
                    "id_empresa" => $usuario->id_empresa,
                    "id_puesto" => $cargo,
                    "st_vigente" => "Vigente"
                ]);*/
            }
            //envia el mail
            $maildata = [
                "nombre" => $nombres,
                "usuario" => $alias,
                "clave" => $password
            ];
            \Mail::send("mails.activacion", $maildata, function($message) use($mail, $nombres, $apepat) {
                $message->to($mail, strtoupper($nombres) . " " . strtoupper($apepat))
                    ->subject("Bienvenido");
                $message->from(env("MAIL_FROM"), env("MAIL_NAME"));
            });
            //
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
            ->leftJoin("ma_puesto as mpp", function($join) {
                $join->on("mp.id_superior", "=", "mpp.id_puesto")
                    ->on("mp.id_empresa", "=", "mpp.id_empresa");
            })
            ->leftJoin("us_usuario_puesto as uupp", function($join) {
                $join->on("mpp.id_puesto", "=", "uupp.id_puesto")
                    ->on("mpp.id_empresa", "=", "uupp.id_empresa")
                    ->on("uupp.st_vigente", "=", DB::raw("'Vigente'"));
            })
            ->where("mp.st_vigente", "Vigente")
            ->where("mp.id_empresa", $usuario->id_empresa)
            ->select(
                DB::raw("mp.id_puesto * 1000 + ifnull(uup.id_usuario,0) as id"),
                DB::raw("ifnull(mp.id_superior,0) * 1000 + ifnull(uupp.id_usuario,0) as parentId"),
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

    public function ls_combo_puestos() {
        $usuario = Auth::user();
        $puestos = DB::table("ma_puesto")
            ->where("id_empresa", $usuario->id_empresa)
            ->where("st_vigente", "Vigente")
            ->select(
                "id_puesto as value",
                "des_puesto as text"
            )
            ->orderBy("text", "asc")
            ->get();
        return Response::json([
            "state" => "success",
            "data" => [
                "puestos" => $puestos
            ]
        ]);
    }

    public function dt_usuario() {
        extract(Request::input());
        if(isset($id)) {
            $usuario = Auth::user();
            $usrdata = DB::table("ma_usuarios as mu")
                ->join("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
                ->leftJoin("us_usuario_puesto as uup", function($join) {
                    $join->on("mu.id_empresa", "=", "uup.id_empresa")
                        ->on("mu.id_usuario", "=", "uup.id_usuario")
                        ->on("uup.st_vigente", "=", DB::raw("'Vigente'"));
                })
                ->leftJoin("ma_puesto as mp", function($join) {
                    $join->on("uup.id_puesto", "=", "mp.id_puesto")
                        ->on("uup.id_empresa", "=", "mp.id_empresa");
                })
                ->select(
                    "me.des_nombre_1 as apepat",
                    "me.des_nombre_2 as apemat",
                    "me.des_nombre_3 as nombres",
                    "mu.des_email as mail",
                    "mu.des_telefono as telefono",
                    "me.cod_entidad as dni",
                    DB::raw("ifnull(mp.id_puesto,0) as puesto"),
                    "mu.st_vigente as vigencia"
                )
                ->where("mu.id_empresa", $usuario->id_empresa)
                ->where("mu.id_usuario", $id)
                ->first();
            if($usrdata) {
                $puestos = DB::table("ma_puesto")
                    ->where("id_empresa", $usuario->id_empresa)
                    ->where("st_vigente", "Vigente")
                    ->select(
                        "id_puesto as value",
                        "des_puesto as text"
                    )
                    ->orderBy("text", "asc")
                    ->get();
                $img = "";
                $imgPath = env("APP_STORAGE_PATH") . DIRECTORY_SEPARATOR . $usrdata->dni . ".jpg";
                if(file_exists($imgPath)) {
                    $img = base64_encode(file_get_contents($imgPath));
                }
                return Response::json([
                    "state" => "success",
                    "data" => [
                        "puestos" => $puestos,
                        "usuario" => $usrdata,
                        "imagen" => $img
                    ]
                ]);
            }
            return Response::json([
                "state" => "error",
                "msg" => "No se encontró al usuario"
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
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

    public function ed_usuario() {
        extract(Request::input());
        if(isset($uid, $dni)) {
            $usuario = Auth::user();
            //
            if(isset($_FILES["foto"]) && $_FILES["foto"]["size"] > 0) {
                $sourcePath = $_FILES["foto"]["tmp_name"];
                $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
                if(strcmp($ext, "JPG") != 0 && strcmp($ext, "jpg") != 0) {
                    return Response::json([
                        "state" => "error",
                        "msg" => "La imagen proporcionada debe estar en formato JPEG",
                        "file" => $_FILES
                    ]);
                }
                $targetPath = env("APP_STORAGE_PATH") . DIRECTORY_SEPARATOR . $dni . "." . strtolower($ext);
                if(file_exists($targetPath)) unlink($targetPath);
                move_uploaded_file($sourcePath, $targetPath);
            }
            //apepat,apemat,nombres,mail,telefono,cargo,foto,vigencia
            $edentidad = [
                "des_nombre_1" => $apepat,
                "des_nombre_2" => $apemat,
                "des_nombre_3" => $nombres,
                "updated_at" => date("Y-m-d H:i:s")
            ];
            DB::table("ma_entidad")
                ->where("cod_entidad", $dni)
                ->update($edentidad);
            $edusuario = [
                "des_email" => $mail,
                "des_telefono" => $telefono,
                "st_vigente" => $vigencia,
                "updated_at" => date("Y-m-d H:i:s")
            ];
            DB::table("ma_usuarios")
                ->where("id_usuario", $uid)
                ->where("id_empresa", $usuario->id_empresa)
                ->update($edusuario);
            if(isset($cargo) && $cargo != 0 && $oldcargo != $cargo) {
                DB::table("us_usuario_puesto")
                    ->where("id_empresa", $usuario->id_empresa)
                    ->where("id_usuario", $uid)
                    ->where("id_puesto", $oldcargo)
                    ->update([
                        "st_vigente" => "Retirado",
                        "updated_at" => date("Y-m-d H:i:s")
                    ]);
                DB::table("us_usuario_puesto")->insert([
                    "id_usuario" => $uid,
                    "id_empresa" => $usuario->id_empresa,
                    "id_puesto" => $cargo
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

    public function dt_puesto() {
        extract(Request::input());
        if(isset($compid)) {
            $usuario = Auth::user();
            $id = (int) ($compid / 1000);
            $data = DB::table("ma_puesto")
                ->where("id_puesto", $id)
                ->where("id_empresa", $usuario->id_empresa)
                ->select(
                    "id_puesto as id",
                    "des_puesto as nombre",
                    DB::raw("ifnull(id_oficina,0) as oficina"),
                    DB::raw("ifnull(id_superior,0) as superior"),
                    "st_vigente as vigencia"
                )
                ->first();
            if($data) return Response::json([
                "state" => "success",
                "data" => [
                    "puesto" => $data
                ]
            ]);
            return Response::json([
                "state" => "error",
                "msg" => "No se encontró el puesto [$id]"
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ed_puesto() {
        extract(Request::input());
        if(isset($id, $nombre, $vigencia)) {
            $usuario = Auth::user();
            $arrUpd = [
                "des_puesto" => $nombre,
                "st_vigente" => $vigencia,
                "updated_at" => date("Y-m-d H:i:s")
            ];
            if(isset($ancestro)) $arrUpd["id_superior"] = $ancestro;
            if(isset($oficina)) $arrUpd["id_oficina"] = $oficina;
            DB::table("ma_puesto")
                ->where("id_puesto", $id)
                ->update($arrUpd);
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

}