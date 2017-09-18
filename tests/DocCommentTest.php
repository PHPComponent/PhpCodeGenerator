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

use PHPComponent\PhpCodeGenerator\DocComment;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class DocCommentTest extends \PHPUnit_Framework_TestCase
{


    public function testDocComment()
    {
        $doc_comment = new DocComment('Test comment');
        $this->assertSame('/** Test comment */', $doc_comment->getComment());
        $doc_comment = new DocComment('/** Test comment */');
        $this->assertSame('/** Test comment */', $doc_comment->getComment());
        $this->assertSame('Test comment', $doc_comment->getDescription());

    }
}
