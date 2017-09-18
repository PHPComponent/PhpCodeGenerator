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
use PHPComponent\PhpCodeGenerator\CodeFormatter;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class AttributeFragmentTest extends \PHPUnit_Framework_TestCase
{

    /** @var AttributeFragment */
    private $attribute;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testName()
    {
        $attribute = new AttributeFragment('attribute');
        $this->assertSame('attribute', $attribute->getName());
        $attribute->setName('');
    }

    /**
     * @dataProvider invalidNames
     * @expectedException \InvalidArgumentException
     * @param string $name
     */
    public function testEmptyName($name)
    {
        new AttributeFragment($name);
    }

    public function invalidNames()
    {
        return array(
            array(1),
            array(false),
            array(''),
            array(null),
            array(array()),
            array(new \stdClass()),
        );
    }

    public function testModifiers()
    {
        $this->attribute->setPublic();
        $this->assertSame('public', $this->attribute->getVisibility());
        $this->assertTrue($this->attribute->isPublic());
        $this->assertFalse($this->attribute->isProtected());
        $this->assertFalse($this->attribute->isPrivate());
        $this->attribute->setStatic(true);
        $this->assertTrue($this->attribute->isStatic());
        $this->attribute->setProtected();
        $this->assertTrue($this->attribute->isProtected());
        $this->attribute->setPrivate();
        $this->assertTrue($this->attribute->isPrivate());
    }

    public function testGetCode()
    {
        $this->attribute->setProtected()->setDefaultValue('');
        /** @var \PHPUnit_Framework_MockObject_MockObject|CodeFormatter $code_formatter */
        $code_formatter = $this->getMockBuilder(CodeFormatter::class)
            ->setMethods(array('getLineEnd'))
            ->getMock();
        $code = $this->attribute->getCode($code_formatter);
        $this->assertSame('', $this->attribute->getDefaultValue());
        $this->assertSame('protected $attribute = \'\';', $code);
        $this->attribute->setPublic();
        $this->assertTrue($this->attribute->isPublic());
        $this->attribute->setStatic(true);
        $this->assertTrue($this->attribute->isStatic());
        $this->assertSame('public static $attribute = \'\';', $this->attribute->getCode($code_formatter));
    }

    public function testDocComment()
    {
        $this->attribute->setDocComment('comment');
        $this->assertSame('/** comment */', $this->attribute->getDocComment()->getComment());
    }

    protected function setUp()
    {
        $this->attribute = new AttributeFragment('attribute');
    }
}
