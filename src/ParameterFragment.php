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

use PHPComponent\PhpCodeGenerator\Exceptions\InvalidTypeHintException;

/**
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class ParameterFragment implements ICodeFragment
{

    /** @var string */
    private $name;

    /** @var string */
    private $default_value;

    /** @var bool */
    private $default_value_set = false;

    /** @var string */
    private $type_hint;

    /**
     * ParameterFragment constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @param CodeFormatter $code_formatter
     * @return string
     */
    public function getCode(CodeFormatter $code_formatter)
    {
        $out = '';
        if($this->getTypeHint() != '') $out .= $this->getTypeHint().' ';
        $out .= '$'.$this->getName();
        if($this->default_value_set)
        {
            $out .= $code_formatter->printAssignment().$code_formatter->printValue($this->getDefaultValue());
        }
        return $out;
    }

    /**
     * @return string
     */
    public function getTypeHint()
    {
        return $this->type_hint;
    }

    /**
     * @param string $type_hint
     * @return $this|ParameterFragment
     */
    public function setTypeHint($type_hint)
    {
        if(is_string($type_hint) && ($type_hint === 'array' || !DataType::isStandardType($type_hint)))
        {
            $this->type_hint = $type_hint;
            return $this;
        }
        throw new InvalidTypeHintException('Argument $type_hint must be valid type hint instead of '.gettype($type_hint));
    }

    /**
     * @param null $value
     * @return $this
     */
    public function setDefaultValue($value)
    {
        $this->default_value = $value;
        $this->default_value_set = true;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getDefaultValue()
    {
        return $this->default_value;
    }

    /**
     * @param string $name
     * @return $this|ParameterFragment
     */
    public function setName($name)
    {
        if(!is_string($name) || ($name = trim($name)) === '') throw new \InvalidArgumentException('Argument $name must be non-empty string instead of '.gettype($name));
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}