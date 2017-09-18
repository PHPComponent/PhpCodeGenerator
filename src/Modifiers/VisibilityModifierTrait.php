<?php
/*
 * This file is part of PHPComponent/PhpCodeGenerator.
 *
 * Copyright (c) 2016 František Šitner <frantisek.sitner@gmail.com>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace PHPComponent\PhpCodeGenerator\Modifiers;

use PHPComponent\PhpCodeGenerator\Exceptions\InvalidVisibilityException;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
trait VisibilityModifierTrait
{

    /** @var string public|protected|private */
    private $visibility;

    /**
     * @return string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param string $visibility
     * @return $this
     */
    public function setVisibility($visibility)
    {
        $visibility_keywords = array(IVisibilityModifier::PUBLIC_VISIBILITY, IVisibilityModifier::PROTECTED_VISIBILITY, IVisibilityModifier::PRIVATE_VISIBILITY);
        if(is_string($visibility) && in_array($visibility, $visibility_keywords, true))
        {
            $this->visibility = $visibility;
            return $this;
        }
        throw new InvalidVisibilityException('Argument $visibility must be one of '.implode(', ', $visibility_keywords).' instead of '.gettype($visibility));
    }

    /**
     * @return $this
     */
    public function setPublic()
    {
        $this->visibility = IVisibilityModifier::PUBLIC_VISIBILITY;
        return $this;
    }

    /**
     * @return $this
     */
    public function setProtected()
    {
        $this->visibility = IVisibilityModifier::PROTECTED_VISIBILITY;
        return $this;
    }

    /**
     * @return $this
     */
    public function setPrivate()
    {
        $this->visibility = IVisibilityModifier::PRIVATE_VISIBILITY;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->visibility === IVisibilityModifier::PUBLIC_VISIBILITY;
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return $this->visibility === IVisibilityModifier::PROTECTED_VISIBILITY;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->visibility === IVisibilityModifier::PRIVATE_VISIBILITY;
    }
}