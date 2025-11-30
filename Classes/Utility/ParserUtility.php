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
 *  (c) 2025 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use DOMDocument;
use DOMText;

/**
 * @package Featdd\DpnGlossary\Utility
 */
class ParserUtility
{
    private const TEMP_META_ATTRIBUTE = 'data-dpnglossary-temp-meta';
    private const TEMP_META_TAG = '<meta content="text/html; charset=utf-8" http-equiv="Content-Type" data-dpnglossary-temp-meta>';
    public const DEFAULT_TAG = 'DPNGLOSSARY';
    public const UMLAUT_MATCHING_GROUPS = [
        'ä' => '(Ä|ä)',
        'Ä' => '(Ä|ä)',
        'ö' => '(Ö|ö)',
        'Ö' => '(Ö|ö)',
        'ü' => '(Ü|ü)',
        'Ü' => '(Ü|ü)',
    ];

    /**
     * Protect inline JavaScript from DOM Manipulation with HTML comments
     * Optional you can pass over an alternative comment tag
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
            '#(<script[^>]*>)(.*?)(</script>)#is',
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
            '#(href|src)(=\")(?!data:.+;base64)(.*?)(\")#is',
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
            '#(href|src)(=\")(' . preg_quote($tag, '#') . ')(.*?)(\")#is',
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
    public static function domTextReplacer(DOMText $DOMText, string $replacement): void
    {
        if (false === empty(trim($replacement))) {
            $tempDOM = new DOMDocument();
            // use XHTML tag for avoiding UTF-8 encoding problems
            $tempDOM->loadHTML('<?xml encoding="UTF-8">' . '<!DOCTYPE html><html><head><meta content="text/html; charset=utf-8" http-equiv="Content-Type"></head><body><div id="replacement">' . $replacement . '</div></body></html>');

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

        return preg_replace_callback('#(<picture.*?>)(.*?)(</picture>)#is', $callback, $html);
    }

    /**
     * Injects a temporary UTF-8 meta declaration so DOMDocument keeps umlauts intact
     *
     * @param string $html
     * @return string
     */
    public static function injectTemporaryUtf8MetaTag(string $html): string
    {
        if (strpos($html, self::TEMP_META_ATTRIBUTE) !== false) {
            return $html;
        }

        if (stripos($html, '<head') !== false) {
            return preg_replace(
                '#(<head\b[^>]*>)#i',
                '$1' . self::TEMP_META_TAG,
                $html,
                1
            ) ?? $html;
        }

        if (stripos($html, '<html') !== false) {
            return preg_replace(
                '#(<html\b[^>]*>)#i',
                '$1<head>' . self::TEMP_META_TAG . '</head>',
                $html,
                1
            ) ?? $html;
        }

        return self::TEMP_META_TAG . $html;
    }

    /**
     * Removes the temporary UTF-8 meta declaration after DOMDocument processing
     *
     * @param string $html
     * @return string
     */
    public static function removeTemporaryUtf8MetaTag(string $html): string
    {
        return preg_replace(
            sprintf(
                '#<meta\b[^>]*%s[^>]*>\s*#i',
                preg_quote(self::TEMP_META_ATTRIBUTE, '#')
            ),
            '',
            $html
        ) ?? $html;
    }

    /**
     * @param string $quotedTerm
     * @return string
     */
    public static function replaceTermUmlautsWithMatchingGroups(string $quotedTerm): string
    {
        $replacedQuotedTerm = '';

        foreach (mb_str_split($quotedTerm) as $character) {
            $replacedQuotedTerm .= array_key_exists($character, static::UMLAUT_MATCHING_GROUPS)
                ? static::UMLAUT_MATCHING_GROUPS[$character]
                : $character;
        }

        return $replacedQuotedTerm;
    }
}
