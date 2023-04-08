<?php

namespace Controllers;

use Model\Categoria;
use Model\Dia;
use Model\Evento;
use Model\EventoRegistro;
use Model\Horas;
use Model\Paquete;
use Model\Ponentes;
use Model\Regalo;
use Model\Registro;
use Model\Usuario;
use MVC\Router;


class RegistroController
{

    public static function crear(Router $router)
    {
        if (!is_auth()) {
            header("location: /");
            return;
        }
        $registro = Registro::where("usuario_id", $_SESSION["id"]);
        if (isset($registro) && $registro->paquete_id == "3" || $registro->paquete_id == "2") {
            header("location: /boleto?id=" . urldecode($registro->token));
            return;
        }
        if (isset($registro) && $registro->paquete_id == "1") {
            header("location: /finalizar-registro/conferencias");
            return;
        }
        $router->render("registro/crear", [
            "titulo" => "Finalizar Registro"
        ]);
    }
    public static function gratis(Router $router)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!is_auth()) {
                header("location: /login");
                return;
            }

            $registro = Registro::where("usuario_id", $_SESSION["id"]);
            if (isset($registro) && $registro->paquete_id == "3") {
                header("location: /boleto?id=" . urldecode($registro->token));
                return;
            }


            $token = substr(md5(uniqid(rand())), 0, 8);
            $datos = [
                "paquete_id" => 3,
                "pago_id" => "",
                "token" => $token,
                "usuario_id" => $_SESSION["id"]
            ];
            $registro = new Registro($datos);
            $resultado = $registro->guardar();
            if ($resultado) {
                header("location: /boleto?id=" . urldecode($registro->token));
            }
        }
    }
    public static function pagar(Router $router)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!is_auth()) {
                header("location: /login");
                return;
            }

            //validar el post si viene vacio
            if (empty($_POST)) {
               echo json_encode([]);
               return;
            }

            //crear registro
            $token = substr(md5(uniqid(rand())), 0, 8);
            $datos = $_POST;
            $datos["token"] = $token;
            $datos["usuario_id"]= $_SESSION["id"];
            try {
                $registro = new Registro($datos);
                
                $resultado = $registro->guardar();
                echo json_encode($resultado);
                return;
                
            } catch (\Throwable $th) {
                echo json_encode(["resultado"=> "error"]);
            }
        }
    }
    public static function boleto(Router $router)
    {
        $token = $_GET["id"];
        if (!$token ||  !strlen($token) == 8) {
            header("location: / ");
            return;
        }
        $registro = Registro::where("token", $token);

        if (!$registro) {
            header("location: / ");
            return;
        }
        $registro->usuario = Usuario::find($registro->usuario_id);
        $registro->paquete = Paquete::find($registro->paquete_id);



        $router->render("registro/boleto", [

            "titulo" => "Asistencia a DevWebCamp",
            "registro" => $registro
        ]);
    }
    public static function conferencias (Router $router)
    {
        if (!is_auth()) {
            header("location: /login");
            return;
        }

        //validar si el usuario compro un boleto
        $usuario_id= $_SESSION["id"];
        $registro = Registro::where("usuario_id", $usuario_id);

        if (isset($registro) && $registro->paquete_id ==="2") {
            header("location: /boleto?id=". urlencode($registro->token));
            return;
        }
                
        if ($registro->paquete_id != "1") {
           header("location: /");
           return;
        }
        if ($registro->regalo_id > 0 && $registro->paquete_id != "1") {
            header("location: /boleto?id=". urlencode($registro->token));
            return;
        }
        


        $eventos = Evento::ordenar("hora_id", "ASC");
        $eventos_formateados = [];
        foreach ($eventos as $evento) {

            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Horas::find($evento->hora_id);
            $evento->ponente = Ponentes::find($evento->ponente_id);
            if ($evento->dia_id == "1" && $evento->categoria_id == "1") {
                $eventos_formateados["conferencias_v"][] = $evento;
            }
            if ($evento->dia_id == "2" && $evento->categoria_id == "1") {
                $eventos_formateados["conferencias_s"][] = $evento;
            }
            if ($evento->dia_id == "1" && $evento->categoria_id == "2") {
                $eventos_formateados["workshops_v"][] = $evento;
            }
            if ($evento->dia_id == "2" && $evento->categoria_id == "2") {
                $eventos_formateados["workshops_s"][] = $evento;
            }
        }
        $regalos = Regalo::all("ASC");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!is_auth()) {
                header("location: /login");
                return;
            }

            $eventos = explode(",", $_POST["eventos"]);
            
            if (empty($eventos)) {
                echo json_encode(["resultado"=> false]);
                return ;
            }
            $registro = Registro::where("usuario_id", $_SESSION["id"]);
           
            if (!isset($eventos) || $registro->paquete_id !== "1") {
                echo json_encode(["resultado"=> false]);
                return ;
            }
            $eventos_array=[];

            //validar la disponibilidad y existencia de los eventos
            foreach ($eventos as $evento_id) {
                $evento = Evento::find($evento_id);

                if (!isset($evento) || $evento->disponibles == "0") {
                    echo json_encode(["resultado"=> false]);
                    return ;
                }
                $eventos_array[] = $evento;
            }

            //restar los lugares disponibles
            foreach ($eventos_array as $evento) {
                $evento->disponibles -= 1;
                $evento->guardar();
                $datos =[
                    "evento_id"=> (int)$evento->id,
                    "registro_id"=> (int) $registro->id
                ];

                $registro_usuario = new EventoRegistro($datos);
                $registro_usuario->guardar();
            
            }

            $registro->sincronizar(["regalo_id"=> $_POST["regalo_id"]]);
            $resultado = $registro->guardar();
            if ($resultado) {
                echo json_encode(["resultado"=>$resultado, "token"=> $registro->token]);
                return;
                
            }else{
                echo json_encode(["resultado"=> false]);
                    return ;
            }

            
        }




        $router->render("registro/conferencias", [

            "titulo" => "Elige Workshops y Conferencias",
            "eventos"=> $eventos_formateados,
            "regalos"=>$regalos
           
        ]);
    }
}
