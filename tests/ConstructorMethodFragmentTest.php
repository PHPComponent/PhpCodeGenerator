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

use PHPComponent\PhpCodeGenerator\ConstructorMethodFragment;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class ConstructorMethodFragmentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \LogicException
     */
    public function testName()
    {
        $constructor_method = new ConstructorMethodFragment();
        $this->assertSame('__constructor', $constructor_method->getName());
        $constructor_method->setName('Foo');
    }
}
