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
trait StaticModifierTrait
{

    /** @var bool */
    private $static;

    /**
     * @param bool $static
     * @return $this
     */
    public function setStatic($static)
    {
        if(!is_bool($static)) throw new \InvalidArgumentException('Argument $static must be bool instead of '.gettype($static));
        $this->static = $static;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->static;
    }
}