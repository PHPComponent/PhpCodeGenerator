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
use PHPComponent\PhpCodeGenerator\PhpCodeFragment;

/**
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class PhpCodeFragmentTest extends \PHPUnit_Framework_TestCase
{

    public function testCodeFragments()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ClassFragment $class_fragment */
        $class_fragment = $this->getMockBuilder(ClassFragment::class)
            ->setConstructorArgs(array('Foo'))
            ->getMock();
        $php_code_fragment = new PhpCodeFragment();
        $php_code_fragment->addCodeFragment($class_fragment);
        $this->assertSame(array($class_fragment), $php_code_fragment->getCodeFragments());
    }

    public function testGetCode()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|CodeFormatter $code_formatter */
        $code_formatter = $this->getMockBuilder(CodeFormatter::class)
            ->getMock();
        $code_formatter->expects($this->any())
            ->method('getLineEndAfterPhpOpeningTag')
            ->willReturn("\n\n");

        /** @var \PHPUnit_Framework_MockObject_MockObject|ClassFragment $class_fragment */
        $class_fragment = $this->getMockBuilder(ClassFragment::class)
            ->setConstructorArgs(array('Foo'))
            ->getMock();
        $class_fragment->expects($this->at(0))
            ->method('getCode')
            ->with($code_formatter)
            ->willReturn('class Foo{}');

        $php_code_fragment = new PhpCodeFragment();
        $php_code_fragment->addCodeFragment($class_fragment);
        $this->assertSame("<?php\n\nclass Foo{}", $php_code_fragment->getCode($code_formatter));
    }
}
