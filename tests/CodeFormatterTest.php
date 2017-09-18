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
use PHPComponent\PhpCodeGenerator\DefaultCodeFormatter;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class CodeFormatterTest extends \PHPUnit_Framework_TestCase
{

    /** @var \PHPUnit_Framework_MockObject_MockObject|CodeFormatter $code_formatter */
    private $code_formatter;

    public function testIndent()
    {
        $this->assertSame('', $this->code_formatter->getCurrentIndentString());
        $this->assertSame($this->code_formatter, $this->code_formatter->increaseIndent());
        $this->assertSame('    ', $this->code_formatter->getCurrentIndentString());
        $this->assertSame($this->code_formatter, $this->code_formatter->decreaseIndent());
        $this->assertSame('', $this->code_formatter->getCurrentIndentString());
    }

    public function testLineEnd()
    {
        $this->assertSame("\n", $this->code_formatter->getLineEnd());
        $this->assertSame("\n", $this->code_formatter->getLineEndBeforeCurlyBracket());
        $this->assertSame("\n", $this->code_formatter->getLineEndAfterCurlyBracket());
    }

    public function testCurlyBrackets()
    {
        $this->assertSame("\n{\n", $this->code_formatter->printOpeningCurlyBracket());
        $this->assertSame("\n}\n", $this->code_formatter->printClosingCurlyBracket());
    }

    public function testOthers()
    {
        $this->assertSame(";\n", $this->code_formatter->printStatementEnd());
        $this->assertSame(' = ', $this->code_formatter->printAssignment());
    }

    public function testPrintValue()
    {
        $this->assertSame('true', $this->code_formatter->printValue(true));
        $this->assertSame('false', $this->code_formatter->printValue(false));
        $this->assertSame('null', $this->code_formatter->printValue(null));
        $this->assertSame(1, $this->code_formatter->printValue(1));
        $this->assertSame(12.3, $this->code_formatter->printValue(12.3));
        $this->assertSame('\'foo\'', $this->code_formatter->printValue('foo'));
        $this->assertSame('\'bar\'', $this->code_formatter->printValue('\'bar\'', false, true));
        $this->assertSame('\'\'bar\'\'', $this->code_formatter->printValue('\'bar\''));
        $this->assertSame('array('."\n".'    0 => \'foo\','."\n".'    1 => \'bar\','."\n".')', $this->code_formatter->printValue(array('foo', 'bar')));
        $this->assertSame('array('.DefaultCodeFormatter::class.', \'getInstance\')', $this->code_formatter->printValue(array(DefaultCodeFormatter::class, 'getInstance'), true));
    }

    protected function setUp()
    {
        $this->code_formatter = $this->getMockBuilder(CodeFormatter::class)
            ->setMethods(null)
            ->getMock();
    }
}
