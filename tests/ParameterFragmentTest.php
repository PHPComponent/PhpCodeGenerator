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

use PHPComponent\PhpCodeGenerator\CodeFormatter;
use PHPComponent\PhpCodeGenerator\Exceptions\InvalidTypeHintException;
use PHPComponent\PhpCodeGenerator\ParameterFragment;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class ParameterFragmentTest extends \PHPUnit_Framework_TestCase
{

    /** @var ParameterFragment */
    private $parameter;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testName()
    {
        $parameter = new ParameterFragment('foo');
        $this->assertSame('foo', $parameter->getName());
        $parameter->setName('');
    }

    /**
     * @param mixed $default_value
     * @param string $expected_value
     * @dataProvider getDefaultValues
     */
    public function testDefaultValue($default_value, $expected_value)
    {
        $this->parameter->setDefaultValue($default_value);
        $this->assertSame($expected_value, $this->parameter->getDefaultValue());
    }

    /**
     * @param string $type_hint
     * @dataProvider getTypeHints
     */
    public function testTypeHint($type_hint, $failed)
    {
        try
        {
            $this->parameter->setTypeHint($type_hint);
        }
        catch(InvalidTypeHintException $e)
        {
            if($failed === false) $this->fail('Not expected exception');
            return;
        }
        if($failed === true)
        {
            $this->fail('Expected exception');
            return;
        }
        $this->assertSame($type_hint, $this->parameter->getTypeHint());
    }

    public function testGetCode()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|CodeFormatter $code_formatter */
        $code_formatter = $this->getMockBuilder(CodeFormatter::class)
            ->setMethods(array('getLineEnd'))
            ->getMock();
        $this->assertSame('$foo', $this->parameter->getCode($code_formatter));
        $this->parameter->setDefaultValue('');
        $this->assertSame('$foo = \'\'', $this->parameter->getCode($code_formatter));
        $this->parameter->setTypeHint('stdClass');
        $this->parameter->setDefaultValue(null);
        $this->assertSame('stdClass $foo = null', $this->parameter->getCode($code_formatter));

    }

    public function getDefaultValues()
    {
        return array(
            array(1, 1),
            array(false, false),
            array('string', 'string'),
            array(array(), array()),
        );
    }

    public function getTypeHints()
    {
        return array(
            array('array', false),
            array('stdClass', false),
            array(array(), true),
            array(false, true),
        );
    }

    protected function setUp()
    {
        $this->parameter = new ParameterFragment('foo');
    }
}