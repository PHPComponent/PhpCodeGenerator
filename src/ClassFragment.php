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

use PHPComponent\PhpCodeGenerator\Exceptions\AttributeAlreadyExistsException;
use PHPComponent\PhpCodeGenerator\Exceptions\ConstantAlreadyExistsException;
use PHPComponent\PhpCodeGenerator\Exceptions\MethodAlreadyExistsException;
use PHPComponent\PhpCodeGenerator\Modifiers\AbstractFinalModifierTrait;
use PHPComponent\PhpCodeGenerator\Modifiers\IAbstractModifier;
use PHPComponent\PhpCodeGenerator\Modifiers\IFinalModifier;

/**
 * @author Martin Legát <mlegat@centrum.cz>
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class ClassFragment implements ICodeFragment, IAbstractModifier, IFinalModifier
{

    use AbstractFinalModifierTrait;

    const TYPE_CLASS = 'class';
    const TYPE_INTERFACE = 'interface';
    const TYPE_TRAIT = 'trait';

    /** @var string */
    private $name;

    /** @var string */
    private $type = self::TYPE_CLASS;

    /** @var AttributeFragment[] */
    private $attributes = array();

    /** @var ConstantFragment[] */
    private $constants = array();

    /** @var MethodFragment[] */
    private $methods = array();

    /** @var null|DocComment */
    private $doc_comment;

    /** @var null|string */
    private $extends;

    /** @var string[] */
    private $implements = array();

    /**
     * ClassFragment constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @param string $name
     * @return $this|ClassFragment
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
     * @param MethodFragment $method
     * @return MethodFragment
     * @throws MethodAlreadyExistsException
     */
    public function addMethod(MethodFragment $method)
    {
        $method_name = $method->getName();
        if($this->hasMethod($method_name)) throw new MethodAlreadyExistsException($method_name);
        $this->methods[strtolower($method_name)] = $method;
        return $method;
    }

    /**
     * @param string $method_name
     * @return null|MethodFragment
     */
    public function getMethod($method_name)
    {
        $method_name = strtolower($method_name);
        if(array_key_exists($method_name, $this->methods))
        {
            return $this->methods[$method_name];
        }
        return null;
    }

    /**
     * @param string $method_name
     * @param MethodFragment $method
     * @return bool
     */
    public function tryGetMethod($method_name, &$method)
    {
        $method = $this->getMethod($method_name);
        return $method instanceof MethodFragment;
    }

    /**
     * @return MethodFragment[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param string $method_name
     * @return bool
     */
    public function hasMethod($method_name)
    {
        $method_name = strtolower($method_name);
        return array_key_exists($method_name, $this->getMethods());
    }

    /**
     * @param AttributeFragment $attribute
     * @return AttributeFragment
     */
    public function addAttribute(AttributeFragment $attribute)
    {
        $attribute_name = $attribute->getName();
        if($this->hasAttribute($attribute_name)) throw new AttributeAlreadyExistsException($attribute_name);
        $this->attributes[strtolower($attribute_name)] = $attribute;
        return $attribute;
    }

    /**
     * @param string $attribute_name
     * @return null|AttributeFragment
     */
    public function getAttribute($attribute_name)
    {
        $attribute_name = strtolower($attribute_name);
        if(array_key_exists($attribute_name, $this->attributes))
        {
            return $this->attributes[$attribute_name];
        }
        return null;
    }

    /**
     * @param string $attribute_name
     * @param AttributeFragment $attribute
     * @return bool
     */
    public function tryGetAttribute($attribute_name, &$attribute)
    {
        $attribute = $this->getAttribute($attribute_name);
        return $attribute instanceof AttributeFragment;
    }

    /**
     * @return AttributeFragment[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $attribute_name
     * @return bool
     */
    public function hasAttribute($attribute_name)
    {
        $attribute_name = strtolower($attribute_name);
        return array_key_exists($attribute_name, $this->attributes);
    }

    /**
     * @param ConstantFragment $constant
     * @return ConstantFragment
     */
    public function addConstant(ConstantFragment $constant)
    {
        $constant_name = $constant->getName();
        if($this->hasConstant($constant_name)) throw new ConstantAlreadyExistsException($constant_name);
        $this->constants[strtolower($constant_name)] = $constant;
        return $constant;
    }

    /**
     * @param string $constant_name
     * @return null|ConstantFragment
     */
    public function getConstant($constant_name)
    {
        $constant_name = strtolower($constant_name);
        if(array_key_exists($constant_name, $this->constants))
        {
            return $this->constants[$constant_name];
        }
        return null;
    }

    /**
     * @param string $constant_name
     * @param null|ConstantFragment $constant
     * @return bool
     */
    public function tryGetConstant($constant_name, &$constant = null)
    {
        $constant = $this->getConstant($constant_name);
        return $constant instanceof ConstantFragment;
    }

    /**
     * @return ConstantFragment[]
     */
    public function getConstants()
    {
        return $this->constants;
    }

    /**
     * @param string $constant_name
     * @return bool
     */
    public function hasConstant($constant_name)
    {
        $constant_name = strtolower($constant_name);
        return array_key_exists($constant_name, $this->constants);
    }

    /**
     * @param string $value
     * @return $this|ClassFragment
     */
    public function setImplements($value)
    {
        $this->implements = array();
        $args = func_get_args();
        return $this->addImplements($args);
    }

    /**
     * @param null|string $interface_1_name
     * @param null|string $interface_2_name
     * @return $this|ClassFragment
     */
    public function addImplements($interface_1_name = null, $interface_2_name = null)
    {
        $args = func_get_args();
        foreach($args as $arg)
        {
            if(is_array($arg))
            {
                foreach($arg as $one)
                {
                    $this->addImplements($one);
                }
                return $this;
            }

            $this->implements[] = $arg;
        }
        return $this;
    }

    /**
     * @return string[]
     */
    public function getImplements()
    {
        return $this->implements;
    }

    /**
     * @param string $extends
     * @return $this
     */
    public function setExtends($extends)
    {
        if(!is_string($extends) || ($extends = trim($extends)) === '') throw new \InvalidArgumentException('Argument $extends must be non-empty string instead of '.gettype($extends));
        $this->extends = $extends;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getExtends()
    {
        return $this->extends;
    }

    /**
     * @param string $type
     * @return $this|ClassFragment
     */
    public function setType($type)
    {
        if(!is_string($type) || ($type = trim($type)) === '') throw new \InvalidArgumentException('Argument $type must be non-empty string instead of '.gettype($type));
        $available_types = array(self::TYPE_CLASS, self::TYPE_INTERFACE, self::TYPE_TRAIT);
        if(!in_array($type, $available_types, true)) throw new \InvalidArgumentException('Argument $type must be one of '.implode(', ', $available_types).' instead of '.$type);
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
        if(!$this->doc_comment instanceof DocComment) $this->doc_comment = new DocComment('', true);
        return $this->doc_comment;
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

        $out .= $code_formatter->getCurrentIndentString();
        if($this->getType() === self::TYPE_CLASS)
        {
            $out .= ($this->isAbstract() ? 'abstract ' : '').($this->isFinal() ? 'final ' : '');
        }
        $out .= $this->getType().' '.$this->getName();

        if(in_array($this->getType(), array(self::TYPE_CLASS, self::TYPE_INTERFACE)) && $this->extends != '')
        {
            $out .= ' extends '.$this->extends;
        }

        if($this->getType() === self::TYPE_CLASS && count($this->implements) > 0)
        {
            $out .= ' implements '.implode(', ', $this->implements);
        }

        $out .= $code_formatter->printOpeningCurlyBracket();

        if(count($this->constants) > 0)
        {
            foreach($this->constants as $constant)
            {
                $out .= $constant->getCode($code_formatter).$code_formatter->getLineEnd();
            }
            $out .= $code_formatter->getLineEnd();
        }

        if($this->getType() !== self::TYPE_INTERFACE && count($this->attributes) > 0)
        {
            foreach($this->attributes as $attribute)
            {
                $out .= $attribute->getCode($code_formatter).$code_formatter->getLineEnd();
            }
        }

        foreach($this->methods as $method)
        {
            $out .= $method->getCode($code_formatter);
        }

        return $out.$code_formatter->printClosingCurlyBracket();
    }
}