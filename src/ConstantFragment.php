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
class ConstantFragment implements ICodeFragment
{

    /** @var string */
    private $name;

    /** @var null|string|int|float|bool */
    private $value;

    /** @var string|DocComment */
    private $doc_comment = '';

    /**
     * ConstantFragment constructor.
     * @param string $name
     * @param null|string|int|float|bool $value
     */
    public function __construct($name, $value = null)
    {
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * @param null|string|int|float|bool $value
     * @return $this|ConstantFragment
     */
    public function setValue($value)
    {
        if($value !== null && !is_array($value) && !is_string($value) && !is_float($value) && !is_bool($value) && !is_int($value))
            throw new \InvalidArgumentException('Argument $value must be null, int, float, string, bool or array instead of '.gettype($value));
        $this->value = $value;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this|ConstantFragment
     */
    public function setDocComment($value)
    {
        $this->doc_comment = new DocComment($value, false);
        return $this;
    }

    /**
     * @return DocComment
     */
    public function getDocComment()
    {
        if(!$this->doc_comment instanceof DocComment) $this->doc_comment = new DocComment('', false);
        return $this->doc_comment;
    }

    /**
     * @param string $name
     */
    private function setName($name)
    {
        if(!is_string($name) || ($name = trim($name)) === '') throw new \InvalidArgumentException('Argument $name must be non-empty string instead of '.gettype($name));
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param CodeFormatter $code_formatter
     * @return string
     */
    public function getCode(CodeFormatter $code_formatter)
    {
        $out = '';
        if($this->doc_comment != '')
        {
            $out .= $code_formatter->printDocComment($this->doc_comment);
        }
        $out .= $code_formatter->getCurrentIndentString().'const '.$this->getName().$code_formatter->printAssignment().$code_formatter->printValue($this->getValue()).$code_formatter->printStatementEnd();
        return $out;
    }
}