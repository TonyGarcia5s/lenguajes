<?php
require_once '../models/Datos.php';
switch ($_GET["op"]) {
    case 'listar_para_tabla':
        $user_login = new Datos();
        $usuarios = $user_login->listarTodosDb();
        $data = array();
        foreach ($usuarios as $reg) {
            $data[] = array(
                "0" => $reg->getId(),
                "1" => $reg->getInicio(),
                "2" => $reg->getFin()
            );
        }
        $resultados = array(
            "sEcho" => 1, ##informacion para datatables
            "iTotalRecords" => count($data), ## total de registros al datatable
            "iTotalDisplayRecords" => count($data), ## enviamos el total de registros a visualizar
            "aaData" => $data##Data 
        );
        echo json_encode($resultados);
        break;
    case 'insertar':
        $inicio = isset($_POST["inicio"]) ? trim($_POST["inicio"]) : "";
        $fin = isset($_POST["fin"]) ? trim($_POST["fin"]) : "";
        $usuario = new Datos();
        $usuario->setInicio($inicio);
        $encontrado = $usuario->verificarExistenciaDb();
        if ($encontrado == false) {
            $usuario->setInicio($inicio);
            $usuario->setFin($fin);
            $usuario->guardarEnDb();
            if ($usuario->verificarExistenciaDb()) {
                echo 1; 
            } else {
                echo 3; 
            }
        } else {
            echo 2;
        }
        break;

    case 'mostrar':
        $usuario = isset($_GET["user"]) ? $_POST["user"] : "";
        $user = new Datos();
        $user->getFin();
        $encontrado = $user->mostrar($usuario);
        if ($encontrado != null) {
            $arr = array();
            $arr[] = [
                "id" => $encontrado->getId(),
                "inicio" => $encontrado->getInicio(),
                "fin" => $encontrado->getFin()
            ];

            echo json_encode($arr);
        } else {
            echo 0;
        }
        break;
}
