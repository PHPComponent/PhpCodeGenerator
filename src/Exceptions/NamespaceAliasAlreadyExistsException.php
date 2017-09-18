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
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class NamespaceAliasAlreadyExistsException extends \InvalidArgumentException
{

    /** @var string */
    private $namespace_alias;

    /** @var string */
    private $existing_namespace;

    /**
     * AttributeAlreadyExistsException constructor.
     * @param string $namespace_alias
     * @param string $existing_namespace
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($namespace_alias, $existing_namespace, $message = '', $code = 0, \Exception $previous = null)
    {
        $this->namespace_alias = $namespace_alias;
        $this->existing_namespace = $existing_namespace;
        if($message === '') $message = 'Namespace alias '.$namespace_alias.' already used for namespace '.$existing_namespace;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getNamespaceAlias()
    {
        return $this->namespace_alias;
    }

    /**
     * @return string
     */
    public function getExistingNamespace()
    {
        return $this->existing_namespace;
    }
}