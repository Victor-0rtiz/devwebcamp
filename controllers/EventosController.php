<?php

namespace Controllers;

use Classes\Paginado;
use Model\Categoria;
use Model\Dia;
use Model\Evento;
use Model\EventoHorario;
use Model\Horas;
use Model\Ponentes;
use MVC\Router;

class EventosController
{
    public static function index(Router $router)
    {
        if(!is_admin()){
            header("location: /login");
        }
        $pagina_actual = $_GET["page"];
        $pagina_actual= filter_var($pagina_actual, FILTER_VALIDATE_INT);
        if (!$pagina_actual || $pagina_actual<1) {
            header("location: /admin/eventos?page=1");
        }
        $por_pagina= 10;
        $total= Evento::total();
        $paginacion = new Paginado($pagina_actual, $por_pagina, $total);
        $eventos= Evento::paginar($por_pagina, $paginacion->offset());
        foreach ($eventos as $evento) {
            $evento->categoria= Categoria::find($evento->categoria_id);
            $evento->dia= Dia::find($evento->dia_id);
            $evento->hora= Horas::find($evento->hora_id);
            $evento->ponente= Ponentes::find($evento->ponente_id);
        }
        
        $router->render("admin/eventos/index", ["titulo" => "Conferencias / Workshops", "eventos"=>$eventos, "paginacion"=>$paginacion->paginacion()]);
    }
    public static function crear(Router $router)
    {
        if(!is_admin()){
            header("location: /login");
        }
        $alertas = [];
        $categorias = Categoria::all();
        $dias = Dia::all("ASC");
        $horas = Horas::all("ASC");
        $evento = new Evento;


        if ($_SERVER["REQUEST_METHOD"]== "POST") {
            if(!is_admin()){
                header("location: /login");
            }
            $evento->sincronizar($_POST);
            $alertas= $evento->validar();
            if (empty($alertas)) {
                $resultado = $evento->guardar();

                if ($resultado) {
                    header("location: /admin/eventos");
                }
            }
        }

        $alertas= Evento::getAlertas();

        $router->render("/admin/eventos/crear", [
            "titulo" => "Registrar Evento",
            "alertas" => $alertas,
            "categorias" => $categorias,
            "dias" => $dias,
            "horas" => $horas,
            "evento"=> $evento
        ]);
    }
    public static function editar(Router $router)
    {
        if(!is_admin()){
            header("location: /login");
        }
        $id = $_GET["id"];
        $id= filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header("location: /admin/eventos?page=1");
        }

        $alertas = [];
        $categorias = Categoria::all();
        $dias = Dia::all("ASC");
        $horas = Horas::all("ASC");
        $evento = Evento::find($id);
        if (!$evento) {
            header("location: /admin/eventos?page=1");
        }


        if ($_SERVER["REQUEST_METHOD"]== "POST") {
            if(!is_admin()){
                header("location: /login");
            }
            $evento->sincronizar($_POST);
            $alertas= $evento->validar();
            if (empty($alertas)) {
                $resultado = $evento->guardar();

                if ($resultado) {
                    header("location: /admin/eventos");
                }
            }
        }

        $alertas= Evento::getAlertas();

        $router->render("/admin/eventos/editar", [
            "titulo" => "Editar Evento",
            "alertas" => $alertas,
            "categorias" => $categorias,
            "dias" => $dias,
            "horas" => $horas,
            "evento"=> $evento
        ]);
    }
    public static function eliminar()
    {
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!is_admin()){
                header("location: /login");
            }
            $id = $_POST["id"];
            $evento = Evento::find($id);

            if (!$evento) {
                header("location: /admin/eventos");
            }
           

            $resultado = $evento->eliminar();
            if ($resultado) {
                header("location: /admin/eventos");
            }
        }
    }
}
