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
use PHPComponent\PhpCodeGenerator\MethodFragment;
use PHPComponent\PhpCodeGenerator\ParameterFragment;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class MethodFragmentTest extends \PHPUnit_Framework_TestCase
{

    /** @var MethodFragment */
    private $method;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testName()
    {
        $this->method->setName('getValue');
        $this->assertSame('getValue', $this->method->getName());
        $this->method->setName('');
    }

    public function testFinal()
    {
        $this->method->setFinal(true);
        $this->assertTrue($this->method->isFinal());
        $this->method->setFinal(false);
        $this->assertFalse($this->method->isFinal());
    }

    public function testStatic()
    {
        $this->method->setStatic(true);
        $this->assertTrue($this->method->isStatic());
        $this->method->setStatic(false);
        $this->assertFalse($this->method->isStatic());
    }

    public function testAbstract()
    {
        $this->method->setAbstract(true);
        $this->assertTrue($this->method->isAbstract());
        $this->method->setAbstract(false);
        $this->assertFalse($this->method->isAbstract());
    }

    public function testBody()
    {
        $body = 'return 1;';
        $this->method->setBody($body);
        $this->assertSame($body, $this->method->getBody());
    }

    public function testGetCode()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|CodeFormatter $code_formatter */
        $code_formatter = $this->getMockBuilder(CodeFormatter::class)
            ->setMethods(array('getLineEnd'))
            ->getMock();
        /** @var \PHPUnit_Framework_MockObject_MockObject|ParameterFragment $parameter */
        $parameter = $this->getMockBuilder(ParameterFragment::class)
            ->setConstructorArgs(array('foo'))
            ->getMock();

        $parameter->expects($this->at(0))->method('getCode')->with($code_formatter)->willReturn('$foo');

        $this->method->setName('getValue');
        $this->method->setPublic();
        $this->method->setBody('return $this->value;');
        $this->method->addParameter($parameter);
        $this->assertSame('public function getValue($foo){    return $this->value;}', $this->method->getCode($code_formatter));
        $this->assertSame(array($parameter), $this->method->getParameters());
    }

    public function testGetCodeAbstract()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|CodeFormatter $code_formatter */
        $code_formatter = $this->getMockBuilder(CodeFormatter::class)
            ->setMethods(array('getLineEnd'))
            ->getMock();
        $this->method->setName('getValue');
        $this->method->setProtected();
        $this->method->setAbstract(true);
        $this->assertSame('protected abstract function getValue();', $this->method->getCode($code_formatter));
    }

    public function testWithoutBody()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|CodeFormatter $code_formatter */
        $code_formatter = $this->getMockBuilder(CodeFormatter::class)
            ->setMethods(array('getLineEnd'))
            ->getMock();
        $this->method->setWithoutBody(true);
        $this->assertSame('public function foo();', $this->method->getCode($code_formatter));
        $this->method->setWithoutBody(false);
        $this->assertSame('public function foo(){}', $this->method->getCode($code_formatter));
    }

    protected function setUp()
    {
        $this->method = new MethodFragment('foo');
    }

}
