<?php

namespace Controllers;

use Classes\Paginado;
use Intervention\Image\ImageManagerStatic as Image;
use Model\Ponentes;
use MVC\Router;

class PonentesController
{
    public static function index(Router $router)
    {
        $pagina_actual =$_GET["page"];
        $pagina_actual = filter_var($pagina_actual,FILTER_VALIDATE_INT);
        if (!$pagina_actual || $pagina_actual < 1) {
            header("location: /admin/ponentes?page=1");
        }
        
        $registro_por_pagina=10;
        $total= Ponentes::total();
        $paginacion = new Paginado($pagina_actual,$registro_por_pagina, $total); 
       
        if ($paginacion->total_paginas() < $pagina_actual) {
            header("location:  /admin/ponentes?page=1");
        }     

        $ponentes = Ponentes::paginar($registro_por_pagina, $paginacion->offset());

        if(!is_admin()){
            header("location: /login");
        }
        $router->render("admin/ponentes/index", ["titulo" => "Ponentes / Conferencistas", "ponentes" => $ponentes, "paginacion"=> $paginacion->paginacion()]);
    }
    public static function crear(Router $router)
    {
        if(!is_admin()){
            header("location: /login");
        }
        $alertas = [];
        $ponente = new Ponentes;


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!is_admin()){
                header("location: /login");
            }

            //leer imagen

            if (!empty($_FILES["imagen"]["tmp_name"])) {
                $carpeta_imagenes = "../public/img/speakers";
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0777, true);
                }
                $imagen_png = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 800)->encode("png", 80);
                $imagen_webp = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 800)->encode("webp", 80);
                $nombre_imagen = md5(uniqid(rand(), true));
                $_POST["imagen"] = $nombre_imagen;
            }
            //sincronizamos
            $_POST["redes"] =  json_encode($_POST["redes"], JSON_UNESCAPED_SLASHES);
            $ponente->sincronizar($_POST);

            //validamos los datos
            $alertas = $ponente->validar();
            if (empty($alertas)) {
                //guardar las imagenes
                $imagen_png->save($carpeta_imagenes . "/" . $nombre_imagen . ".png");
                $imagen_webp->save($carpeta_imagenes . "/" . $nombre_imagen . ".webp");

                $resultado = $ponente->guardar();
                if ($resultado) {
                    header("location: /admin/ponentes");
                }
            }
        }
        $alertas = Ponentes::getAlertas();
        $router->render("admin/ponentes/crear", ["titulo" => "Registrar Ponente", "alertas" => $alertas, "ponente" => $ponente, "redes" => json_decode($ponente->redes)]);
    }
    public static function editar(Router $router)
    {
        if(!is_admin()){
            header("location: /login");
        }
        $alertas = [];
        $id = $_GET["id"];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            header("location: /admin/ponentes");
        }
        //obtener ponente por el id
        $ponente = Ponentes::find($id);
        if (!$ponente) {
            header("location: /admin/ponentes");
        }
        $ponente->imagen_actual = $ponente->imagen;
        //obtenemos las redes y decodificamos el json
        $redes = json_decode($ponente->redes);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!is_admin()){
                header("location: /login");
            }
            if (!empty($_FILES["imagen"]["tmp_name"])) {
                $carpeta_imagenes = "../public/img/speakers";
                // Eliminar la imagen previa
                unlink($carpeta_imagenes . '/' . $ponente->imagen_actual . ".png");
                unlink($carpeta_imagenes . '/' . $ponente->imagen_actual . ".webp");
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0777, true);
                }
                $imagen_png = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 800)->encode("png", 80);
                $imagen_webp = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 800)->encode("webp", 80);
                $nombre_imagen = md5(uniqid(rand(), true));
                $_POST["imagen"] = $nombre_imagen;
            } else {
                $_POST["imagen"] = $ponente->imagen_actual;
            }
            $_POST["redes"] =  json_encode($_POST["redes"], JSON_UNESCAPED_SLASHES);
            $ponente->sincronizar($_POST);
            $alertas = $ponente->validar();

            if (empty($alertas)) {
                if (isset($nombre_imagen)) {
                    $imagen_png->save($carpeta_imagenes . "/" . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . "/" . $nombre_imagen . ".webp");
                }

                $resultado = $ponente->guardar();
                if ($resultado) {
                    header("location: /admin/ponentes");
                }
            }
        }
        $alertas = Ponentes::getAlertas();


        $router->render("admin/ponentes/editar", ["titulo" => "Actualizar Ponente", "ponente" => $ponente, "alertas" => $alertas, "redes" => $redes]);
    }


    public static function eliminar()
    {
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!is_admin()){
                header("location: /login");
            }
            $id = $_POST["id"];
            $ponente = Ponentes::find($id);

            if (!$ponente) {
                header("location: /admin/ponentes");
            }
            if ($ponente->imagen) {
                $carpeta_imagenes = '../public/img/speakers';
                unlink($carpeta_imagenes . '/' . $ponente->imagen . ".png");
                unlink($carpeta_imagenes . '/' . $ponente->imagen . ".webp");
            }

            $resultado = $ponente->eliminar();
            if ($resultado) {
                header("location: /admin/ponentes");
            }
        }
    }
}
