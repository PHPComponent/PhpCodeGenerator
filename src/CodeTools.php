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
class CodeTools
{

    /**
     * @param string $string
     * @return string
     */
    public static function generatePlural($string)
    {
        if(preg_match('#^(.*(.))y$#', $string, $match) && !in_array($match[2], array('a', 'e', 'i', 'o', 'u')))
        {
            return $match[1].'ies';
        }
        elseif(preg_match('#^.*s$#', $string, $match))
        {
            return $string.'es';
        }
        return $string.'s';
    }

    /**
     * @param string $name
     * @return string
     */
    public static function generateCamelCase($name)
    {
        $name = preg_replace_callback('#(?:([a-z])[^a-z]([a-z]))#i', function($matches)
        {
            return $matches[1].strtoupper($matches[2]);
        }, $name);
        $name = ucfirst($name);
        return $name;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function generateUnderscoresCase($name)
    {
        $name = preg_replace('#([a-z])([A-Z0-9])#', '$1_$2', $name);
        $name = ucfirst($name);
        return $name;
    }

    /**
     * @param string $namespace
     * @return string
     */
    public static function extractNamespace($namespace)
    {
        $position = strrpos($namespace, '\\');
        if(!$position) return '';
        return substr($namespace, 0, $position);
    }

    /**
     * @param string $namespace
     * @return string
     */
    public static function extractNamespaceShortName($namespace)
    {
        $position = strrpos($namespace, '\\');
        if(!$position) return $namespace;
        return substr($namespace, $position + 1);
    }
}