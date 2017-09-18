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

use PHPComponent\PhpCodeGenerator\DataType;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class DataTypeTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @param string $type
     * @param bool $is_standard
     * @dataProvider getStandardTypeData
     */
    public function testStandardType($type, $is_standard)
    {
        $this->assertSame($is_standard, DataType::isStandardType($type));
    }

    /**
     * @param string $type
     * @param bool $is_array
     * @dataProvider getArrayOfTypesData
     */
    public function testArrayOfTypes($type, $is_array)
    {
        $this->assertSame($is_array, DataType::isArrayOfTypes($type));
    }

    public function getStandardTypeData()
    {
        return array(
            array('int', true),
            array('integer', true),
            array('bool', true),
            array('boolean', true),
            array('float', true),
            array('string', true),
            array('DataType', false),
            array('array', true),
            array('null', true),
            array('stdClass', false),
            array('test string', false),
            array('mixed', true),
            array('void', true),
        );
    }

    public function getArrayOfTypesData()
    {
        return array(
            array('int[]', true),
            array('string[]', true),
            array('array', true),
            array('DataType', false),
            array('stdClass', false),
            array('integer', false),
        );
    }
}
