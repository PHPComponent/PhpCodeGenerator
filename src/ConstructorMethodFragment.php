<?php
/*
 * This file is part of PHPComponent/PhpCodeGenerator.
 *
 * Copyright (c) 2016 František Šitner <frantisek.sitner@gmail.com>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace PHPComponent\PhpCodeGenerator;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class ConstructorMethodFragment extends MethodFragment
{

    const NAME = '__constructor';

    /**
     * ConstructorMethodFragment constructor.
     */
    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    /**
     * @param string $name
     * @return $this|ConstructorMethodFragment
     */
    public function setName($name)
    {
        if($name === self::NAME) return parent::setName($name);
        throw new \LogicException('Name of constructor cannot be changed');
    }
}