<?php
namespace Featdd\DpnGlossary\Service;

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

use Featdd\DpnGlossary\Domain\Model\Synonym;
use Featdd\DpnGlossary\Domain\Model\Term;
use Featdd\DpnGlossary\Domain\Repository\TermRepository;
use Featdd\DpnGlossary\Utility\ParserUtility;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ParserService implements SingletonInterface
{
    /**
     * tags to be always ignored by parsing
     */
    public static $alwaysIgnoreParentTags = array(
        'a',
        'script',
    );

    /**
     * @var ContentObjectRenderer
     */
    protected $contentObjectRenderer;

    /**
     * @var array
     */
    protected $terms = array();

    /**
     * @var array
     */
    protected $tsConfig = array();

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * Boots up:
     *  - objectManager to get class instances
     *  - configuration manager for ts settings
     *  - contentObjectRenderer for generating links etc.
     *  - termRepository to get the Terms
     *
     * @return ParserService
     */
    public function __construct()
    {
        // Make instance of Object Manager
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        // Get Configuration Manager
        /** @var ConfigurationManager $configurationManager */
        $configurationManager = $objectManager->get(ConfigurationManager::class);
        // Inject Content Object Renderer
        $this->contentObjectRenderer = $objectManager->get(ContentObjectRenderer::class);
        // Get Query Settings
        /** @var QuerySettingsInterface $querySettings */
        $querySettings = $objectManager->get(QuerySettingsInterface::class);
        // Get termRepository
        /** @var TermRepository $termRepository */
        $termRepository = $objectManager->get(TermRepository::class);
        // Get Typoscript Configuration
        $this->tsConfig = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        // Reduce TS config to plugin
        $this->tsConfig = $this->tsConfig['plugin.']['tx_dpnglossary.'];

        if (null !== $this->tsConfig && 0 < count($this->tsConfig)) {
            // Save extension settings without ts dots
            $this->settings = GeneralUtility::removeDotsFromTS($this->tsConfig['settings.']);
            // Set StoragePid in the query settings object
            $querySettings->setStoragePageIds(
                GeneralUtility::trimExplode(
                    ',',
                    $this->tsConfig['persistence.']['storagePid'])
            );
            // Set current language uid
            $querySettings->setLanguageUid($GLOBALS['TSFE']->sys_language_uid);
            // Assign query settings object to repository
            $termRepository->setDefaultQuerySettings($querySettings);
            //Find all terms
            $terms = $terms = $termRepository->findByNameLength();
            //Sort terms with an individual counter for max replacement per page
            /** @var Term $term */
            foreach ($terms as $term) {
                $this->terms[] = array(
                    'term' => $term,
                    'replacements' => (int) $this->settings['maxReplacementPerPage'],
                );
            }
        }
    }

    /**
     * parse html for terms and return the parsed html
     * or false if parsers has to be aborted
     *
     * @param string $html
     * @return string|boolean
     * @throws Exception
     */
    public function pageParser($html)
    {
        // extract Pids which should be parsed
        $parsingPids = GeneralUtility::trimExplode(',', $this->settings['parsingPids']);
        // extract Pids which should NOT be parsed
        $excludePids = GeneralUtility::trimExplode(',', $this->settings['parsingExcludePidList']);
        // Get Tags which content should be parsed
        $tags = GeneralUtility::trimExplode(',', $this->settings['parsingTags']);
        // Remove "a" & "script" from parsingTags if it was added unknowingly
        if (true === in_array(self::$alwaysIgnoreParentTags, $tags, true)) {
            $tags = array_diff($tags, self::$alwaysIgnoreParentTags);
        }

        // Abort parser...
        if (
            // Parser disabled
            true === (boolean) $this->settings['disableParser'] ||
            // Pagetype not 0
            0 !== $GLOBALS['TSFE']->type ||
            // no tags to parse given
            0 === count($tags) ||
            // no terms have been found
            0 === count($this->terms) ||
            // no config is given
            0 === count($this->tsConfig) ||
            (
                // parsingPids doesn't contain 0 and...
                false === in_array('0', $parsingPids, true) &&
                (
                    // page is excluded
                    true === in_array($GLOBALS['TSFE']->id, $excludePids, false) ||
                    // page is not whitelisted
                    false === in_array($GLOBALS['TSFE']->id, $parsingPids, false)
                )
            )
        ) {
            return false;
        }

        // Classes which are not allowed for the parsing tag
        $forbiddenParsingTagClasses = array_filter(
            GeneralUtility::trimExplode(',', $this->settings['forbiddenParsingTagClasses'])
        );

        // Tags which are not allowed as direct parent for a parsingTag
        $forbiddenParentTags = array_filter(GeneralUtility::trimExplode(',', $this->settings['forbiddenParentTags']));

        // Add "a" if unknowingly deleted to prevent errors
        if (false === in_array(self::$alwaysIgnoreParentTags, $forbiddenParentTags, true)) {
            $forbiddenParentTags = array_unique(
                array_merge($forbiddenParentTags, self::$alwaysIgnoreParentTags)
            );
        }

        //Create new DOMDocument
        $DOM = new \DOMDocument();

        // Prevent crashes caused by HTML5 entities with internal errors
        libxml_use_internal_errors(true);

        // Load Page HTML in DOM and check if HTML is valid else abort
        // use XHTML tag for avoiding UTF-8 encoding problems
        if (
            false === $DOM->loadHTML(
                '<?xml encoding="UTF-8">' . ParserUtility::protectLinkAndSrcPathsFromDOM(
                    ParserUtility::protectScrtiptsAndCommentsFromDOM(
                        $html
                    )
                )
            )
        ) {
            throw new Exception('Parsers DOM Document could\'nt load the html');
        }

        // remove unnecessary whitespaces in nodes (no visible whitespace)
        $DOM->preserveWhiteSpace = false;

        // Init DOMXPath with main DOMDocument
        $DOMXPath = new \DOMXPath($DOM);

        /** @var \DOMElement $DOMBody */
        $DOMBody = $DOM->getElementsByTagName('body')->item(0);
        // iterate over tags which are defined to be parsed
        foreach ($tags as $tag) {
            $xpathQuery = '//' . $tag;

            // if classes given add them to xpath query
            if (0 < count($forbiddenParsingTagClasses)) {
                $xpathQuery .= '[not(contains(@class, \'' .
                    implode(
                        '\') or contains(@class, \'',
                        $forbiddenParsingTagClasses
                    ) .
                    '\'))]';
            }

            // extract the tags
            $DOMTags = $DOMXPath->query($xpathQuery, $DOMBody);
            // call the nodereplacer for each node to parse its content
            /** @var \DOMNode $DOMTag */
            foreach ($DOMTags as $DOMTag) {
                // get parent tags from root tree string
                $parentTags = explode(
                    '/',
                    preg_replace(
                        '#\[([^\]]*)\]#',
                        '',
                        substr($DOMTag->parentNode->getNodePath(), 1)
                    )
                );

                // check if element is children of a forbidden parent
                if (false === in_array($parentTags, $forbiddenParentTags, true)) {
                    ParserUtility::domNodeContentReplacer(
                        $DOMTag,
                        ParserUtility::getAndSetInnerTagContent(
                            $DOMTag->ownerDocument->saveHTML($DOMTag),
                            array($this, 'textParser'),
                            array($this, 'termWrapper')
                        )
                    );
                }
            }
        }

        // return the parsed html page and remove XHTML tag which is not needed anymore
        return str_replace(
            '<?xml encoding="UTF-8">',
            '',
            ParserUtility::protectScriptsAndCommentsFromDOMReverse(
                ParserUtility::protectLinkAndSrcPathsFromDOMReverse(
                    $DOM->saveHTML()
                )
            )
        );
    }

    /**
     * Parse the extracted html for terms
     *
     * @param string   $text             the text to be parsed
     * @param callable $wrappingCallback the wrapping function for parsed terms as callback
     * @return string
     */
    public function textParser($text, $wrappingCallback)
    {
        $text = preg_replace('#\x{00a0}#iu', '&nbsp;', $text);
        // Iterate over terms and search matches for each of them
        foreach ($this->terms as $term) {
            /** @var Term $termObject */
            $termObject = clone $term['term'];
            $replacements = &$term['replacements'];

            //Check replacement counter
            if (0 !== $term['replacements']) {
                $this->regexParser($text, $termObject, $replacements, $wrappingCallback);

                if (true === (boolean) $this->settings['parseSynonyms']) {
                    /** @var Synonym $synonym */
                    foreach ($termObject->getSynonyms() as $synonym) {
                        $termObject->setName(
                            $synonym->getName()
                        );

                        if (true === (boolean) $this->settings['maxReplacementPerPageRespectSynonyms']) {
                            $this->regexParser($text, $termObject, $replacements, $wrappingCallback);
                        } else {
                            $noReplacementCount = -1;
                            $this->regexParser($text, $termObject, $noReplacementCount, $wrappingCallback);
                        }
                    }
                }
            }
        }

        return $text;
    }

    /**
     * Regex parser for terms on a text string
     *
     * @param string   $text
     * @param Term     $term
     * @param integer  $replacements
     * @param callable $wrappingCallback
     */
    protected function regexParser(&$text, Term $term, &$replacements, $wrappingCallback)
    {
        /*
         * Regex Explanation:
         * Group 1: (^|[\s\>[:punct:]]|\<br*\>)
         *  ^         = can be begin of the string
         *  \G        = can match an other matchs end
         *  \s        = can have space before term
         *  \>        = can have a > before term (end of some tag)
         *  [:punct:] = can have punctuation characters like .,?!& etc. before term
         *  \<br*\>   = can have a "br" tag before
         *
         * Group 2: (' . preg_quote($term->getName()) . ')
         *  The term to find, preg_quote() escapes special chars
         *
         * Group 3: ($|[\s\<[:punct:]]|\<br*\>)
         *  Same as Group 1 but with end of string and < (start of some tag)
         *
         * Group 4: (?![^<]*>|[^<>]*<\/)
         *  This Group protects any children element of the tag which should be parsed
         *  ?!        = negative lookahead
         *  [^<]*>    = match is between < & > and some other character
         *              avoids parsing terms in self closing tags
         *              example: <TERM> will work <TERM > not
         *  [^<>]*<\/ = match is between some tag and tag ending
         *              example: < or >TERM</>
         *
         * Flags:
         * i = ignores camel case
         */
        $regex = '#' .
            '(^|\G|[\s\>[:punct:]]|\<br*\>)' .
            '(' . preg_quote($term->getName()) . ')' .
            '($|[\s\<[:punct:]]|\<br*\>)' .
            '(?![^<]*>|[^<>]*<\/)' .
            '#i';

        // replace callback
        $callback = function ($match) use ($term, &$replacements, $wrappingCallback) {
            //decrease replacement counter
            if (0 < $replacements) {
                $replacements--;
            }
            // Use term match to keep original camel case
            $term->setName($match[2]);

            // Wrap replacement with original chars
            return $match[1] . call_user_func($wrappingCallback, $term) . $match[3];
        };

        // Use callback to keep allowed chars around the term and his camel case
        $text = preg_replace_callback($regex, $callback, $text, $replacements);
    }

    /**
     * Renders the wrapped term using the plugin settings
     *
     * @param \Featdd\DpnGlossary\Domain\Model\Term
     * @return string
     */
    protected function termWrapper(Term $term)
    {
        // get content object type
        $contentObjectType = $this->tsConfig['settings.']['termWraps'];
        // get term wrapping settings
        $wrapSettings = $this->tsConfig['settings.']['termWraps.'];
        // pass term data to the cObject pseudo constructor
        $this->contentObjectRenderer->start($term->toArray());

        // return the wrapped term
        return $this->contentObjectRenderer->cObjGetSingle($contentObjectType, $wrapSettings);
    }
}
