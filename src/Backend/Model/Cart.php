<?php

namespace Backend\Model;

use Backend\Trait\Sanitize;
use Error;
use PDOException;

class Cart
{
    use Sanitize;

    private int $id;
    private int $userId;

    public static function getById($id): ?Cart
    {
        try {
            $id = self::sanitizeValue($id);
            $stmt = Con::getInstance()->run("SELECT * FROM cesta WHERE idcesta = ?", [$id]);
            $row = $stmt->fetchObject(__CLASS__);
            if (!$row) return null;
            $cart = new Cart();
            $cart->setId($row->idcesta);
            $cart->setUserId($row->idusuario);
            return $cart;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function getByUser($id): ?Cart
    {
        try {
            $id = self::sanitizeValue($id);
            $stmt = Con::getInstance()->run("SELECT * FROM cesta WHERE idusuario = ?", [$id]);
            $row = $stmt->fetchObject(__CLASS__);
            if (!$row) return null;
            $cart = new Cart();
            $cart->setId($row->idcesta);
            $cart->setUserId($row->idusuario);
            return $cart;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }


    public static function getLastUnshoppedCartByUser($id): ?Cart
    {
        try {
            $id = self::sanitizeValue($id);
            $stmt = Con::getInstance()->run
            (
                "
                    SELECT * FROM cesta WHERE idusuario = ? 
                    AND comprado LIKE '%NO%' 
                    ORDER BY idcesta 
                    DESC LIMIT 1
                    ",
                [$id]);
            $row = $stmt->fetchObject(__CLASS__);
            if (!$row) return null;
            $cart = new Cart();
            $cart->setId($row->idcesta);
            $cart->setUserId($row->idusuario);
            return $cart;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function createNewCart($userId): ?int
    {
        try {
            $userId = self::sanitizeValue($userId);
            $stmt = Con::getInstance()->run
            ("INSERT INTO cesta (idusuario, comprado) VALUES (?, ?)", [$userId, 'NO']);
            return $stmt->rowCount();
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function buy($cartId): ?int
    {
        try {
            $cartId = self::sanitizeValue($cartId);
            $stmt = Con::getInstance()->run("UPDATE cesta SET comprado=? WHERE idcesta=?", ['SI', $cartId]);
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
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function __toString(): string
    {
        return
            $this->id . " " .
            $this->userId;
    }
}