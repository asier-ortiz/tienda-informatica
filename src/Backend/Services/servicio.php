<?php

use Backend\Model\Con;

require_once("../../../vendor/econea/nusoap/src/nusoap.php");
require_once('../../../vendor/autoload.php');
Dotenv\Dotenv::createImmutable(realpath("./../../../"))->load();

$server = new soap_server();
$namespace = "urn:ServiceLowStock";
$server->configureWSDL("ServiceLowStock, $namespace");
$server->wsdl->schemaTargetNamespaces = $namespace;
$server->register("getLowStockProducts", array(), array('return' => 'xsd:string'));

function getLowStockProducts(): bool|string|null
{
    try {
        $stmt = Con::getInstance()->run(
            "
                    SELECT product.ProductoNombre, provider.stock, provider.diasPedido
                    FROM servicio.proveedor AS provider
                    JOIN UD04.producto AS product
                    ON provider.idProducto = product.idProducto
                    AND provider.stock < 4
                    ORDER BY provider.stock 
                    ");
        $products = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }
        return json_encode($products);
    } catch (Error $e) {
        echo $e->getMessage();
        return null;
    } catch (PDOException $p) {
        echo $p->getMessage();
        return null;
    }
}

$server->service(file_get_contents("php://input"));