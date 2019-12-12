<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Service;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Domain\Model\Term;
use Featdd\DpnGlossary\Domain\Repository\TermRepository;
use Featdd\DpnGlossary\Utility\ObjectUtility;
use Featdd\DpnGlossary\Utility\ParserUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @package DpnGlossary
 * @subpackage Service
 */
class ParserService implements SingletonInterface
{
    public const REGEX_DELIMITER = '/';

    /**
     * tags to be always ignored by parsing
     */
    public static $alwaysIgnoreParentTags = [
        'a',
        'script',
    ];

    /**
     * @var ContentObjectRenderer
     */
    protected $contentObjectRenderer;

    /**
     * @var array
     */
    protected $terms = [];

    /**
     * @var array
     */
    protected $typoScriptConfiguration = [];

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * Boots up:
     *  - configuration manager for TypoScript settings
     *  - contentObjectRenderer for generating links etc.
     *  - termRepository to get the Terms
     *
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    public function __construct()
    {
        // Get Configuration Manager
        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = ObjectUtility::makeInstance(ConfigurationManager::class);
        // Get Cache Manager
        /** @var \TYPO3\CMS\Core\Cache\CacheManager $cacheManager */
        $cacheManager = ObjectUtility::makeInstance(CacheManager::class);
        // Inject Content Object Renderer
        $this->contentObjectRenderer = ObjectUtility::makeInstance(ContentObjectRenderer::class);
        // Get Query Settings
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface $querySettings */
        $querySettings = ObjectUtility::makeInstance(QuerySettingsInterface::class);
        // Get termRepository
        /** @var \Featdd\DpnGlossary\Domain\Repository\TermRepository $termRepository */
        $termRepository = ObjectUtility::makeInstance(TermRepository::class);
        // Get Typoscript Configuration
        $this->typoScriptConfiguration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        // Reduce TS config to plugin
        $this->typoScriptConfiguration = $this->typoScriptConfiguration['plugin.']['tx_dpnglossary.'];

        if (null !== $this->typoScriptConfiguration && 0 < \count($this->typoScriptConfiguration)) {
            // Save extension settings without ts dots
            $this->settings = GeneralUtility::removeDotsFromTS($this->typoScriptConfiguration['settings.']);
            // Set StoragePid in the query settings object
            $querySettings->setStoragePageIds(
                GeneralUtility::trimExplode(
                    ',',
                    $this->typoScriptConfiguration['persistence.']['storagePid'])
            );

            try {
                /** @var \TYPO3\CMS\Core\Context\Context $context */
                $context = ObjectUtility::makeInstance(Context::class);
                $sysLanguageUid = $context->getPropertyFromAspect('language', 'id');
            } catch (AspectNotFoundException $exception) {
                $sysLanguageUid = 0;
            }

            // Set current language uid
            $querySettings->setLanguageUid($sysLanguageUid);
            // Set query to respect the language uid
            $querySettings->setRespectSysLanguage(true);
            // Assign query settings object to repository
            $termRepository->setDefaultQuerySettings($querySettings);

            //Find all terms
            if (false === (bool) $this->settings['useCachingFramework']) {
                $terms = $termRepository->findByNameLength();
            } else {
                $cacheIdentifier = sha1('termsByNameLength' . $querySettings->getLanguageUid() . '_' . implode('', $querySettings->getStoragePageIds()));
                $cache = $cacheManager->getCache('dpnglossary_termscache');
                $terms = $cache->get($cacheIdentifier);

                // If $terms is null, it hasn't been cached. Calculate the value and store it in the cache:
                if ($terms === false) {
                    $terms = $termRepository->findByNameLength();
                    // Save value in cache
                    $cache->set($cacheIdentifier, $terms, ['dpnglossary_termscache']);
                }
            }

            //Sort terms with an individual counter for max replacement per page
            /** @var Term $term */
            foreach ($terms as $term) {
                $maxReplacements = -1 === $term->getMaxReplacements()
                    ? (int) $this->settings['maxReplacementPerPage']
                    : $term->getMaxReplacements();

                $this->terms[] = [
                    'term' => $term,
                    'replacements' => $maxReplacements,
                ];
            }
        }
    }

