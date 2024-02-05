<?php

namespace Backend\Trait;

trait Sanitize
{
    public static function sanitizeObject($object)
    {
        foreach ($object as $attr => $value) {
            $value = trim($value);
            $value = stripslashes($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value);
            $object->$attr = $value;
        }
        return $object;
    }

    public static function sanitizeValue($value): string
    {
        $value = trim($value);
        $value = stripslashes($value);
        $value = strip_tags($value);
        $value = htmlspecialchars($value);
        return $value;
    }
}