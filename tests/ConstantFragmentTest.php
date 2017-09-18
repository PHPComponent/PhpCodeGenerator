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
use PHPComponent\PhpCodeGenerator\ConstantFragment;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class ConstantFragmentTest extends \PHPUnit_Framework_TestCase
{

    /** @var ConstantFragment */
    private $constant;

    public function testSetName()
    {
        $this->assertSame('__CONSTANT', $this->constant->getName());
    }

    /**
     * @param mixed $value
     * @dataProvider getValidValues
     */
    public function testSetValidValue($value)
    {
        $constant = new ConstantFragment('__CONSTANT', $value);
        $this->assertSame($value, $constant->getValue());
    }

    public function getValidValues()
    {
        return array(
            array(array()),
            array(true),
            array(false),
            array(1),
            array('string'),
            array(null),
            array(''),
        );
    }

    /**
     * @param mixed $value
     * @dataProvider getInvalidValues
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidValue($value)
    {
        $constant = new ConstantFragment('__CONSTANT', $value);
        $this->assertSame($value, $constant->getValue());
    }

    public function getInvalidValues()
    {
        return array(
            array(new \stdClass()),
        );
    }

    public function testDocComment()
    {
        $this->constant->setDocComment('/** constant */');
        $this->assertSame('/** constant */', $this->constant->getDocComment()->getComment());
    }

    public function testGetCode()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|CodeFormatter $code_formatter */
        $code_formatter = $this->getMockBuilder(CodeFormatter::class)
            ->setMethods(array('getLineEnd'))
            ->getMock();
        $constant = new ConstantFragment('__CONSTANT', '');
        $this->assertSame('const __CONSTANT = \'\';', $constant->getCode($code_formatter));
    }

    protected function setUp()
    {
        $this->constant = new ConstantFragment('__CONSTANT');
    }
}