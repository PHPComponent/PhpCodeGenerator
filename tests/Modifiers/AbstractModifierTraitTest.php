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

use PHPComponent\PhpCodeGenerator\Modifiers\AbstractModifierTrait;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class AbstractModifierTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstract()
    {
        /** @var AbstractModifierTrait|\PHPUnit_Framework_MockObject_MockObject $abstract_modifier */
        $abstract_modifier = $this->getMockForTrait(AbstractModifierTrait::class);
        $this->assertSame($abstract_modifier, $abstract_modifier->setAbstract(true));
        $this->assertTrue($abstract_modifier->isAbstract());
        $this->assertSame($abstract_modifier, $abstract_modifier->setAbstract(false));
        $this->assertFalse($abstract_modifier->isAbstract());
    }

    /**
     * @param $invalid_value
     * @dataProvider getInvalidValues
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidValues($invalid_value)
    {
        /** @var AbstractModifierTrait|\PHPUnit_Framework_MockObject_MockObject $abstract_modifier */
        $abstract_modifier = $this->getMockForTrait(AbstractModifierTrait::class);
        $abstract_modifier->setAbstract($invalid_value);
    }

    public function getInvalidValues()
    {
        return array(
            array(1),
            array(array()),
            array('string'),
            array(new \stdClass()),
            array(1.2),
            array(null),
        );
    }
}
