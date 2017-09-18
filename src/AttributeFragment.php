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

use PHPComponent\PhpCodeGenerator\Modifiers\IStaticModifier;
use PHPComponent\PhpCodeGenerator\Modifiers\IVisibilityModifier;
use PHPComponent\PhpCodeGenerator\Modifiers\StaticModifierTrait;
use PHPComponent\PhpCodeGenerator\Modifiers\VisibilityModifierTrait;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class AttributeFragment implements ICodeFragment, IVisibilityModifier, IStaticModifier
{

    use VisibilityModifierTrait;
    use StaticModifierTrait;

    /** @var string */
    private $name;

    /** @var null|string */
    private $default_value;

    /** @var string */
    private $doc_comment = '';

    /**
     * AttributeFragment constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->setPrivate();
        $this->setName($name);
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setDefaultValue($value)
    {
        $this->default_value = $value;
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
     * @param string $value
     * @return $this
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
        $modifiers = $this->getVisibility().' '.($this->isStatic() ? 'static ' : '');

        $out .= $code_formatter->getCurrentIndentString().trim($modifiers).' $'.$this->name;
        if($this->default_value !== null)
        {
            $out .= $code_formatter->printAssignment().$code_formatter->printValue($this->default_value);
        }
        return $out.$code_formatter->printStatementEnd();
    }
}


