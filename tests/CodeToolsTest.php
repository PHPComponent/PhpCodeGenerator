<?php
/*
 * This file is part of PHPComponent/PhpCodeGenerator.
 *
 * Copyright (c) 2016 František Šitner <frantisek.sitner@gmail.com>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace PHPComponent\PhpCodeGenerator\Tests;

use PHPComponent\PhpCodeGenerator\CodeTools;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class CodeToolsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $value
     * @param string $expected_value
     * @dataProvider getValuesToGeneratePlural
     */
    public function testGeneratePlural($value, $expected_value)
    {
        $this->assertSame($expected_value, CodeTools::generatePlural($value));
    }

    public function getValuesToGeneratePlural()
    {
        return array(
            array('Car', 'Cars'),
            array('Property', 'Properties'),
            array('Type', 'Types'),
            array('Glass', 'Glasses'),
        );
    }

    /**
     * @param string $value
     * @param string $expected_value
     * @dataProvider getValuesToGenerateCamelCase
     */
    public function testGenerateCamelCase($value, $expected_value)
    {
        $this->assertSame($expected_value, CodeTools::generateCamelCase($value));
    }

    public function getValuesToGenerateCamelCase()
    {
        return array(
            array('get_value', 'GetValue'),
            array('method', 'Method'),
            array('foobar', 'Foobar'),
        );
    }

    /**
     * @param string $value
     * @param string $expected_value
     * @dataProvider getValuesToGenerateUnderscoresCase
     */
    public function testGenerateUnderscoresCase($value, $expected_value)
    {
        $this->assertSame($expected_value, CodeTools::generateUnderscoresCase($value));
    }

    public function getValuesToGenerateUnderscoresCase()
    {
        return array(
            array('GetValue', 'Get_Value'),
            array('method', 'Method'),
            array('Foo', 'Foo'),
            array('FooBar', 'Foo_Bar'),
        );
    }

    /**
     * @param string $value
     * @param string $expected_value
     * @dataProvider getExtractNamespaceShortNameValues
     */
    public function testExtractNamespaceShortName($value, $expected_value)
    {
        $this->assertSame($expected_value, CodeTools::extractNamespaceShortName($value));
    }

    public function getExtractNamespaceShortNameValues()
    {
        return array(
            array('PHPComponent\PhpCodeGenerator', 'PhpCodeGenerator'),
        );
    }
}
