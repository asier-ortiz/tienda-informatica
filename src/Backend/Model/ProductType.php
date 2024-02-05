<?php

namespace Backend\Model;

use Backend\Trait\Sanitize;
use Error;
use PDOException;

class ProductType
{
    use Sanitize;

    private int $id;
    private string $description;
    private int $familyId;

    public static function getById($id): ?ProductType
    {
        try {
            $id = self::sanitizeValue($id);
            $stmt = Con::getInstance()->run("SELECT * FROM tipo_producto WHERE idTipo_producto = ?", [$id]);
            $row = $stmt->fetchObject(__CLASS__);
            if (!$row) return null;
            $productType = new ProductType();
            $productType->setId($row->idTipo_producto);
            $productType->setDescription($row->DescTipoProd);
            $productType->setFamilyId($row->idFamilia);
            return $productType;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function getAll(): ?array
    {
        try {
            $stmt = Con::getInstance()->run("SELECT * FROM tipo_producto");
            $types = array();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $type = new ProductType();
                $type->setId($row['idTipo_producto']);
                $type->setDescription($row['DescTipoProd']);
                $type->setFamilyId($row['idFamilia']);
                array_push($types, $type);
            }
            return $types;
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
     * @return int
     */
    public function getFamilyId(): int
    {
        return $this->familyId;
    }

    /**
     * @param int $familyId
     */
    public function setFamilyId(int $familyId): void
    {
        $this->familyId = $familyId;
    }

    public function __toString(): string
    {
        return
            $this->id . " " .
            $this->description . " " .
            $this->familyId;
    }
}