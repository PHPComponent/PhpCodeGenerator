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

use PHPComponent\PhpCodeGenerator\ClassFragment;
use PHPComponent\PhpCodeGenerator\CodeFormatter;
use PHPComponent\PhpCodeGenerator\Exceptions\NamespaceAliasAlreadyExistsException;
use PHPComponent\PhpCodeGenerator\NamespaceFragment;

/**
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class NamespaceFragmentTest extends \PHPUnit_Framework_TestCase
{

    /** @var NamespaceFragment */
    private $namespace;

    public function testName()
    {
        $this->assertSame('PHPComponent', $this->namespace->getNamespace());
    }

    public function testClasses()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ClassFragment $class */
        $class = $this->getMockBuilder(ClassFragment::class)
            ->setConstructorArgs(array('Foo'))
            ->getMock();
        $class->expects($this->at(0))
            ->method('getName')
            ->willReturn('Foo');

        $this->namespace->addClass($class);
        $this->assertSame(array('Foo' => $class), $this->namespace->getClasses());
    }

    public function testGetCode()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|CodeFormatter $code_formatter */
        $code_formatter = $this->getMockBuilder(CodeFormatter::class)->getMock();
        $code_formatter->expects($this->atLeastOnce())
            ->method('printStatementEnd')
            ->willReturn(";\n");
        $code_formatter->expects($this->atLeastOnce())
            ->method('printOpeningCurlyBracket')
            ->willReturn("{\n");
        $code_formatter->expects($this->atLeastOnce())
            ->method('printClosingCurlyBracket')
            ->willReturn("\n}");

        /** @var \PHPUnit_Framework_MockObject_MockObject|ClassFragment $class */
        $class = $this->getMockBuilder(ClassFragment::class)
            ->setConstructorArgs(array('Foo'))
            ->getMock();
        $class->expects($this->atLeastOnce())
            ->method('getName')
            ->willReturn('Foo');
        $class->expects($this->atLeastOnce())
            ->method('getCode')
            ->with($code_formatter)
            ->willReturn('class Foo{}');
        $this->namespace->addClass($class);

        $this->assertSame("namespace PHPComponent;\nclass Foo{}" , $this->namespace->getCode($code_formatter));

        $this->namespace->addUse('PHPComponent\PhpCodeGenerator');
        $this->assertSame("namespace PHPComponent;\nuse PHPComponent\\PhpCodeGenerator;\nclass Foo{}", $this->namespace->getCode($code_formatter));

        $this->namespace->setBracketedSyntax(true);
        $this->namespace->addUse('PHPComponent\PhpCodeGenerator\Modifiers', 'Mod');
        $this->assertSame("namespace PHPComponent{\nuse PHPComponent\\PhpCodeGenerator;\nuse PHPComponent\\PhpCodeGenerator\\Modifiers as Mod;\nclass Foo{}\n}", $this->namespace->getCode($code_formatter));

    }

    public function testUses()
    {
        $this->assertSame($this->namespace, $this->namespace->addUse('PHPComponent\PhpCodeGenerator'));
        $this->namespace->addUse('PHPComponent\PhpCodeGenerator\Tests');

        try
        {
            $this->namespace->addUse('PHPComponent\PhpCodeGenerator\Tests');
            $this->fail('Exception about existing namespace alias was not thrown');
        }
        catch(NamespaceAliasAlreadyExistsException $e)
        {}

        $this->namespace->addUse('PHPComponent\PhpCodeGenerator\Modifiers', 'Modifiers');
        $this->assertSame(array(
            'PhpCodeGenerator' => 'PHPComponent\PhpCodeGenerator',
            'Tests' => 'PHPComponent\PhpCodeGenerator\Tests',
            'Modifiers' => 'PHPComponent\PhpCodeGenerator\Modifiers'),
            $this->namespace->getUses()
        );

        try
        {
            $this->namespace->addUse('PHPComponent\PhpCodeGenerator\Modifis', 'modifiers');
            $this->fail('Exception about existing namespace alias was not thrown');
        }
        catch(NamespaceAliasAlreadyExistsException $e)
        {}
    }

    public function testBracketedSyntax()
    {
        $this->namespace->setBracketedSyntax(true);
        $this->assertTrue($this->namespace->isBracketedSyntax());
        $this->namespace->setBracketedSyntax(false);
        $this->assertFalse($this->namespace->isBracketedSyntax());
    }

    /**
     * @param mixed $invalid_value
     * @dataProvider getInvalidValues
     * @expectedException \InvalidArgumentException
     */
    public function testBracketedSyntaxInvalidValues($invalid_value)
    {
        $this->namespace->setBracketedSyntax($invalid_value);
    }

    public function getInvalidValues()
    {
        return array(
            array(1),
            array('1'),
            array(array()),
            array(new \stdClass()),
            array(null),
            array(1.2),
        );
    }

    protected function setUp()
    {
        $this->namespace = new NamespaceFragment('PHPComponent');
    }
}