<?php

namespace Backend\Model;

use Backend\Trait\Sanitize;
use Error;
use Exception;
use PDOException;

class User
{
    use Sanitize;

    private int $id;
    private string $name;
    private string $surnames;
    private string $username;
    private string $password;
    private bool $isAdmin = false;

    /**
     * @throws Exception
     */
    public static function getByUsername($username): ?User
    {
        try {
            $username = self::sanitizeValue($username);
            $stmt = Con::getInstance()->run("SELECT * FROM usuario WHERE UserName = ?", [$username]);
            $row = $stmt->fetchObject(__CLASS__);
            if (!$row) return null;
            $user = new User();
            $user->setId($row->idusuario);
            $user->setName($row->Nombre);
            $user->setSurnames($row->Apellidos);
            $user->setUsername($row->UserName);
            $user->setPassword($row->Pass);
            $user->setIsAdmin($row->Administrador);
            return $user;
        } catch (Error $e) {
            echo $e->getMessage();
            throw new Exception("Error en la conexion con la base de datos");
        } catch (PDOException $p) {
            echo $p->getMessage();
            throw new Exception("Error en la conexion con la base de datos");
        }
    }

    public static function getById($id): ?User
    {
        try {
            $id = self::sanitizeValue($id);
            $stmt = Con::getInstance()->run("SELECT * FROM usuario WHERE idusuario = ?", [$id]);
            $row = $stmt->fetchObject(__CLASS__);
            if (!$row) return null;
            $user = new User();
            $user->setId($row->idusuario);
            $user->setName($row->Nombre);
            $user->setSurnames($row->Apellidos);
            $user->setUsername($row->UserName);
            $user->setPassword($row->Pass);
            $user->setIsAdmin($row->Administrador);
            return $user;
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
            $stmt = Con::getInstance()->run("SELECT * FROM usuario");
            $users = array();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $user = new User();
                $user->setId($row['idusuario']);
                $user->setName($row['Nombre']);
                $user->setSurnames($row['Apellidos']);
                $user->setUsername($row['UserName']);
                $user->setPassword($row['Pass']);
                $user->setIsAdmin($row['Administrador']);
                array_push($users, $user);
            }
            return $users;
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function insert($user): ?int
    {
        try {
            self::sanitizeObject($user);
            $stmt = Con::getInstance()->run("INSERT INTO usuario (Nombre, Apellidos, UserName, Pass, Administrador) VALUES (?, ?, ? , ? ,?)",
                [$user->name, $user->surnames, $user->username, $user->password, $user->isAdmin ? 1 : 0]);
            return $stmt->rowCount();
        } catch (Error $e) {
            echo $e->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }

    public static function update($user): ?int
    {
        try {
            self::sanitizeObject($user);
            $stmt = Con::getInstance()->run("UPDATE usuario SET Nombre=?, Apellidos=?, UserName=?, Pass=?, Administrador=? WHERE idusuario=?",
                [$user->name, $user->surnames, $user->username, $user->password, $user->isAdmin ? 1 : 0, $user->id]);
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
            $stmt = Con::getInstance()->run("DELETE FROM usuario WHERE idusuario=?", [$id]);
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
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
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
     * @return string
     */
    public function getSurnames(): string
    {
        return $this->surnames;
    }

    /**
     * @param string $surnames
     */
    public function setSurnames(string $surnames): void
    {
        $this->surnames = $surnames;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    public function __toString(): string
    {
        return
            $this->id . " " .
            $this->name . " " .
            $this->surnames . " " .
            $this->username . " " .
            $this->password . " " .
            $this->isAdmin;
    }
}