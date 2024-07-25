<?php

require_once '../models/Datos.php';

class GraficosController {
    public function obtenerDatosGrafico() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["op"])) {
            switch ($_GET["op"]) {
                case 'obtener_datos_grafico':
                    $user_login = new Datos();
                    $usuarios = $user_login->listarTodosDb();
                    $data = array();
                    foreach ($usuarios as $reg) {
                        $data[] = array(
                            "id" => $reg->getId(),
                            "inicio" => $reg->getInicio(),
                            "fin" => $reg->getFin()
                        );
                    }
                    header('Content-Type: application/json');
                    echo json_encode($data);
                    break;
                default:
                    http_response_code(404);
                    echo json_encode(array("message" => "Operación no encontrada"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Solicitud incorrecta"));
        }
    }
}


?>
