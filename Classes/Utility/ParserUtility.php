<?php
namespace Featdd\DpnGlossary\Utility;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @package DpnGlossary
 * @subpackage Utility
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
    public static function protectScrtiptsAndCommentsFromDOM($html, $tag = self::DEFAULT_TAG): string
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
    public static function protectScriptsAndCommentsFromDOMReverse($html, $tag = self::DEFAULT_TAG): string
    {
        $callback = function ($match) {
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
    public static function protectLinkAndSrcPathsFromDOM($html, $tag = self::DEFAULT_TAG): string
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
    public static function protectLinkAndSrcPathsFromDOMReverse($html, $tag = self::DEFAULT_TAG): string
    {
        $callback = function ($match) {
            return $match[1] . $match[2] . base64_decode($match[4]) . $match[5];
        };

        return preg_replace_callback(
            '#(href|src)(\=\")(' . preg_quote($tag, '#') . ')(.*?)(\")#is',
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
    public static function getAndSetInnerTagContent($html, callable $contentCallback, callable $wrapperCallback): string
    {
        $regexCallback = function ($match) use ($contentCallback, $wrapperCallback) {
            return '<' . $match[1] . $match[2] . '>' . $contentCallback($match[3], $wrapperCallback) . $match[4];
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
    public static function domTextReplacer(\DOMText $DOMText, $replacement): void
    {
        //class HTML5DOMDocument
        $extPath = ExtensionManagementUtility::extPath('dpn_glossary');
        require_once($extPath . 'Resources/Private/Libraries/html5DomDocument/autoload.php');
        
        if (false === empty(trim($replacement))) {
            $tempDOM = new \IvoPetkov\HTML5DOMDocument();;
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
}
