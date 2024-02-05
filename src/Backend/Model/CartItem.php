<?php

namespace Backend\Model;

use Backend\Trait\Sanitize;
use Error;
use PDOException;

class CartItem
{
    use Sanitize;

    private int $id;
    private int $cartId;
    private int $productId;
    private int $quantity;

    public static function getByCartAndProductId($cartId, $productId): ?CartItem
    {
        try {
            $cartId = self::sanitizeValue($cartId);
            $productId = self::sanitizeValue($productId);
            $stmt = Con::getInstance()->run("SELECT * FROM cesta_lineas WHERE idcesta = ? AND idproducto = ?", [$cartId, $productId]);
            $row = $stmt->fetchObject(__CLASS__);
            if (!$row) return null;
            $cartItem = new CartItem();
            $cartItem->setId($row->idcesta_lineas);
            $cartItem->setCartId($row->idcesta);
            $cartItem->setProductId($row->idproducto);
            $cartItem->setQuantity($row->cantidad);
            return $cartItem;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function getAllByCart($cartId): ?array
    {
        try {
            $cartId = self::sanitizeValue($cartId);
            $stmt = Con::getInstance()->run("SELECT * FROM cesta_lineas WHERE idcesta = ?", [$cartId]);
            $cartItems = array();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $cartItem = new CartItem();
                $cartItem->setId($row['idcesta_lineas']);
                $cartItem->setCartId($row['idcesta']);
                $cartItem->setProductId($row['idproducto']);
                $cartItem->setQuantity($row['cantidad']);
                array_push($cartItems, $cartItem);
            }
            return $cartItems;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function insert($cartItem): ?int
    {
        try {
            self::sanitizeObject($cartItem);
            $stmt = Con::getInstance()->run("INSERT INTO cesta_lineas (idcesta, idproducto, cantidad) VALUES (?, ?, ?)",
                [$cartItem->cartId, $cartItem->productId, $cartItem->quantity]);
            return $stmt->rowCount();
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function updateCartItemQuantity($cartItemId, $quantity): ?int
    {
        try {
            $cartItemId = self::sanitizeValue($cartItemId);
            $quantity = self::sanitizeValue($quantity);
            $stmt = Con::getInstance()->run("UPDATE cesta_lineas SET cantidad=? WHERE idcesta_lineas=?", [$quantity, $cartItemId]);
            return $stmt->rowCount();
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function deleteCartItemFromCart($cartId, $productId): ?int
    {
        try {
            $cartId = self::sanitizeValue($cartId);
            $productId = self::sanitizeValue($productId);
            $stmt = Con::getInstance()->run("DELETE FROM cesta_lineas WHERE idcesta=? AND idproducto=?", [$cartId, $productId]);
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
            $stmt = Con::getInstance()->run("DELETE FROM cesta_lineas WHERE idcesta_lineas=?", [$id]);
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
     * @return int
     */
    public function getCartId(): int
    {
        return $this->cartId;
    }

    /**
     * @param int $cartId
     */
    public function setCartId(int $cartId): void
    {
        $this->cartId = $cartId;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function __toString(): string
    {
        return
            $this->id . " " .
            $this->cartId . " " .
            $this->productId . " " .
            $this->quantity;
    }
}