<?php

namespace Backend\Model;

use Backend\API\CurrencyExchangeManager;
use Backend\Trait\Sanitize;

use Error;
use Exception;
use PDOException;
use PDOStatement;

class Product
{
    use Sanitize;

    private int $id;
    private string $name;
    private int $type;
    private string $unity;
    private string $description;
    private float $price;
    private float $discount = 0;
    private static string $currencySymbol = '€';

    public static function getById($id, $assoc = false): Product|PDOStatement|null
    {
        try {
            $id = self::sanitizeValue($id);
            $stmt = Con::getInstance()->run("SELECT * FROM producto WHERE idProducto = ?", [$id]);
            if ($assoc) return $stmt;
            $row = $stmt->fetchObject(__CLASS__);
            if (!$row) return null;
            $product = new Product();
            $product->setId($row->idProducto);
            $product->setName($row->ProductoNombre);
            $product->setType($row->idTipoProducto);
            $product->setUnity($row->Unidad);
            $product->setDescription($row->Descripcion);
            $product->setPrice($row->pvpUnidad);
            $product->setDiscount($row->Descuento);
            return $product;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function getAll($assoc = false): array|PDOStatement|null
    {
        try {
            $stmt = Con::getInstance()->run("SELECT * FROM producto");
            if ($assoc) return $stmt;
            $products = array();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $product = new Product();
                $product->setId($row['idProducto']);
                $product->setName($row['ProductoNombre']);
                $product->setType($row['idTipoProducto']);
                $product->setUnity($row['Unidad']);
                $product->setDescription($row['Descripcion']);
                $product->setPrice($row['pvpUnidad']);
                $product->setDiscount($row['Descuento']);
                array_push($products, $product);
            }
            return $products;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function getAllByProductType($productTypeId): ?array
    {
        try {
            $productTypeId = self::sanitizeValue($productTypeId);
            $stmt = Con::getInstance()->run("SELECT * FROM producto WHERE idTipoProducto = ?", [$productTypeId]);
            $products = array();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $product = new Product();
                $product->setId($row['idProducto']);
                $product->setName($row['ProductoNombre']);
                $product->setType($row['idTipoProducto']);
                $product->setUnity($row['Unidad']);
                $product->setDescription($row['Descripcion']);
                $product->setPrice($row['pvpUnidad']);
                $product->setDiscount($row['Descuento']);
                array_push($products, $product);
            }
            return $products;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function getProductsAndQuantitiesByCart($cartId, $assoc = false): array|PDOStatement|null
    {
        try {
            $cartId = self::sanitizeValue($cartId);
            $stmt = Con::getInstance()->run
            (
                "
                    SELECT cl.cantidad, p.idProducto, p.ProductoNombre, p.idTipoProducto, p.Unidad, p.Descripcion, p.pvpUnidad, p.Descuento
                    FROM cesta_lineas cl
                    INNER JOIN producto p on cl.idproducto = p.idProducto
                    WHERE idcesta = $cartId
                    ORDER BY cantidad DESC 
                   "
            );
            $cartProductsAndQuantities = array();
            if ($assoc) return $stmt;
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $quantity = $row['cantidad'] ?? 0;
                $product = new Product();
                $product->setId($row['idProducto']);
                $product->setName($row['ProductoNombre']);
                $product->setType($row['idTipoProducto']);
                $product->setUnity($row['Unidad']);
                $product->setDescription($row['Descripcion']);
                $product->setPrice($row['pvpUnidad']);
                $product->setDiscount($row['Descuento']);
                array_push($cartProductsAndQuantities, [$quantity, $product]);
            }
            return $cartProductsAndQuantities;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function getAllProductsByProductTypeAndQuantitiesOfEachProductByCartId($cartId, $productTypeId): ?array
    {
        try {
            $cartId = self::sanitizeValue($cartId);
            $productTypeId = self::sanitizeValue($productTypeId);
            $stmt = Con::getInstance()->run
            (
                "
                    SELECT * FROM (
                    SELECT cl.cantidad, p.idProducto, p.ProductoNombre, p.idTipoProducto, p.Unidad, p.Descripcion, p.pvpUnidad, p.Descuento
                    FROM cesta_lineas cl
                    INNER JOIN producto p on cl.idproducto = p.idProducto
                    WHERE idcesta = $cartId
                    AND p.idTipoProducto = $productTypeId
                    UNION 
                    SELECT 0 AS cantidad, idProducto, ProductoNombre, idTipoProducto, Unidad, Descripcion, pvpUnidad, Descuento
                    FROM producto 
                    WHERE producto.idTipoProducto = $productTypeId) AS CartItem
                    GROUP BY CartItem.idProducto
                    ORDER BY max(CartItem.cantidad) DESC 
                   "
            );
            $cartProductsAndQuantities = array();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $quantity = $row['cantidad'] ?? 0;
                $product = new Product();
                $product->setId($row['idProducto']);
                $product->setName($row['ProductoNombre']);
                $product->setType($row['idTipoProducto']);
                $product->setUnity($row['Unidad']);
                $product->setDescription($row['Descripcion']);
                $product->setPrice($row['pvpUnidad']);
                $product->setDiscount($row['Descuento']);
                array_push($cartProductsAndQuantities, [$quantity, $product]);
            }
            return $cartProductsAndQuantities;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function insert($product): ?int
    {
        try {
            self::sanitizeObject($product);
            $stmt = Con::getInstance()->run
            (
                "INSERT INTO producto (ProductoNombre, idTipoProducto, Unidad, Descripcion, pvpUnidad, Descuento) VALUES (?, ?, ? ,? ,? ,?)",
                [$product->name, $product->type, $product->unity, $product->description, $product->price, $product->discount]
            );
            return $stmt->rowCount();
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function update($product): ?int
    {
        try {
            self::sanitizeObject($product);
            $stmt = Con::getInstance()->run
            (
                "UPDATE producto SET ProductoNombre=?, idTipoProducto=?, Unidad=?, Descripcion=?, pvpUnidad=?, Descuento=? WHERE idProducto=?",
                [$product->name, $product->type, $product->unity, $product->description, $product->price, $product->discount]
            );
            return $stmt->rowCount();
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function updatePrice($id, $price): ?int
    {
        try {
            $id = self::sanitizeValue($id);
            $price = self::sanitizeValue($price);
            $stmt = Con::getInstance()->run("UPDATE producto SET pvpUnidad=? WHERE idProducto=?", [$price, $id]);
            return $stmt->rowCount();
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function delete($id): ?int
    {
        try {
            $id = self::sanitizeValue($id);
            $stmt = Con::getInstance()->run("DELETE FROM producto WHERE idProducto=?", [$id]);
            return $stmt->rowCount();
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getUnity(): string
    {
        return $this->unity;
    }

    /**
     * @param string $unity
     */
    public function setUnity(string $unity): void
    {
        $this->unity = $unity;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getPrice(): float
    {
        if (isset($_COOKIE['currency'])) {
            $currency = $_COOKIE['currency'];
            return CurrencyExchangeManager::convertEuroTo($currency, $this->price);
        }
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return float|int
     */
    public function getDiscount(): float|int
    {
        return $this->discount;
    }

    /**
     * @param float|int $discount
     */
    public function setDiscount(float|int $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @return string
     */
    public static function getCurrencySymbol(): string
    {
        if (isset($_COOKIE['currency'])) {
            $currency = $_COOKIE['currency'];
            switch ($currency) {
                case "EUR":
                    self::setCurrencySymbol('€');
                    break;
                case "USD":
                    self::setCurrencySymbol('＄');
                    break;
                case "GBP":
                    self::setCurrencySymbol('£');
                    break;
                case "SEK":
                    self::setCurrencySymbol('kr');
                    break;
                default:
                    self::setCurrencySymbol('€');
            }
        }
        return self::$currencySymbol;
    }

    /**
     * @param string $currencySymbol
     */
    public static function setCurrencySymbol(string $currencySymbol): void
    {
        self::$currencySymbol = $currencySymbol;
    }

    /**
     * @throws Exception
     */
    public function getPriceWithDiscount(): float
    {
        return $this->getPrice() - (($this->getPrice() * $this->discount) / 100);
    }

    public function __toString(): string
    {
        return
            $this->id . " " .
            $this->name . " " .
            $this->type . " " .
            $this->unity . " " .
            $this->description . " " .
            $this->price . " " .
            $this->discount . " " .
            self::$currencySymbol;
    }
}