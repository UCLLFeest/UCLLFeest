<?php

namespace AppBundle\Util;

/**
 * A class that allows the definition of enums.
 * Taken from http://stackoverflow.com/questions/254514/php-and-enumerations
 *
 * @package AppBundle\Util
 */
abstract class BasicEnum {
    private static $constCacheArray = NULL;

    private static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    public static function isValidName($name, $strict = false) {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function isValidValue($value, $strict = true) {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }

    /**
     * Gets the names of all constants
     */
    public static function getNames()
    {
        $constants = self::getConstants();

        return array_keys($constants);
    }

    /**
     * Gets all constants as a map
     * @return array
     */
    public static function getAsMap()
    {
        return self::getConstants();
    }

    /**
     * Maps constants to names
     * @return array
     */
    public static function getAsInvertedMap()
    {
        return array_flip(self::getConstants());
    }

    public static function getName($value)
    {
        if(!self::isValidValue($value))
            return null;

        return self::getAsInvertedMap()[$value];
    }
}