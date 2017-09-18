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
class DefaultCodeFormatter extends CodeFormatter
{

    /** @var DefaultCodeFormatter */
    private static $instance = null;

    /**
     * @return DefaultCodeFormatter
     */
    static public function getInstance()
    {
        if(self::$instance === null) self::$instance = new self();
        return self::$instance;
    }
}