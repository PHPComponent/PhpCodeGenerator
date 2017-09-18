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

use PHPComponent\PhpCodeGenerator\Exceptions\NamespaceAliasAlreadyExistsException;

/**
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class NamespaceFragment implements ICodeFragment
{

    /** @var string */
    private $namespace;

    /** @var array */
    private $uses = array();

    /** @var ClassFragment[] */
    private $classes = array();

    /** @var bool */
    private $bracketed_syntax = false;

    /**
     * NamespaceFragment constructor.
     * @param string $namespace
     */
    public function __construct($namespace)
    {
        $this->setNamespace($namespace);
    }

    /**
     * @param ClassFragment $class
     * @return $this|NamespaceFragment
     */
    public function addClass(ClassFragment $class)
    {
        $this->classes[$class->getName()] = $class;
        return $this;
    }

    /**
     * @return ClassFragment[]
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param string $namespace
     * @param null|string $alias
     * @return $this|NamespaceFragment
     */
    public function addUse($namespace, $alias = null)
    {
        if($alias === null) $alias = CodeTools::extractNamespaceShortName($namespace);

        if(!is_string($alias)) throw new \InvalidArgumentException('Argument $alias must be string instead of '.gettype($alias));
        array_walk($this->uses, function($existing_namespace, $existing_alias) use($alias)
        {
            if(strtolower($existing_alias) == strtolower($alias)) throw new NamespaceAliasAlreadyExistsException($alias, $existing_namespace);
        });

        $this->uses[$alias] = $namespace;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getUses()
    {
        return $this->uses;
    }

    /**
     * @param boolean $bracketed_syntax
     * @return $this|NamespaceFragment
     */
    public function setBracketedSyntax($bracketed_syntax)
    {
        if(!is_bool($bracketed_syntax)) throw new \InvalidArgumentException('Argument $bracketed_syntax must be bool instead of '.gettype($bracketed_syntax));
        $this->bracketed_syntax = $bracketed_syntax;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isBracketedSyntax()
    {
        return $this->bracketed_syntax;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        if(!is_string($namespace)) throw new \InvalidArgumentException('Argument $namespace must be string instead of '.gettype($namespace));
        $this->namespace = trim($namespace);
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param CodeFormatter $code_formatter
     * @return string
     */
    public function getCode(CodeFormatter $code_formatter)
    {
        $body = '';
        foreach($this->uses as $alias => $namespace)
        {
            $body .= 'use '.$namespace.($alias != CodeTools::extractNamespaceShortName($namespace) ? ' as '.$alias : '').$code_formatter->printStatementEnd();
        }

        $body .= implode("\n", array_map(function(ClassFragment $class) use($code_formatter)
        {
            return $class->getCode($code_formatter);
        }, $this->getClasses()));

        if($this->isBracketedSyntax())
        {
            $code = 'namespace ';
            $code .= $this->namespace === '' ? '' : $this->namespace;
            $code .= $code_formatter->printOpeningCurlyBracket();
            $code .= $body;
            $code .= $code_formatter->printClosingCurlyBracket();
        }
        else
        {
            $code = $this->namespace === '' ? '' : 'namespace '.$this->namespace;
            $code .= $code_formatter->printStatementEnd();
            $code .= $body;
        }
        return $code;
    }
}