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
 *  (c) 2024 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Closure;
use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMText;
use DOMXPath;
use Featdd\DpnGlossary\Domain\Model\TermInterface;
use Featdd\DpnGlossary\Domain\Repository\ParserTermRepository;
use Featdd\DpnGlossary\Utility\ParserUtility;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @package Featdd\DpnGlossary\Service
 */
class ParserService implements SingletonInterface
{
    /**
     * @var string[]
     */
    public static array $alwaysIgnoreParentTags = [
        'a',
        'script',
    ];

    /**
     * @var string
     */
    public static string $additionalRegexWrapCharacters = '';

    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    protected ContentObjectRenderer $contentObjectRenderer;

    /**
     * @var array
     */
    protected array $terms = [];

    /**
     * @var array
     */
    protected array $typoScriptConfiguration = [];

    /**
     * @var array
     */
    protected array $settings = [];

    /**
     * Boots up:
     *  - configuration manager for TypoScript settings
     *  - contentObjectRenderer for generating links etc.
     *  - termRepository to get the Terms
     *
     * @param \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface $termsCache
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function __construct(FrontendInterface $termsCache)
    {
        // Get Configuration Manager
        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        // Inject Content Object Renderer
        $this->contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        // Get Query Settings
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        // Get Typoscript Configuration
        $this->typoScriptConfiguration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        // Reduce TS config to plugin
        $this->typoScriptConfiguration = $this->typoScriptConfiguration['plugin.']['tx_dpnglossary.'] ?? [];

        if (count($this->typoScriptConfiguration) > 0) {
            // Save extension settings without ts dots
            $this->settings = GeneralUtility::removeDotsFromTS($this->typoScriptConfiguration['settings.']);
            // Set StoragePid in the query settings object
            $querySettings->setStoragePageIds(
                GeneralUtility::trimExplode(
                    ',',
                    $this->typoScriptConfiguration['persistence.']['storagePid'] ?? ''
                )
            );

            $parsingSpecialWrapCharacters = GeneralUtility::trimExplode(',', $this->settings['parsingSpecialWrapCharacters'] ?? '', true);

            if (count($parsingSpecialWrapCharacters) > 0) {
                foreach ($parsingSpecialWrapCharacters as $parsingSpecialWrapCharacter) {
                    self::$additionalRegexWrapCharacters .= '|' . preg_quote($parsingSpecialWrapCharacter);
                }
            }

            try {
                /** @var \TYPO3\CMS\Core\Context\Context $context */
                $context = GeneralUtility::makeInstance(Context::class);
                $sysLanguageUid = $context->getPropertyFromAspect('language', 'id');
            } catch (AspectNotFoundException) {
                $sysLanguageUid = 0;
            }

            /** @var \Featdd\DpnGlossary\Domain\Repository\TermRepositoryInterface $termRepository */
            $termRepository = GeneralUtility::makeInstance($this->settings['parserRepositoryClass'] ?? ParserTermRepository::class);

            // Set current language uid
            $querySettings->setLanguageUid($sysLanguageUid);
            // Set query to respect the language uid
            $querySettings->setRespectSysLanguage(true);
            // Assign query settings object to repository
            $termRepository->setDefaultQuerySettings($querySettings);

            //Find all terms
            if (!($this->settings['useCachingFramework'] ?? true)) {
                $terms = $termRepository->findByNameLength();
            } else {
                $cacheIdentifier = sha1('termsByNameLength' . $querySettings->getLanguageUid() . '_' . implode('', $querySettings->getStoragePageIds()));
                $terms = $termsCache->get($cacheIdentifier);

                // If $terms is empty, it hasn't been cached. Calculate the value and store it in the cache:
                if (empty($terms)) {
                    $terms = $termRepository->findByNameLength();
                    // Save value in cache
                    $termsCache->set($cacheIdentifier, $terms, ['dpnglossary_termscache']);
                }
            }

            //Sort terms with an individual counter for max replacement per page
            /** @var \Featdd\DpnGlossary\Domain\Model\TermInterface $term */
            foreach ($terms as $term) {
                $maxReplacements = $term->getMaxReplacements() === -1
                    ? (int)($this->settings['maxReplacementPerPage'] ?? -1)
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
     * @return string
     * @throws \Featdd\DpnGlossary\Service\Exception
     */
    public function pageParser(string $html): string
    {
        // extract Pids which should be parsed
        $parsingPids = GeneralUtility::intExplode(',', $this->settings['parsingPids'] ?? '');
        // extract Pids which should NOT be parsed
        $excludePids = GeneralUtility::intExplode(',', $this->settings['parsingExcludePidList'] ?? '');
        // Get Tags which content should be parsed
        $tags = GeneralUtility::trimExplode(',', $this->settings['parsingTags'] ?? '');
        // Remove "a" & "script" from parsingTags if it was added unknowingly
        if (in_array(self::$alwaysIgnoreParentTags, $tags, true)) {
            $tags = array_diff($tags, self::$alwaysIgnoreParentTags);
        }

        $currentPageId = (int)$GLOBALS['TSFE']->id;
        $currentPageType = (int)$GLOBALS['TSFE']->type;

        // Abort parser...
        if (
            // Parser disabled
            ($this->settings['disableParser'] ?? false) ||
            // Pagetype not 0
            $currentPageType !== 0 ||
            // no tags to parse given
            count($tags) === 0 ||
            // no terms have been found
            count($this->terms) === 0 ||
            // no config is given
            count($this->typoScriptConfiguration) === 0 ||
            // page is excluded
            in_array($currentPageId, $excludePids, true) ||
            (
                // parsingPids doesn't contain 0 and...
                !in_array(0, $parsingPids, true) &&
                // page is not whitelisted
                !in_array($currentPageId, $parsingPids, true)
            )
        ) {
            return $html;
        }

        // Protect scripts, src & links from unwanted DOM sideffects
        $protectedHtml = ParserUtility::protectLinkAndSrcPathsFromDOM(
            ParserUtility::protectScrtiptsAndCommentsFromDOM(
                $html
            )
        );

        // Classes which are not allowed for the parsing tag
        $forbiddenParsingTagClasses = GeneralUtility::trimExplode(',', $this->settings['forbiddenParsingTagClasses'] ?? '', true);

        // Classes which are not allowed for the parsing tag
        $forbiddenParentClasses = GeneralUtility::trimExplode(',', $this->settings['forbiddenParentClasses'] ?? '', true);

        // Tags which are not allowed as direct parent for a parsingTag
        $forbiddenParentTags = GeneralUtility::trimExplode(',', $this->settings['forbiddenParentTags'] ?? '', true);

        // Respect synonyms for replacement count
        $isMaxReplacementPerPageRespectSynonyms = (bool)($this->settings['maxReplacementPerPageRespectSynonyms'] ?? false);

        // Parse synonyms
        $isParseSynonyms = (bool)($this->settings['parseSynonyms'] ?? true);

        // Parse synonyms before or after the main term
        $isPriorisedSynonymParsing = (bool)($this->settings['priorisedSynonymParsing'] ?? true);

        // Limit parsing to a single node with this ID
        $limitParsingId = (string)($this->settings['limitParsingId'] ?? '');

        // Excludes a term if the term link target page is the current page
        $isExcludeTermLinksTargetPages = (bool)($this->settings['excludeTermLinksTargetPages'] ?? false);

        // Add "a" if unknowingly deleted to prevent errors
        if (!in_array(self::$alwaysIgnoreParentTags, $forbiddenParentTags, true)) {
            $forbiddenParentTags = array_unique(
                array_merge($forbiddenParentTags, self::$alwaysIgnoreParentTags)
            );
        }

        // Create new DOMDocument
        $DOM = new DOMDocument();

        // remove unnecessary whitespaces in nodes (no visible whitespace)
        $DOM->preserveWhiteSpace = false;

        // Prevent crashes caused by HTML5 entities with internal errors
        libxml_use_internal_errors(true);

        $isLimitToXpath = !empty($limitParsingId);

        if (!$isLimitToXpath) {
            // Load Page HTML in DOM and check if HTML is valid else abort
            // use XHTML tag for avoiding UTF-8 encoding problems
            if (!$DOM->loadHTML('<?xml encoding="UTF-8">' . $protectedHtml)) {
                throw new Exception('Parsers DOM Document could\'nt load the html');
            }

            /** @var \DOMNode $DOMNodeToParse */
            $DOMNodeToParse = $DOM->getElementsByTagName('body')->item(0);
        } else {
            // Create new DOMDocument for separately holding the whole HTML
            $DOMpage = new DOMDocument();

            // Load Page HTML in DOM...
            if (!$DOMpage->loadHTML('<?xml encoding="UTF-8">' . $protectedHtml)) {
                throw new Exception('Parsers DOM Document could\'nt load the html');
            }

            // Init DOMXPath with separate DOMDocument
            $DOMXPathPage = new DOMXPath($DOMpage);

            // Extract the DOM Node to wich the parser should limit its parsing
            /** @var \DOMNode $DOMcontent */
            $DOMcontent = $DOMXPathPage
                ->query(
                    '//*[@id="' . $limitParsingId . '"]',
                    $DOMpage->getElementsByTagName('body')->item(0)
                )
                ->item(0);

            // Abort if the id for limited parsing was not found
            if (!$DOMcontent instanceof DOMNode) {
                return $html;
            }

            // Only load the extracted nodes content into the main DOM Document for parsing
            if (!$DOM->loadHTML('<?xml encoding="UTF-8">' . $DOMpage->saveHTML($DOMcontent))) {
                throw new Exception('Parsers DOM Document could\'nt load the html');
            }

            // Extract the node again from the main DOM
            /** @var \DOMNode $DOMNodeToParse */
            $DOMNodeToParse = $DOM->getElementById($limitParsingId);
        }

        // Init DOMXPath with main DOMDocument
        $DOMXPath = new DOMXPath($DOM);

        // This can be changed to "$this->termWrapper(...)" when dropping PHP 8.0 support
        $wrapperClosure = Closure::fromCallable([$this, 'termWrapper']);

        /** @var \TYPO3\CMS\Core\Http\ServerRequest $request */
        $request = $GLOBALS['TYPO3_REQUEST'];
        $queryParameters = $request->getQueryParams();
        $currentDetailPageTermUid = null;

        if (
            array_key_exists('tx_dpnglossary_glossary', $queryParameters) &&
            array_key_exists('term', $queryParameters['tx_dpnglossary_glossary'])
        ) {
            $currentDetailPageTermUid = (int)$queryParameters['tx_dpnglossary_glossary']['term'];
        }

        foreach ($this->terms as $term) {
            /** @var \Featdd\DpnGlossary\Domain\Model\TermInterface $termObject */
            $termObject = clone $term['term'];
            $replacements = &$term['replacements'];

            if ($isExcludeTermLinksTargetPages && $termObject->getTermMode() === 'link') {
                $termLink = trim($termObject->getTermLink());

                if (filter_var($termLink, FILTER_VALIDATE_INT) !== false) {
                    $termLinkIsCurrentPage = (int)$termLink === $currentPageId;
                } elseif (str_starts_with($termLink, 't3://page')) {
                    parse_str(htmlspecialchars_decode(parse_url($termLink, PHP_URL_QUERY)), $queryParameters);
                    $linkTargetPage = (int)($queryParameters['uid'] ?? null);
                    $termLinkIsCurrentPage = $linkTargetPage === $currentPageId;
                } else {
                    $currentUrl = strtok(GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'), '?');
                    $termLinkIsCurrentPage = $termLink === $currentUrl;
                }

                if ($termLinkIsCurrentPage) {
                    continue;
                }
            }

            if (
                $replacements === 0 ||
                $termObject->isExcludeFromParsing() ||
                $currentDetailPageTermUid === $termObject->getUid()
            ) {
                continue;
            }

            $xpathQuery = '';

            // iterate over tags which are defined to be parsed
            foreach ($tags as $tag) {
                if (!empty($xpathQuery)) {
                    $xpathQuery .= ' | ';
                }

                $xpathQuery .= '//' . $tag;

                // if forbidden parsing tag classes given add them to xpath query
                if (count($forbiddenParsingTagClasses) > 0) {
                    $xpathQuery .= '[not(contains(@class, \'' .
                        implode(
                            '\') or contains(@class, \'',
                            $forbiddenParsingTagClasses
                        ) .
                        '\'))';

                    $xpathQuery = count($forbiddenParentClasses) > 0 ? $xpathQuery . ' and ' : $xpathQuery . ']';
                }

                /*
                 * Due to PHP still uses XPath 1.0 up to its latest versions we still have to use "contains" here.
                 * It would make more sense to use the function "matches", but this is only supported from version 2.0.
                 * This inevitably leads to the problem that contains also matches if the class is concatenated.
                 * Example: class="example-andmore" using "contains" for "example" still matches, although it's different
                 */

                // if forbidden parent classes given add them to xpath query
                if (count($forbiddenParentClasses) > 0) {
                    $xpathQuery .= (count($forbiddenParsingTagClasses) > 0 ? '' : '[') .
                        'not(./ancestor::*[contains(@class, \'' .
                        implode(
                            '\')] or ./ancestor::*[contains(@class, \'',
                            $forbiddenParentClasses
                        ) .
                        '\')])]';
                }
            }

            // extract the tags
            $DOMTags = $DOMXPath->query($xpathQuery, $DOMNodeToParse);

            if (!$isPriorisedSynonymParsing) {
                $this->domTagsParser($DOMTags, $termObject, $replacements, $wrapperClosure, $forbiddenParentTags);
            }

            if ($isParseSynonyms) {
                $synonymTermObject = clone $termObject;
                /** @var \Featdd\DpnGlossary\Domain\Model\Synonym $synonym */
                foreach ($termObject->getSynonyms() as $synonym) {
                    $synonymTermObject->{
                    ($this->settings['useTermForSynonymParsingDataWrap'] ?? false)
                        ? 'setParsingName'
                        : 'setName'
                    }(
                        $synonym->getName()
                    );

                    if ($isMaxReplacementPerPageRespectSynonyms) {
                        $this->domTagsParser(
                            $DOMTags,
                            $synonymTermObject,
                            $replacements,
                            $wrapperClosure,
                            $forbiddenParentTags
                        );
                    } else {
                        $noReplacementCount = -1;
                        $this->domTagsParser(
                            $DOMTags,
                            $synonymTermObject,
                            $noReplacementCount,
                            $wrapperClosure,
                            $forbiddenParentTags
                        );
                    }
                }
            }

            if ($isPriorisedSynonymParsing) {
                $this->domTagsParser($DOMTags, $termObject, $replacements, $wrapperClosure, $forbiddenParentTags);
            }
        }

        if (!$isLimitToXpath) {
            $parsedHtml = $DOM->saveHTML();
        } else {
            // Extract the original node from the separate DOM
            $originalContentDOMNode = $DOMpage->getElementById($limitParsingId);
            // Import the processed node into the separate DOM
            $parsedContentDOMNode = $DOMpage->importNode($DOMNodeToParse, true);
            // Replace the parsed node in the separate DOM
            $originalContentDOMNode
                ->parentNode
                ->replaceChild($parsedContentDOMNode, $originalContentDOMNode);

            $parsedHtml = $DOMpage->saveHTML();
        }

        // Reverse DOM sideffects protection and apply some repairs for some unwanted HTML5 adjustments from DOM
        $parsedHtml = ParserUtility::protectScriptsAndCommentsFromDOMReverse(
            ParserUtility::protectLinkAndSrcPathsFromDOMReverse(
                ParserUtility::domHtml5Repairs(
                    $parsedHtml
                )
            )
        );

        // return the parsed html page and remove XHTML tag which is not needed anymore
        return str_replace('<?xml encoding="UTF-8">', '', $parsedHtml);
    }

    /**
     * @param \DOMNodeList $domTags
     * @param \Featdd\DpnGlossary\Domain\Model\ParserTerm $term
     * @param int $replacements
     * @param \Closure $wrapperClosure
     * @param string[] $forbiddenParentTags
     */
    protected function domTagsParser(
        DOMNodeList $domTags,
        TermInterface $term,
        int &$replacements,
        Closure $wrapperClosure,
        array $forbiddenParentTags
    ): void {
        // call the nodereplacer for each node to parse its content
        /** @var \DOMNode $DOMTag */
        foreach ($domTags as $DOMTag) {
            // get parent tags from root tree string
            $parentTags = explode(
                '/',
                preg_replace(
                    '#\[([^]]*)]#',
                    '',
                    substr($DOMTag->parentNode->getNodePath(), 1)
                )
            );

            // check if element is children of a forbidden parent
            if (0 === count(array_intersect($parentTags, $forbiddenParentTags))) {
                /** @var \DOMNode $childNode */
                for ($i = 0; $i < $DOMTag->childNodes->length; $i++) {
                    $childNode = $DOMTag->childNodes->item($i);

                    if ($childNode instanceof DOMText) {
                        $text = preg_replace(
                            '#\x{00a0}#u', '&nbsp;',
                            $childNode->ownerDocument->saveHTML($childNode)
                        );

                        ParserUtility::domTextReplacer(
                            $childNode,
                            $this->regexParser($text, $term, $replacements, $wrapperClosure)
                        );
                    }
                }
            }
        }
    }

    /**
     * Regex parser for terms on a text string
     *
     * @param string $text
     * @param \Featdd\DpnGlossary\Domain\Model\TermInterface $term
     * @param int $replacements
     * @param \Closure $wrapperClosure
     * @return string
     */
    protected function regexParser(string $text, TermInterface $term, int &$replacements, Closure $wrapperClosure): string
    {
        // Try simple search first to save performance
        if (mb_stripos($text, $term->getParsingName()) === false) {
            return $text;
        }

        $quotedTerm = preg_quote($term->getParsingName(), '#');
        $umlautsInTerm = count(array_intersect(mb_str_split($quotedTerm), array_keys(ParserUtility::UMLAUT_MATCHING_GROUPS)));
        $matchArrayEndingCharacterIndex = 3;

        if (!$term->isCaseSensitive() && $umlautsInTerm > 0) {
            $matchArrayEndingCharacterIndex += $umlautsInTerm;
            $quotedTerm = ParserUtility::replaceTermUmlautsWithMatchingGroups($quotedTerm);
        }

        /*
         * Regex Explanation:
         * Group 1: (^|[\s\>[:punct:]]|<br*>)
         *  ^         = can be begin of the string
         *  \G        = can match another matchs end
         *  \s        = can have space before term
         *  \>        = can have a > before term (end of some tag)
         *  [:punct:] = can have punctuation characters like .,?!& etc. before term
         *  <br*>   = can have a "br" tag before
         *
         * Group 2: (' . preg_quote($term->getName()) . ')
         *  The term to find, preg_quote() escapes special chars
         *
         * Group 3: ($|[\s\<[:punct:]]|<br*>)
         *  Same as Group 1 but with end of string and < (start of some tag)
         *
         * Group 4: (?![^<]*>|[^<>]*</)
         *  This Group protects any children element of the tag which should be parsed
         *  ?!        = negative lookahead
         *  [^<]*>    = match is between < & > and some other character
         *              avoids parsing terms in self-closing tags
         *              example: <TERM> will work <TERM > not
         *  [^<>]*</ = match is between some tag and tag ending
         *              example: < or >TERM</>
         *
         * Flags:
         * i = ignores camel case
         */
        $regex = '#' .
            '(^|\G|[\s>[:punct:]]|<br*>' . self::$additionalRegexWrapCharacters . ')' .
            '(' . $quotedTerm . ')' .
            '($|[\s<[:punct:]]|<br*>' . self::$additionalRegexWrapCharacters . ')' .
            '(?![^<]*>|[^<>]*</)' .
            '#' .
            ($term->isCaseSensitive() ? '' : 'i');

        // replace callback
        $callback = function (array $match) use (
            $term,
            &$replacements,
            $wrapperClosure,
            $matchArrayEndingCharacterIndex
        ) {
            //decrease replacement counter
            if (0 < $replacements) {
                $replacements--;
            }

            // Use term match to keep original camel case
            $term->setParsingName($match[2]);

            // Wrap replacement with original chars
            return $match[1] . $wrapperClosure($term) . $match[$matchArrayEndingCharacterIndex];
        };

        // Use callback to keep allowed chars around the term and his camel case
        return (string)preg_replace_callback($regex, $callback, $text, $replacements);
    }

    /**
     * Renders the wrapped term using the plugin settings
     *
     * @param \Featdd\DpnGlossary\Domain\Model\TermInterface $term
     * @return string
     * @throws \UnexpectedValueException
     */
    protected function termWrapper(TermInterface $term): string
    {
        // get content object type
        $contentObjectType = $this->typoScriptConfiguration['settings.']['termWraps'];
        // get term wrapping settings
        $wrapSettings = $this->typoScriptConfiguration['settings.']['termWraps.'];
        // pass term data to the cObject pseudo constructor
        $this->contentObjectRenderer->start(
            $term->__toArray(),
            TermInterface::TABLE
        );

        // return the wrapped term
        return $this->contentObjectRenderer->cObjGetSingle($contentObjectType, $wrapSettings);
    }
}
