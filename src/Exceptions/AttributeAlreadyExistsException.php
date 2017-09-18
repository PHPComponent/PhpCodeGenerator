<?php
/*
* This file is part of PHPComponent/PhpCodeGenerator.
*
* Copyright (c) 2016 František Šitner <frantisek.sitner@gmail.com>
*
* For the full copyright and license information, please view the "LICENSE.md"
* file that was distributed with this source code.
*/

namespace PHPComponent\PhpCodeGenerator\Exceptions;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class AttributeAlreadyExistsException extends \InvalidArgumentException
{

    /** @var string */
    private $attribute_name;

    /**
     * AttributeAlreadyExistsException constructor.
     * @param string $constant_name
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($constant_name, $message = '', $code = 0, \Exception $previous = null)
    {
        $this->attribute_name = $constant_name;
        if($message === '') $message = 'Attribute '.$constant_name.' already exists';
        parent::__construct($message, $code, $previous);
    }
}