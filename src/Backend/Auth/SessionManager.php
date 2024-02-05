<?php

namespace Backend\Auth;

use Exception;

class SessionManager
{
    private static int $TIMEOUT_DURATION = 60 * 20;

    /**
     * @throws Exception
     */
    public static function start()
    {
        if (session_status() == PHP_SESSION_DISABLED)
            throw new Exception('Error: El uso de sesiones está deshabilitado');

        if (session_id() === '')
            if (!session_start())
                throw new Exception('Error: No se ha podido crear o recuperar la sesión');
    }

    /**
     * @throws Exception
     */
    public static function checkSessionIsValid()
    {
        if (!isset($_SESSION['LAST_ACTIVE']))
            throw new Exception('Error: Usuario no autenticado');

        if (time() - $_SESSION['LAST_ACTIVE'] > self::$TIMEOUT_DURATION)
            throw new Exception('Tu sesión ha caducado. Por favor, vuelve a loguearte');
    }

    /**
     * @throws Exception
     */
    public static function write($key, &$value)
    {
        if (!is_string($key))
            throw new Exception('Error: La clave debe ser de tipo String');
        if (is_object($value)) $value = serialize($value);
        $_SESSION[$key] = $value;
    }

    public static function read($key, $unserialize = false)
    {
        if (isset($_SESSION[$key])) {
            $unserialize ? $value = unserialize($_SESSION[$key]) : $value = $_SESSION[$key];
            return $value;
        }
        return null;
    }

    public static function destroy()
    {
        if (session_id() !== '') {
            $_SESSION = array();
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 4200, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
            }
        }
        session_destroy();
    }
}