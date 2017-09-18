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

use PHPComponent\PhpCodeGenerator\Modifiers\AbstractFinalModifierTrait;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class AbstractFinalModifierTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstract()
    {
        /** @var AbstractFinalModifierTrait|\PHPUnit_Framework_MockObject_MockObject $abstract_final_modifier */
        $abstract_final_modifier = $this->getMockForTrait(AbstractFinalModifierTrait::class);
        $this->assertSame($abstract_final_modifier, $abstract_final_modifier->setAbstract(true));
        $this->assertSame($abstract_final_modifier, $abstract_final_modifier->setFinal(true));
        $this->assertTrue($abstract_final_modifier->isFinal());
        $this->assertFalse($abstract_final_modifier->isAbstract());
        $abstract_final_modifier->setAbstract(false);
        $this->assertTrue($abstract_final_modifier->isFinal());
    }

    public function testFinal()
    {
        /** @var AbstractFinalModifierTrait|\PHPUnit_Framework_MockObject_MockObject $abstract_final_modifier */
        $abstract_final_modifier = $this->getMockForTrait(AbstractFinalModifierTrait::class);
        $this->assertSame($abstract_final_modifier, $abstract_final_modifier->setFinal(true));
        $this->assertSame($abstract_final_modifier, $abstract_final_modifier->setAbstract(true));
        $this->assertTrue($abstract_final_modifier->isAbstract());
        $this->assertFalse($abstract_final_modifier->isFinal());
        $abstract_final_modifier->setFinal(false);
        $this->assertTrue($abstract_final_modifier->isAbstract());
    }
}
