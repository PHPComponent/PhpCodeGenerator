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
trait FinalModifierTrait
{

    /** @var bool */
    private $final;

    /**
     * @param bool $final
     * @return $this
     */
    public function setFinal($final)
    {
        if(!is_bool($final)) throw new \InvalidArgumentException('Argument $final must be bool instead of '.gettype($final));
        $this->final = $final;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isFinal()
    {
        return $this->final;
    }
}