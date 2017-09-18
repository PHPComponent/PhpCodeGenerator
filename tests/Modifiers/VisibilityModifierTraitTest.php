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

use PHPComponent\PhpCodeGenerator\Modifiers\VisibilityModifierTrait;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class VisibilityModifierTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testVisibility()
    {
        /** @var VisibilityModifierTrait|\PHPUnit_Framework_MockObject_MockObject $visibility_modifier */
        $visibility_modifier = $this->getMockForTrait(VisibilityModifierTrait::class);
        $this->assertFalse($visibility_modifier->isPublic());
        $this->assertFalse($visibility_modifier->isProtected());
        $this->assertFalse($visibility_modifier->isPrivate());
        $this->assertSame($visibility_modifier, $visibility_modifier->setPublic());
        $this->assertTrue($visibility_modifier->isPublic());
        $this->assertSame($visibility_modifier, $visibility_modifier->setPrivate());
        $this->assertTrue($visibility_modifier->isPrivate());
        $this->assertSame($visibility_modifier, $visibility_modifier->setProtected());
        $this->assertTrue($visibility_modifier->isProtected());
    }

    /**
     * @param $visibility
     * @dataProvider getValidVisibility
     */
    public function testValidVisibility($visibility)
    {
        /** @var VisibilityModifierTrait|\PHPUnit_Framework_MockObject_MockObject $visibility_modifier */
        $visibility_modifier = $this->getMockForTrait(VisibilityModifierTrait::class);
        $this->assertSame($visibility_modifier, $visibility_modifier->setVisibility($visibility));
        $this->assertSame($visibility, $visibility_modifier->getVisibility());
    }

    public function getValidVisibility()
    {
        return array(
            array('private'),
            array('protected'),
            array('public'),
        );
    }

    /**
     * @param $visibility
     * @dataProvider getInvalidVisibility
     * @expectedException \PHPComponent\PhpCodeGenerator\Exceptions\InvalidVisibilityException
     */
    public function testInvalidVisibility($visibility)
    {
        /** @var VisibilityModifierTrait|\PHPUnit_Framework_MockObject_MockObject $visibility_modifier */
        $visibility_modifier = $this->getMockForTrait(VisibilityModifierTrait::class);
        $visibility_modifier->setVisibility($visibility);
    }

    public function getInvalidVisibility()
    {
        return array(
            array(false),
            array(true),
            array(array()),
            array(0),
            array(1.2),
            array(null),
            array(new \stdClass()),
            array('string'),
            array('protectedd'),
        );
    }
}
