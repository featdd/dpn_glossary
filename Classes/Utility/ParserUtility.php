<?php
namespace Dpn\DpnGlossary\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Daniel Dorndorf <dorndorf@dreipunktnull.com>, dreipunktnull
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\SingletonInterface;

/**
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ParserUtility implements SingletonInterface {

	/**
	 * Protect inline JavaScript from DOM Manipulation with HTML comments
	 * Optional you can pass over a alternative comment tag
	 *
	 * @param string $html
	 * @param string $tag
	 * @return string
	 */
	public static function protectInlineJSFromDOM($html, $tag = 'DPNGLOSSARY') {
		$callback = function($match) use ($tag) {
			return '<!--' . $tag . $match[1] . $match[2] . $match[3] . '-->';
		};

		return preg_replace_callback('#(<script[^>]*>)(.*?)(<\/script>)#is', $callback, $html);
	}

	/**
	 * Reverse inline JavaScript protection
	 *
	 * @param string $html
	 * @param string $tag
	 * @return string
	 */
	public static function protectInlineJSFromDOMReverse($html, $tag = 'DPNGLOSSARY') {
		$callback = function($match) {
			return $match[2];
		};

		return preg_replace_callback('#(<!--' . preg_quote($tag) . ')(.*?)(-->)#is', $callback, $html);
	}

	/**
	 * Extracts and replaces the
	 * inner content of the html tag
	 *
	 * @param string $html
	 * @param callable $callback receives the inner tag contents and has to return the parsed content
	 * @return string
	 */
	public static function getAndSetInnerTagContent($html, $callback) {
		$regexCallback = function($match) use ($callback) {
			return '<' . $match[1] . $match[2] . '>' . call_user_func($callback, $match[3]) . $match[4];
		};

		return preg_replace_callback('#^<([\w]+)([^>]*)>(.*?)(<\/\1>)$#is', $regexCallback, $html);
	}

	/**
	 * Extract the DOMNodes html and
	 * replace it with the parsed html
	 * injected in a temp DOMDocument
	 *
	 * @param \DOMNode $DOMNode
	 * @param string $replacement
	 * @return void
	 */
	public static function domNodeContentReplacer(\DOMNode $DOMNode, $replacement) {
		$tempDOM = new \DOMDocument();
		// use XHTML tag for avoiding UTF-8 encoding problems
		$tempDOM->loadHTML(
			'<?xml encoding="UTF-8">' .
			$replacement
		);
		// Replaces the original Node with the
		// new node containing the parsed content
		$DOMNode->parentNode->replaceChild(
			$DOMNode
				->ownerDocument
				->importNode(
					$tempDOM
						->getElementsByTagName('body')
						->item(0)
						->childNodes
						->item(0),
					TRUE
				),
			$DOMNode
		);
	}
}
