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
 * @author František Šitner <frantisek.sitner@gmail.com>
 */
class PhpCodeFragment implements ICodeFragment
{

    /** @var ICodeFragment[] */
    private $code_fragments = array();

    /**
     * @param ICodeFragment $code_fragment
     * @return $this|PhpCodeFragment
     */
    public function addCodeFragment(ICodeFragment $code_fragment)
    {
        $this->code_fragments[] = $code_fragment;
        return $this;
    }

    /**
     * @return ICodeFragment[]
     */
    public function getCodeFragments()
    {
        return $this->code_fragments;
    }

    /**
     * @param CodeFormatter $code_formatter
     * @return string
     */
    public function getCode(CodeFormatter $code_formatter)
    {
        $code = '<?php'.$code_formatter->getLineEndAfterPhpOpeningTag();
        foreach($this->getCodeFragments() as $code_fragment)
        {
            $code .= $code_fragment->getCode($code_formatter);
        }
        return $code;
    }
}