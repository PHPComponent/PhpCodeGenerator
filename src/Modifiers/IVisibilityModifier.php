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

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
interface IVisibilityModifier
{

    const PUBLIC_VISIBILITY = 'public';
    const PROTECTED_VISIBILITY = 'protected';
    const PRIVATE_VISIBILITY = 'private';

    /**
     * @return string
     */
    public function getVisibility();

    /**
     * @param string $visibility
     */
    public function setVisibility($visibility);

    /**
     * @return $this
     */
    public function setPublic();

    /**
     * @return $this
     */
    public function setProtected();

    /**
     * @return $this
     */
    public function setPrivate();

    /**
     * @return bool
     */
    public function isPublic();

    /**
     * @return bool
     */
    public function isProtected();

    /**
     * @return bool
     */
    public function isPrivate();
}