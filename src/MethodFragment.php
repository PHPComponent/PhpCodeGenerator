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

use PHPComponent\PhpCodeGenerator\Modifiers\AbstractFinalModifierTrait;
use PHPComponent\PhpCodeGenerator\Modifiers\IAbstractModifier;
use PHPComponent\PhpCodeGenerator\Modifiers\IFinalModifier;
use PHPComponent\PhpCodeGenerator\Modifiers\IStaticModifier;
use PHPComponent\PhpCodeGenerator\Modifiers\IVisibilityModifier;
use PHPComponent\PhpCodeGenerator\Modifiers\StaticModifierTrait;
use PHPComponent\PhpCodeGenerator\Modifiers\VisibilityModifierTrait;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class MethodFragment implements ICodeFragment, IVisibilityModifier, IAbstractModifier, IFinalModifier, IStaticModifier
{

    use VisibilityModifierTrait;
    use AbstractFinalModifierTrait;
    use StaticModifierTrait;

    /** @var string */
    private $name;

    /** @var ParameterFragment[] */
    private $params = array();

    /** @var DocComment */
    private $doc_comment;

    /** @var string */
    private $body = '';

    /** @var bool */
    private $without_body = false;

    /**
     * MethodFragment constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
        $this->setPublic();
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        if(!is_string($name) || ($name = trim($name)) === '') throw new \InvalidArgumentException('Argument $name must be non-empty string instead of '.gettype($name));
        $this->name = $name;
        return $this;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDocComment($value)
    {
        $this->doc_comment = new DocComment($value, true);
        return $this;
    }

    /**
     * @return DocComment
     */
    public function getDocComment()
    {
        if (!$this->doc_comment instanceof DocComment) $this->doc_comment = new DocComment('', true);
        return $this->doc_comment;
    }

    /**
     * @param CodeFormatter $code_formatter
     * @return string
     */
    public function getCode(CodeFormatter $code_formatter)
    {
        $out = '';
        $out .= $code_formatter->getLineEnd();
        if ($this->doc_comment != '')
        {
            $out .= $code_formatter->printDocComment($this->doc_comment);
        }

        $modifiers = $this->getVisibility().' '
            .($this->isAbstract() ? 'abstract ' : '')
            .($this->isFinal() ? 'final ' : '')
            .($this->isStatic() ? 'static ' : '');

        $out .= $code_formatter->getCurrentIndentString().$modifiers;
        $out .= 'function '.$this->name.'('.$this->getParamsCode($code_formatter).')';
        if ($this->isAbstract() || $this->isWithoutBody())
        {
            $out .= $code_formatter->printStatementEnd();
        }
        else
        {
            $out .= $code_formatter->printOpeningCurlyBracket()
                .$code_formatter->printCodeBlock($this->getBody())
                .$code_formatter->printClosingCurlyBracket();
        }
        return $out;
    }

    /**
     * @param CodeFormatter $code_formatter
     * @return string
     */
    protected function getParamsCode(CodeFormatter $code_formatter)
    {
        $params_codes = array();
        foreach($this->params as $param)
        {
            $params_codes[] = $param->getCode($code_formatter);
        }
        return implode(', ', $params_codes);
    }

    /**
     * @param string $body
     * @return $this|MethodFragment
     */
    public function setBody($body)
    {
        if(!is_string($body)) throw new \InvalidArgumentException('Argument $body must be string instead of '.gettype($body));
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param bool $without_body
     * @return $this|MethodFragment
     */
    public function setWithoutBody($without_body)
    {
        if(!is_bool($without_body)) throw new \InvalidArgumentException('Argument $without_body must be bool instead of '.gettype($without_body));
        $this->without_body = $without_body;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWithoutBody()
    {
        return $this->without_body;
    }

    /**
     * @param ParameterFragment $parameter
     * @return $this|MethodFragment
     */
    public function addParameter(ParameterFragment $parameter)
    {
        $this->params[] = $parameter;
        return $this;
    }

    /**
     * @return ParameterFragment[]
     */
    public function getParameters()
    {
        return $this->params;
    }
}