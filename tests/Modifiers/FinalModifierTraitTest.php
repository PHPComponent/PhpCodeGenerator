<?php
/*
 * This file is part of PHPComponent/PhpCodeGenerator.
 *
 * Copyright (c) 2016 František Šitner <frantisek.sitner@gmail.com>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace PHPComponent\PhpCodeGenerator\Test\Modifiers;

use PHPComponent\PhpCodeGenerator\Modifiers\FinalModifierTrait;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class FinalModifierTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testFinal()
    {
        /** @var FinalModifierTrait|\PHPUnit_Framework_MockObject_MockObject $final_modifier */
        $final_modifier = $this->getMockForTrait(FinalModifierTrait::class);
        $this->assertSame($final_modifier, $final_modifier->setFinal(true));
        $this->assertTrue($final_modifier->isFinal());
        $this->assertSame($final_modifier, $final_modifier->setFinal(false));
        $this->assertFalse($final_modifier->isFinal());
    }

    /**
     * @param $invalid_value
     * @dataProvider getInvalidValues
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidValues($invalid_value)
    {
        /** @var FinalModifierTrait|\PHPUnit_Framework_MockObject_MockObject $final_modifier */
        $final_modifier = $this->getMockForTrait(FinalModifierTrait::class);
        $final_modifier->setFinal($invalid_value);
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