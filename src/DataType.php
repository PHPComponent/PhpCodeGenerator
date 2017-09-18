<?php
/*
 * This file is part of PHPComponent/PhpCodeGenerator.
 *
 * Copyright (c) 2016 František Šitner <frantisek.sitner@gmail.com>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace PHPComponent\PhpCodeGenerator;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class DataType
{

    /** @var string[] */
    private static $simple_types = array('int', 'string', 'bool', 'float', 'mixed', 'void', 'null', 'array');

    /** @var string[] */
    private static $simple_types_aliases = array(
        'integer' => 'int',
        'double'  => 'float',
        'real'    => 'float',
        'boolean' => 'bool',
    );

    /**
     * @param string $type
     * @param string|null $normalized_name
     * @return bool
     */
    public static function isStandardType($type, &$normalized_name = null)
    {
        $type = strtolower($type);
        if(array_key_exists($type, self::$simple_types_aliases))
        {
            $normalized_name = self::$simple_types_aliases[$type];
            return true;
        }
        if(in_array($type, self::$simple_types))
        {
            $normalized_name = $type;
            return true;
        }
        $normalized_name = null;
        return false;
    }

    /**
     * @param string $type_definition
     * @param null|string $type
     * @return bool
     */
    public static function isArrayOfTypes($type_definition, &$type = null)
    {
        if(preg_match('#^(.+)\[\]$#', $type_definition, $match))
        {
            $type = $match[1];
            return true;
        }
        elseif(strcasecmp($type_definition, 'array') === 0)
        {
            $type = 'mixed';
            return true;
        }
        $type = null;
        return false;
    }
}