<?php
/*
 * This file is part of PHPComponent/PhpCodeGenerator.
 *
 * Copyright (c) 2016 František Šitner <frantisek.sitner@gmail.com>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace PHPComponent\PhpCodeGenerator\Tests\Modifiers;

use PHPComponent\PhpCodeGenerator\Modifiers\StaticModifierTrait;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class StaticModifierTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testStatic()
    {
        /** @var StaticModifierTrait|\PHPUnit_Framework_MockObject_MockObject $static_modifier */
        $static_modifier = $this->getMockForTrait(StaticModifierTrait::class);
        $this->assertSame($static_modifier, $static_modifier->setStatic(true));
        $this->assertTrue($static_modifier->isStatic());
        $this->assertSame($static_modifier, $static_modifier->setStatic(false));
        $this->assertFalse($static_modifier->isStatic());
    }

    /**
     * @param $value
     * @dataProvider getInvalidValues
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidValues($value)
    {
        /** @var StaticModifierTrait|\PHPUnit_Framework_MockObject_MockObject $static_modifier */
        $static_modifier = $this->getMockForTrait(StaticModifierTrait::class);
        $static_modifier->setStatic($value);
    }

    public function getInvalidValues()
    {
        return array(
            array(1),
            array('test'),
            array(array()),
            array(null),
            array(1.2),
            array(new \stdClass()),
        );
    }
}