    /**
     * parse html for terms and return the parsed html
     * or false if parsers has to be aborted
     *
     * @param string $html
     * @return string|null
     * @throws Exception
     */
    public function pageParser(string $html): string
    {
        // extract Pids which should be parsed
        $parsingPids = GeneralUtility::intExplode(',', $this->settings['parsingPids']);
        // extract Pids which should NOT be parsed
        $excludePids = GeneralUtility::intExplode(',', $this->settings['parsingExcludePidList']);
        // Get Tags which content should be parsed
        $tags = GeneralUtility::trimExplode(',', $this->settings['parsingTags']);
        // Remove "a" & "script" from parsingTags if it was added unknowingly
        if (true === \in_array(self::$alwaysIgnoreParentTags, $tags, true)) {
            $tags = array_diff($tags, self::$alwaysIgnoreParentTags);
        }

        $currentPageId = (int) $GLOBALS['TSFE']->id;
        $currentPageType = (int) $GLOBALS['TSFE']->type;

        // Abort parser...
        if (
            // Parser disabled
            true === (bool) $this->settings['disableParser'] ||
            // Pagetype not 0
            0 !== $currentPageType ||
            // no tags to parse given
            0 === \count($tags) ||
            // no terms have been found
            0 === \count($this->terms) ||
            // no config is given
            0 === \count($this->typoScriptConfiguration) ||
            // page is excluded
            true === \in_array($currentPageId, $excludePids, true) ||
            (
                // parsingPids doesn't contain 0 and...
                false === \in_array(0, $parsingPids, true) &&
                // page is not whitelisted
                false === \in_array($currentPageId, $parsingPids, true)
            )
        ) {
            return $html;
        }

        // Classes which are not allowed for the parsing tag
        $forbiddenParsingTagClasses = array_filter(
            GeneralUtility::trimExplode(',', $this->settings['forbiddenParsingTagClasses'])
        );

        // Tags which are not allowed as direct parent for a parsingTag
        $forbiddenParentTags = array_filter(GeneralUtility::trimExplode(',', $this->settings['forbiddenParentTags']));

        // Add "a" if unknowingly deleted to prevent errors
        if (false === \in_array(self::$alwaysIgnoreParentTags, $forbiddenParentTags, true)) {
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

        /** @var \DOMNode $DOMBody */
        $DOMBody = $DOM->getElementsByTagName('body')->item(0);

        $wrapperClosure = \Closure::fromCallable([$this, 'termWrapper']);

        // iterate over tags which are defined to be parsed
        foreach ($tags as $tag) {
            $xpathQuery = '//' . $tag;

            // if classes given add them to xpath query
            if (0 < \count($forbiddenParsingTagClasses)) {
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
                if (false === (bool)count(array_intersect($parentTags, $forbiddenParentTags))) {
                    /** @var \DOMNode $childNode */
                    for ($i = 0; $i < $DOMTag->childNodes->length; $i++) {
                        $childNode = $DOMTag->childNodes->item($i);

                        if ($childNode instanceof \DOMText) {
                            ParserUtility::domTextReplacer(
                                $childNode,
                                $this->textParser(
                                    $childNode->ownerDocument->saveHTML($childNode),
                                    $wrapperClosure
                                )
                            );
                        }
                    }
                }
            }
        }

        // return the parsed html page and remove XHTML tag which is not needed anymore
        return str_replace(
            '<?xml encoding="UTF-8">',
            '',
            ParserUtility::protectScriptsAndCommentsFromDOMReverse(
                ParserUtility::protectLinkAndSrcPathsFromDOMReverse(
                    ParserUtility::domHtml5Repairs(
                        $DOM->saveHTML()
                    )
                )
            )
        );
    }

    /**
     * Parse the extracted html for terms
     *
     * @param string $text the text to be parsed
     * @param \Closure $wrapperClosure the wrapping function for parsed terms as callback
     * @return string
     */
    public function textParser(string $text, \Closure $wrapperClosure): string
    {
        $text = preg_replace('#\x{00a0}#iu', '&nbsp;', $text);
        // Iterate over terms and search matches for each of them
        foreach ($this->terms as &$term) {
            /** @var \Featdd\DpnGlossary\Domain\Model\Term $termObject */
            $termObject = clone $term['term'];
            $replacements = &$term['replacements'];

            if (true === $termObject->getExcludeFromParsing()) {
                continue;
            }

            //Check replacement counter
            if (0 !== $term['replacements']) {
                $this->regexParser($text, $termObject, $replacements, $wrapperClosure);

                if (true === (boolean) $this->settings['parseSynonyms']) {
                    /** @var \Featdd\DpnGlossary\Domain\Model\Synonym $synonym */
                    foreach ($termObject->getSynonyms() as $synonym) {
                        $termObject->setName(
                            $synonym->getName()
                        );

                        if (true === (boolean) $this->settings['maxReplacementPerPageRespectSynonyms']) {
                            $this->regexParser($text, $termObject, $replacements, $wrapperClosure);
                        } else {
                            $noReplacementCount = -1;
                            $this->regexParser($text, $termObject, $noReplacementCount, $wrapperClosure);
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
     * @param string $text
     * @param Term $term
     * @param integer $replacements
     * @param \Closure $wrapperClosure
     */
    protected function regexParser(string &$text, Term $term, int &$replacements, \Closure $wrapperClosure): void
    {
        // Try simple search first to save performance
        if (false === mb_stripos($text, $term->getName())) {
            return;
        }

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
        $regex = self::REGEX_DELIMITER .
            '(^|\G|[\s\>[:punct:]]|\<br*\>)' .
            '(' . preg_quote($term->getName(), self::REGEX_DELIMITER) . ')' .
            '($|[\s\<[:punct:]]|\<br*\>)' .
            '(?![^<]*>|[^<>]*<\/)' .
            self::REGEX_DELIMITER .
            (false === $term->isCaseSensitive() ? 'i' : '');

        // replace callback
        $callback = function (array $match) use ($term, &$replacements, $wrapperClosure) {
            //decrease replacement counter
            if (0 < $replacements) {
                $replacements--;
            }

            // Use term match to keep original camel case
            $term->setName($match[2]);

            // Wrap replacement with original chars
            return $match[1] . $wrapperClosure($term) . $match[3];
        };

        // Use callback to keep allowed chars around the term and his camel case
        $text = (string) preg_replace_callback($regex, $callback, $text, $replacements);
    }

    /**
     * Renders the wrapped term using the plugin settings
     *
     * @param \Featdd\DpnGlossary\Domain\Model\Term
     * @return string
     * @throws \UnexpectedValueException
     */
    protected function termWrapper(Term $term): string
    {
        // get content object type
        $contentObjectType = $this->typoScriptConfiguration['settings.']['termWraps'];
        // get term wrapping settings
        $wrapSettings = $this->typoScriptConfiguration['settings.']['termWraps.'];
        // pass term data to the cObject pseudo constructor
        $this->contentObjectRenderer->start(
            $term->__toArray(),
            Term::TABLE
        );

        // return the wrapped term
        return $this->contentObjectRenderer->cObjGetSingle($contentObjectType, $wrapSettings);
    }
}
