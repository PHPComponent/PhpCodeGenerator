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
trait AbstractFinalModifierTrait
{

    use AbstractModifierTrait{
        setAbstract as protected setAbstractOriginal;
    }
    use FinalModifierTrait{
        setFinal as protected setFinalOriginal;
    }

    /**
     * @param bool $abstract
     * @return $this
     */
    public function setAbstract($abstract)
    {
        $this->setAbstractOriginal($abstract);
        if($abstract === true) $this->setFinalOriginal(false);
        return $this;
    }

    /**
     * @param bool $final
     * @return $this
     */
    public function setFinal($final)
    {
        $this->setFinalOriginal($final);
        if($final === true) $this->setAbstractOriginal(false);
        return $this;
    }
}