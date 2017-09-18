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
class DocComment
{

    const FORCE_MULTI_LINE = true;
    const NOT_FORCE_MULTI_LINE = false;

    /** @var string */
	private $comment;

    /** @var null|array */
	private $parsed_comment = null;

    /** @var bool */
	private $force_multi_line = false;

    /**
     * DocComment constructor.
     * @param string $comment
     * @param bool $force_multi_line
     */
	public function __construct($comment = '', $force_multi_line = self::NOT_FORCE_MULTI_LINE)
	{
		$this->force_multi_line = $force_multi_line;
		$this->setComment($comment);
	}

    /**
     * @return null|string
     */
	public function getDescription()
	{
		$description = $this->getParam('');
		if(count($description) > 0) return $description[0]['other_content'];
		return null;
	}

    /**
     * @param string $comment
     * @param bool $force_multi_line
     * @return mixed|null|string
     */
	protected function fixDocInputComment($comment, $force_multi_line)
	{
		if($comment == '') return null;
		$comment = trim($comment);
		$match = null;
		if(!preg_match('#^/\*\*#', $comment, $match))
		{
			$comment = '/**'.($force_multi_line ? "\n" : '').' '.$comment;
		}
		if(!preg_match('#\*/$#', $comment, $match))
		{
			$comment = $comment.($force_multi_line ? "\n" : '').' */';
		}

		$comment = preg_replace('#^#m', '*', $comment);
		$comment = preg_replace('#^\*\s*\*#m', ' *', $comment);
		$comment = preg_replace('#^\*(/\*)#i', '$1', $comment);

		return $comment;
	}

    /**
     * @param string $comment
     */
	protected function setComment($comment)
	{
		$this->comment = strval($this->fixDocInputComment($comment, $this->force_multi_line));
		$this->parsed_comment = null;
	}

    /**
     * @return string
     */
	public function getComment()
	{
		if(is_string($this->comment)) return $this->comment;

		$this->comment = '';

		ksort($this->parsed_comment);

		foreach($this->parsed_comment as $param => $items)
		{
			foreach($items as $item)
			{
				if($param == '')
				{
					$this->comment .= ' * '.$this->fixCommentNewLines($item['other_content'])."\n";
				}
				else
				{
					$this->comment .= " * ".'@'.$param.rtrim(' '.$item['first_token']).rtrim(' '.$this->fixCommentNewLines($item['other_content']))."\n";
				}
			}
		}

		$this->comment = trim($this->comment, " *\n\r");

		if($this->comment != '')
		{
			$this->comment = "/**".($this->force_multi_line ? "\n * " : ' ').$this->comment.($this->force_multi_line ? "\n" : '')." */";
		}

		return $this->comment;
	}

    /**
     * @param string $comment
     * @return string
     */
	protected function fixCommentNewLines($comment)
	{
		$comment = str_replace("\n", "\n*     ", $comment);
		return $comment;
	}

    /**
     * @return string
     */
	public function __toString()
	{
		return $this->getComment();
	}

    /**
     * @param string $name
     * @param string $value
     * @param int $order
     */
	public function setParam($name, $value, $order = 0)
	{
		$this->parseDocComment();
		$this->comment = false;
		$name = strtolower($name);

		if(!array_key_exists($name, $this->parsed_comment)) $this->parsed_comment[$name] = array();

		list($first_token, $other_content) = explode(' ', trim($value), 2);

		$this->parsed_comment[$name][$order] = array('first_token' => $first_token, 'other_content' => $other_content);

		ksort($this->parsed_comment[$name]);
	}

    /**
     * @param string $name
     * @return array
     */
	public function getParam($name)
	{
		$name = strtolower($name);
		$this->parseDocComment();
		if(array_key_exists($name, $this->parsed_comment)) return $this->parsed_comment[$name];
		return array();
	}

	protected function parseDocComment()
	{
		if(is_array($this->parsed_comment)) return;

		$comment_string = $this->comment;
		$this->parsed_comment = array();

		$lines = explode("\n", str_replace("\r", '', preg_replace('#\\/\\*(.*)\\*\\/#is', '$1', $comment_string)));
		$last = '';
		foreach($lines as $line)
		{
			if(preg_match('/^\\s*\\*\\s*@([^ ]+)(?:|\\s+([^\\s]+)(?:\\s+(.*)|))$/im', $line, $res))
			{
				$name = strtolower($res[1]);
				if(!array_key_exists($name, $this->parsed_comment)) $this->parsed_comment[$name] = array();
				$this->parsed_comment[$name][] = array('first_token' => $res[2], 'other_content' => array_key_exists(3, $res) ? trim($res[3]) : '');
				$last = $name;
			}
			else
			{
				$value = trim(preg_replace('/^\\s*\\*\\s*/', '', $line));
				if($last == '' && !array_key_exists($last, $this->parsed_comment))
				{
					if($value != '') $this->parsed_comment[$last] = array(array('other_content' => $value));
				}
				else
				{
					if($value != '') $this->parsed_comment[$last][count($this->parsed_comment[$last]) - 1]['other_content'] .= "\n".$value;
				}
			}
		}
		if(array_key_exists('param', $this->parsed_comment))
		{
			foreach($this->parsed_comment['param'] as &$param)
			{
				if(preg_match('#^([^\s]+|)\s*(\$[^\s]+|)\s*(.*?|)$#s', $param['first_token'].' '.$param['other_content'], $match))
				{
					$param['type'] = $match[1];
					$param['param_name'] = ltrim($match[2], '$');
					$param['description'] = trim($match[3]);
				}
			}
		}
	}
}
