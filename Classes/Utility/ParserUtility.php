<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Utility;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Core\SingletonInterface;

/**
 * @package Featdd\DpnGlossary\Utility
 */
class ParserUtility implements SingletonInterface
{
    public const DEFAULT_TAG = 'DPNGLOSSARY';

    /**
     * Protect inline JavaScript from DOM Manipulation with HTML comments
     * Optional you can pass over a alternative comment tag
     *
     * @param string $html
     * @param string $tag
     * @return string
     */
    public static function protectScrtiptsAndCommentsFromDOM(string $html, string $tag = self::DEFAULT_TAG): string
    {
        $callback = function (array $match) use ($tag) {
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
    public static function protectScriptsAndCommentsFromDOMReverse(string $html, string $tag = self::DEFAULT_TAG): string
    {
        $callback = function (array $match) {
            return base64_decode($match[2]);
        };

        return preg_replace_callback(
            '#(<!--' . preg_quote($tag, '#') . ')(.*?)(-->)#is',
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
    public static function protectLinkAndSrcPathsFromDOM(string $html, string $tag = self::DEFAULT_TAG): string
    {
        $callback = function (array $match) use ($tag) {
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
    public static function protectLinkAndSrcPathsFromDOMReverse(string $html, string $tag = self::DEFAULT_TAG): string
    {
        $callback = function (array $match) {
            return $match[1] . $match[2] . base64_decode($match[4]) . $match[5];
        };

        return preg_replace_callback(
            '#(href|src)(\=\")(' . preg_quote($tag, '#') . ')(.*?)(\")#is',
            $callback,
            $html
        );
    }

    /**
     * Replaces a DOM Text node
     * with a replacement string
     *
     * @param \DOMText $DOMText
     * @param string $replacement
     * @return void
     */
    public static function domTextReplacer(\DOMText $DOMText, string $replacement): void
    {
        if (false === empty(trim($replacement))) {
            $tempDOM = new \DOMDocument();
            // use XHTML tag for avoiding UTF-8 encoding problems
            $tempDOM->loadHTML('<?xml encoding="UTF-8">' . '<!DOCTYPE html><html><body><div id="replacement">' . $replacement . '</div></body></html>');

            $replacementNode = $DOMText->ownerDocument->createDocumentFragment();

            /** @var \DOMElement $tempDOMChild */
            foreach ($tempDOM->getElementById('replacement')->childNodes as $tempDOMChild) {
                $tempChild = $DOMText->ownerDocument->importNode($tempDOMChild, true);
                $replacementNode->appendChild($tempChild);
            }

            $DOMText->parentNode->replaceChild($replacementNode, $DOMText);
        }
    }

    /**
     * @param string $html
     * @return string
     */
    public static function domHtml5Repairs(string $html): string
    {
        $callback = function (array $match) {
            return $match[1] . str_ireplace('</source>', '', $match['2']) . $match[3];
        };

        return preg_replace_callback('/(<picture.*?>)(.*?)(<\/picture>)/is', $callback, $html);
    }
}
