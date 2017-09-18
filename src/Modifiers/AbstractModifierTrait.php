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
trait AbstractModifierTrait
{

    /** @var bool */
    private $abstract;

    /**
     * @param bool $abstract
     * @return $this
     */
    public function setAbstract($abstract)
    {
        if(!is_bool($abstract)) throw new \InvalidArgumentException('Argument $abstract must be bool instead of '.gettype($abstract));
        $this->abstract = $abstract;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAbstract()
    {
        return $this->abstract;
    }
}