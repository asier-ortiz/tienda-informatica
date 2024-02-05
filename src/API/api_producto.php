<?php

use Backend\Model\Product;

require_once('../../vendor/autoload.php');
Dotenv\Dotenv::createImmutable(realpath("./../../"))->load();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET POST PUT DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

switch ($_SERVER['REQUEST_METHOD']) {

    /*
     * Ejemplo con Postman para leer registros
     *
     * !! Cambiar dependiendo de dónde se tenga guardado el proyecto
     *  http://localhost/ [CARPETA DONDE ESTÉ ESTE PROYECTO DENTRO DEL DIRECTORIO HTDOCS, SI ES QUE ESTÁ DENTRO DE ALGUNA] /tienda-informatica/src/API/api_producto.php
     *
     * [GET] http://localhost/ud05/UD05TE01/src/API/api_producto.php
     *
     * [GET] http://localhost/ud05/UD05TE01/src/API/api_producto.php?id=1
     */

    case 'GET':
        $createProductObject = function ($row) {
            extract($row);
            return array(
                "id" => $idProducto,
                "name" => $ProductoNombre,
                "type" => $idTipoProducto,
                "unity" => $Unidad,
                "description" => html_entity_decode($Descripcion),
                "price" => $pvpUnidad,
                "discount" => $Descuento
            );
        };
        if (isset($_GET['id'])) {
            $stmt = Product::getById($_GET['id'], true);
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $product_item = $createProductObject($row);
                http_response_code(200);
                echo json_encode($product_item);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => sprintf("El producto con ID %s no existe", $_GET['id'])));
            }
        } else {
            $stmt = Product::getAll(true);
            if ($stmt->rowCount() > 0) {
                $products = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $products[] = $createProductObject($row);
                }
                http_response_code(200);
                echo json_encode($products);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No se han encontrado productos"));
            }
        }
        break;

    /*
     * Ejemplo con Postman para crear un nuevo producto
     *
     * [POST] http://localhost/ud05/UD05TE01/src/API/api_producto.php
     *
     * Body -> Raw
     *
     *  {
            "name" : "Logitech Webcam C920",
            "type_id" : "3",
            "unity" : "ud",
            "description" : "Webcam Logitech",
            "price" : "60",
            "discount" : "100"
        }
     *
     */

    case 'POST':
        if (!empty($data->name) && !empty($data->type_id) && !empty($data->unity) && !empty($data->description)
            && !empty($data->price) && !empty($data->discount)) {
            if (!is_numeric($data->price) || intval($data->price) <= 0) {
                http_response_code(400);
                echo json_encode(array("message" => "No se ha prodido crear el producto. Los datos no son correctos"));
            }
            if (intval($data->discount) < 0 || intval($data->discount > 100)) {
                echo json_encode(array("message" => "No se ha prodido crear el producto. Los datos no son correctos"));
            }
            $product = new Product();
            $product->setName($data->name);
            $product->setType($data->type_id);
            $product->setUnity($data->unity);
            $product->setDescription($data->description);
            $product->setPrice($data->price);
            $product->setDiscount($data->discount);
            $rowCount = Product::insert($product);
            if (!is_null($rowCount) && $rowCount > 0) {
                http_response_code(201);
                echo json_encode(array("message" => "Producto creado"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se ha podido crear el producto. Servicio no disponible"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "No se ha podido crear el producto. Datos incompletos"));
        }
        break;

    /*
     * Ejemplo con Postman para actualizar el precio de un producto
     *
     * [PUT] http://localhost/ud05/UD05TE01/src/API/api_producto.php?id=1&price=250
     */

    case 'PUT':
        if (isset($_GET['id']) && isset($_GET['price'])) {
            if (!is_numeric($_GET['price']) || intval($_GET['price']) <= 0) {
                http_response_code(400);
                echo json_encode(array("message" => "No se ha prodido actualizar el precio del producto. Los datos no son correctos"));
            } else {
                $rowCount = Product::updatePrice($_GET['id'], $_GET['price']);
                if (!is_null($rowCount) && $rowCount > 0) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Precio del producto actualizado"));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "No se ha podido actualizar el precio del producto. Servicio no disponible"));
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "No se ha podido actualizar el precio del producto. Datos incompletos"));
        }
        break;

    /*
     * Ejemplo con Postman para borrar un producto
     *
     * [DELETE] http://localhost/ud05/UD05TE01/src/API/api_producto.php?id=40
     */

    case 'DELETE':
        if (isset($_GET['id'])) {
            $rowCount = Product::delete($_GET['id']);
            if (!is_null($rowCount) && $rowCount > 0) {
                http_response_code(200);
                echo json_encode(array("message" => "Producto eliminado"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se ha podido eliminar producto. Servicio no disponible"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "No se ha podido eliminar producto. Datos incompletos"));
        }
}