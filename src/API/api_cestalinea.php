<?php

use Backend\Model\Product;

require_once('../../vendor/autoload.php');
Dotenv\Dotenv::createImmutable(realpath("./../../"))->load();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

/*
   * Ejemplo con Postman para leer registros
   *
   * [GET] http://localhost/ud05/UD05TE01/src/API/api_cestalinea.php?id=1
   */

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $cartId = $_GET['id'] ?? die();
    $createProductObject = function ($row) {
        extract($row);
        return array(
            "id" => $idProducto,
            "name" => $ProductoNombre,
            "type" => $idTipoProducto,
            "unity" => $Unidad,
            "description" => html_entity_decode($Descripcion),
            "price" => $pvpUnidad,
            "discount" => $Descuento,
            "quantity" => $cantidad
        );
    };
    $stmt = Product::getProductsAndQuantitiesByCart($cartId, true);
    if ($stmt->rowCount() > 0) {
        $cartProducts = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cartProducts[] = $createProductObject($row);
        }
        http_response_code(200);
        echo json_encode($cartProducts);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => sprintf("No se han encontrado productos para la cesta con ID %s", $_GET['id'])));
    }
}