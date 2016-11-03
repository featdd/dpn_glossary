<?php
namespace Featdd\DpnGlossary\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Daniel Dorndorf <dorndorf@featdd.de>
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
class ParserUtility implements SingletonInterface
{
    const DEFAULT_TAG = 'DPNGLOSSARY';

    /**
     * Protect inline JavaScript from DOM Manipulation with HTML comments
     * Optional you can pass over a alternative comment tag
     *
     * @param string $html
     * @param string $tag
     * @return string
     */
    public static function protectScrtiptsAndCommentsFromDOM($html, $tag = self::DEFAULT_TAG)
    {
        $callback = function ($match) use ($tag) {
            return '<!--' . $tag . base64_encode($match[1] . $match[2] . $match[3]) . '-->';
        };

        return preg_replace_callback(
            '#(<script[^>]*>)(.*?)(<\/script>)#is',
            $callback,
            preg_replace_callback(
                '#(<!--\[[^<]*>|<!--)(.*?)(<!\[[^<]*>|-->)#s',
                $callback,
                $html
            )
        );
    }

    /**
     * Reverse inline JavaScript protection
     *
     * @param string $html
     * @param string $tag
     * @return string
     */
    public static function protectScriptsAndCommentsFromDOMReverse($html, $tag = self::DEFAULT_TAG)
    {
        $callback = function ($match) {
            return base64_decode($match[2]);
        };

        return preg_replace_callback(
            '#(<!--' . preg_quote($tag) . ')(.*?)(-->)#is',
            $callback,
            $html
        );
    }

    /**
     * Protect link and src attribute paths to be altered by dom
     *
     * @param string $html
     * @param string $tag
     * @return string
     */
    public static function protectLinkAndSrcPathsFromDOM($html, $tag = self::DEFAULT_TAG)
    {
        $callback = function ($match) use ($tag) {
            return $match[1] . $match[2] . $tag . base64_encode($match[3]) . $match[4];
        };

        return preg_replace_callback(
            '#(href|src)(\=\")(.*?)(\")#is',
            $callback,
            $html
        );
    }

    /**
     * Reverse link and src paths protection
     *
     * @param string $html
     * @param string $tag
     * @return string
     */
    public static function protectLinkAndSrcPathsFromDOMReverse($html, $tag = self::DEFAULT_TAG)
    {
        $callback = function ($match) {
            return $match[1] . $match[2] . base64_decode($match[4]) . $match[5];
        };

        return preg_replace_callback(
            '#(href|src)(\=\")(' . preg_quote($tag) . ')(.*?)(\")#is',
            $callback,
            $html
        );
    }

    /**
     * Extracts and replaces the
     * inner content of the html tag
     *
     * @param string $html
     * @param callable $contentCallback receives the inner tag contents and has to return the parsed content
     * @param callable $wrapperCallback the function used by the content callback to wrap parsed terms
     * @return string
     */
    public static function getAndSetInnerTagContent($html, $contentCallback, $wrapperCallback)
    {
        $regexCallback = function ($match) use ($contentCallback, $wrapperCallback) {
            return '<' . $match[1] . $match[2] . '>' . call_user_func(
                $contentCallback,
                $match[3],
                $wrapperCallback
            ) . $match[4];
        };

        return preg_replace_callback('#^<([\w]+)([^>]*)>(.*?)(<\/\1>)$#is', $regexCallback, $html);
    }

    /**
     * Replaces a DOM Text node
     * with a replacement string
     *
     * @param \DOMText $DOMText
     * @param string $replacement
     * @return void
     */
    public static function domTextReplacer(\DOMText $DOMText, $replacement)
    {
        if (false === empty(trim($replacement))) {
            $tempDOM = new \DOMDocument();
            // use XHTML tag for avoiding UTF-8 encoding problems
            $tempDOM->loadHTML('<?xml encoding="UTF-8">' . '<div id="replacement">' . $replacement . '</div>');
            // Reload the save html to parse definitely the DOM
            $saveHtml = $tempDOM->saveHTML();
            $tempDOM->loadHTML($saveHtml);

            $replacementNode = $DOMText->ownerDocument->createDocumentFragment();

            /** @var \DOMElement $tempDOMChild */
            foreach ($tempDOM->getElementById('replacement')->childNodes as $tempDOMChild) {
                $tempChild = $DOMText->ownerDocument->importNode($tempDOMChild);
                $tempChild->nodeValue = $tempDOMChild->nodeValue;
                $replacementNode->appendChild($tempChild);
            }

            $DOMText->parentNode->replaceChild($replacementNode, $DOMText);
        }
    }
}
