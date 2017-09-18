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
abstract class CodeFormatter
{

    /** @var int */
    protected $current_indent = 0;

    /**
     * @return $this|CodeFormatter
     */
    public function increaseIndent()
    {
        $this->current_indent++;
        return $this;
    }

    /**
     * @return $this|CodeFormatter
     */
    public function decreaseIndent()
    {
        if($this->current_indent <= 0) throw new \LogicException('Indent cannot be decreased, it is already 0');
        $this->current_indent--;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentIndentString()
    {
        return $this->getIndentString($this->current_indent);
    }

    /**
     * @param int $indent
     * @return string
     */
    public function getIndentString($indent)
    {
        return str_repeat('    ', $indent);
    }

    /**
     * @return string
     */
    public function getLineEnd()
    {
        return "\n";
    }

    /**
     * @return string
     */
    public function getLineEndBeforeCurlyBracket()
    {
        return $this->getLineEnd().$this->getCurrentIndentString();
    }

    /**
     * @return string
     */
    public function getLineEndAfterCurlyBracket()
    {
        return $this->getLineEnd();
    }

    /**
     * @return string
     */
    public function getLineEndAfterPhpOpeningTag()
    {
        return $this->getLineEnd().$this->getLineEnd();
    }

    /**
     * @return string
     */
    public function printOpeningCurlyBracket()
    {
        $out = $this->getLineEndBeforeCurlyBracket().'{'.$this->getLineEndAfterCurlyBracket();
        $this->increaseIndent();
        return $out;
    }

    /**
     * @return string
     */
    public function printClosingCurlyBracket()
    {
        $this->decreaseIndent();
        return $this->getLineEndBeforeCurlyBracket().'}'.$this->getLineEndAfterCurlyBracket();
    }

    /**
     * @param mixed $value
     * @param bool $is_callable
     * @param bool $is_already_quoted
     * @return string
     */
    public function printValue($value, $is_callable = false, $is_already_quoted = false)
    {
        if(is_array($value))
        {
            $value_printable = 'array(';
            $first = true;
            $this->increaseIndent();
            foreach($value as $key => $item)
            {
                if($is_callable)
                {
                    $value_printable .= ($first) ? trim($item, '\'\"').', ' : $this->printValue($item, false, preg_match('#^[\'\"].+[\'\"]$#', $item) === 1);
                    $first = false;
                }
                else
                {
                    if($first) $value_printable .= $this->getLineEnd();
                    $value_printable .= $this->getCurrentIndentString();
                    $value_printable .= (is_int($key) ? $key : '\''.$key.'\'').' => '.$this->printValue($item, false, is_string($item) && preg_match('#^[\'\"].+[\'\"]$#', $item) === 1).','.$this->getLineEnd();
                    $first = false;
                }
            }
            $this->decreaseIndent();
            $value_printable .= $this->getCurrentIndentString().')';
            return $value_printable;
        }
        if(is_string($value)) return !$is_already_quoted ? '\''.$value.'\'' : $value;
        if($value === null) return 'null';
        if($value === true) return 'true';
        if($value === false) return 'false';
        return $value;
    }

    /**
     * @return string
     */
    public function printStatementEnd()
    {
        return ';'.$this->getLineEnd();
    }

    /**
     * @return string
     */
    public function printAssignment()
    {
        return ' = ';
    }

    /**
     * @param DocComment|string $doc_comment
     * @return string
     */
    public function printDocComment($doc_comment)
    {
        $doc_comment = preg_replace('#^\s*\*#m', $this->getCurrentIndentString().' *', trim($doc_comment));
        return $this->getCurrentIndentString().$doc_comment.$this->getLineEnd();
    }

    /**
     * @param string $code_block
     * @return string
     */
    public function printCodeBlock($code_block)
    {
        $tokens = token_get_all('<?php '.$code_block);

        $out = $this->getCurrentIndentString();

        foreach($tokens as $token)
        {
            if(is_array($token))
            {
                switch($token[0])
                {
                    case T_OPEN_TAG:
                        break;
                    case T_WHITESPACE:
                        //$out = preg_replace('#\s+$#s',' ',$out).preg_replace("#\n\s*#", "\n".$this->getCurrentIndentString(), trim($token[1],' '));
                        $out .= preg_match('#\s$#s', $out) ? '' : ' ';
                        break;
                    default:
                        $out .= $token[1];
                }
            }
            else
            {
                switch($token)
                {
                    case "\n":
                        $out .= "\n".$this->getCurrentIndentString();
                        break;
                    case '{':
                        $out = rtrim($out).$this->printOpeningCurlyBracket().$this->getCurrentIndentString();
                        break;
                    case '}':
                        $out = preg_replace('#\s+$#s', '', $out).$this->printClosingCurlyBracket().$this->getCurrentIndentString();
                        break;
                    case ';':
                        $out .= $this->printSemicolon();
                        break;
                    default:
                        $out .= $token;
                }
            }
        }

        return rtrim($out);
    }

    /**
     * @return string
     */
    private function printSemicolon()
    {
        return ";\n".$this->getCurrentIndentString();
    }
}
 