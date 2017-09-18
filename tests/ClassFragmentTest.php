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

use PHPComponent\PhpCodeGenerator\AttributeFragment;
use PHPComponent\PhpCodeGenerator\ClassFragment;
use PHPComponent\PhpCodeGenerator\CodeFormatter;
use PHPComponent\PhpCodeGenerator\ConstantFragment;
use PHPComponent\PhpCodeGenerator\MethodFragment;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class ClassFragmentTest extends \PHPUnit_Framework_TestCase
{

    /** @var ClassFragment */
    private $class;

    /**
     * @expectedException \PHPComponent\PhpCodeGenerator\Exceptions\MethodAlreadyExistsException
     */
    public function testMethods()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|MethodFragment $method_mock */
        $method_mock = $this->getMockBuilder(MethodFragment::class)
            ->setConstructorArgs(array('testMethod'))
            ->setMethods(null)
            ->getMock();
        $method_name = 'testmethod';
        $this->assertNull($this->class->getMethod($method_name));
        $this->assertSame($method_mock, $this->class->addMethod($method_mock));
        $this->assertSame(array($method_name => $method_mock), $this->class->getMethods());
        $this->assertSame($method_mock, $this->class->getMethod($method_name));
        $this->assertTrue($this->class->tryGetMethod($method_name, $method));
        $this->assertSame($method, $method_mock);
        $this->assertTrue($this->class->hasMethod($method_name));
        $this->class->addMethod($method_mock);
    }

    /**
     * @expectedException \PHPComponent\PhpCodeGenerator\Exceptions\AttributeAlreadyExistsException
     */
    public function testAttributes()
    {
        $attribute_name = 'bar';
        /** @var \PHPUnit_Framework_MockObject_MockObject|AttributeFragment $attribute_mock */
        $attribute_mock = $this->getMockBuilder(AttributeFragment::class)
            ->setConstructorArgs(array($attribute_name))
            ->setMethods(null)
            ->getMock();
        $this->assertNull($this->class->getAttribute($attribute_name));
        $this->assertFalse($this->class->hasAttribute($attribute_name));
        $this->assertSame($attribute_mock, $this->class->addAttribute($attribute_mock));
        $this->assertSame(array($attribute_name => $attribute_mock), $this->class->getAttributes());
        $this->assertTrue($this->class->tryGetAttribute($attribute_name, $attribute));
        $this->assertSame($attribute_mock, $attribute);
        $this->class->addAttribute($attribute_mock);
    }

    /**
     * @expectedException \PHPComponent\PhpCodeGenerator\Exceptions\ConstantAlreadyExistsException
     */
    public function testConstants()
    {
        $constant_name = 'BAR';
        /** @var \PHPUnit_Framework_MockObject_MockObject|ConstantFragment $constant_mock */
        $constant_mock = $this->getMockBuilder(ConstantFragment::class)
            ->setConstructorArgs(array($constant_name))
            ->setMethods(null)
            ->getMock();

        $this->assertNull($this->class->getConstant($constant_name));
        $this->class->addConstant($constant_mock);
        $this->assertSame(array('bar' => $constant_mock), $this->class->getConstants());
        $this->assertTrue($this->class->hasConstant($constant_name));
        $this->assertFalse($this->class->tryGetConstant('for', $constant));
        $this->assertNull($constant);
        $this->assertTrue($this->class->tryGetConstant($constant_name, $constant));
        $this->assertSame($constant_mock, $constant);
        $this->class->addConstant($constant_mock);
    }

    public function testImplements()
    {
        $this->assertSame(array(), $this->class->getImplements());
        $this->class->addImplements('interface1');
        $this->assertSame(array('interface1'), $this->class->getImplements());
        $this->class->setImplements('interface2', 'interface3');
        $this->assertSame(array('interface2', 'interface3'), $this->class->getImplements());
    }

    public function testExtends()
    {
        $this->assertNull($this->class->getExtends());
        $this->class->setExtends('Bar');
        $this->assertSame('Bar', $this->class->getExtends());
        $this->class->setExtends('Bar2');
        $this->assertSame('Bar2', $this->class->getExtends());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testName()
    {
        $this->class->setName('Foo');
        $this->assertSame('Foo', $this->class->getName());
        $this->class->setName('');
    }

    public function testGetCode()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|CodeFormatter $code_formatter */
        $code_formatter = $this->getMockBuilder(CodeFormatter::class)
            ->setMethods(array('getLineEnd'))
            ->getMock();

        /** @var \PHPUnit_Framework_MockObject_MockObject|AttributeFragment $attribute_mock */
        $attribute_mock = $this->getMockBuilder(AttributeFragment::class)
            ->setConstructorArgs(array('attribute'))
            ->getMock();
        $attribute_mock->expects($this->once())
            ->method('getCode')
            ->with($code_formatter)
            ->willReturn('private $attribute;');

        /** @var \PHPUnit_Framework_MockObject_MockObject|ConstantFragment $constant_mock */
        $constant_mock = $this->getMockBuilder(ConstantFragment::class)
            ->setConstructorArgs(array('_CONSTANT_'))
            ->getMock();
        $constant_mock->expects($this->at(0))
            ->method('getName')
            ->willReturn('_CONSTANT_');
        $constant_mock->expects($this->at(1))
            ->method('getCode')
            ->with($code_formatter)
            ->willReturn('const _CONSTANT_ = true;');

        /** @var \PHPUnit_Framework_MockObject_MockObject|MethodFragment $method_mock */
        $method_mock = $this->getMockBuilder(MethodFragment::class)
            ->setConstructorArgs(array('setName'))
            ->getMock();
        $method_mock->expects($this->at(0))
            ->method('getName')
            ->willReturn('setName');

        $method_mock->expects($this->at(1))
            ->method('getCode')
            ->with($code_formatter)
            ->willReturn('public function setName(){};');

        $this->class->setName('Foo');
        $this->class->setExtends('Bar');
        $this->class->setImplements('IBar', 'IFoo');
        $this->class->addAttribute($attribute_mock);
        $this->class->addConstant($constant_mock);
        $this->class->addMethod($method_mock);
        $code = 'class Foo extends Bar implements IBar, IFoo{const _CONSTANT_ = true;private $attribute;public function setName(){};}';
        $this->assertSame($code, $this->class->getCode($code_formatter));
    }

    public function testType()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|CodeFormatter $code_formatter */
        $code_formatter = $this->getMockBuilder(CodeFormatter::class)
            ->setMethods(array('getLineEnd'))
            ->getMock();
        $this->class->setType(ClassFragment::TYPE_INTERFACE);
        $this->assertSame('interface Foo{}', $this->class->getCode($code_formatter));
        $this->class->setType(ClassFragment::TYPE_TRAIT);
        $this->assertSame('trait Foo{}', $this->class->getCode($code_formatter));
    }

    protected function setUp()
    {
        $this->class = new ClassFragment('Foo');
    }
}