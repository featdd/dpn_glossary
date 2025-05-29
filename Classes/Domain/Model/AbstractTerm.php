<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Domain\Model;

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

use Featdd\DpnGlossary\Utility\LinkUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @package Featdd\DpnGlossary\Domain\Model
 */
abstract class AbstractTerm extends AbstractEntity implements TermInterface
{
    /**
     * @var string
     */
    protected string $name = '';

    /**
     * @var string
     */
    protected string $parsingName = '';

    /**
     * @var string
     */
    protected string $urlSegment = '';

    /**
     * @var string
     */
    protected string $tooltiptext = '';

    /**
     * @var string
     */
    protected string $termType = '';

    /**
     * @var string
     */
    protected string $termLang = '';

    /**
     * @var string
     */
    protected string $termMode = '';

    /**
     * @var string
     */
    protected string $termLink = '';

    /**
     * @var bool
     */
    protected bool $excludeFromParsing = false;

    /**
     * @var bool
     */
    protected bool $caseSensitive = false;

    /**
     * @var int
     */
    protected int $maxReplacements = -1;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Synonym>
     */
    protected ObjectStorage $synonyms;

    public function __construct()
    {
        $this->synonyms = new ObjectStorage();
    }

    /**
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->parsingName = $name;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getParsingName(): string
    {
        if (empty($this->parsingName)) {
            return $this->name;
        }

        return $this->parsingName;
    }

    /**
     * @param string $parsingName
     */
    public function setParsingName(string $parsingName): void
    {
        $this->parsingName = $parsingName;
    }

    /**
     * @return string
     */
    public function getUrlSegment(): string
    {
        return $this->urlSegment;
    }

    /**
     * @param string $urlSegment
     */
    public function setUrlSegment(string $urlSegment): void
    {
        $this->urlSegment = $urlSegment;
    }

    /**
     * @return string $tooltiptext
     */
    public function getTooltiptext(): string
    {
        return $this->tooltiptext;
    }

    /**
     * @param string $tooltiptext
     */
    public function setTooltiptext(string $tooltiptext): void
    {
        $this->tooltiptext = $tooltiptext;
    }

    /**
     * @return string
     */
    public function getTermType(): string
    {
        return $this->termType;
    }

    /**
     * @param string $termType
     */
    public function setTermType(string $termType): void
    {
        $this->termType = $termType;
    }

    /**
     * @return string
     */
    public function getTermLang(): string
    {
        return $this->termLang;
    }

    /**
     * @param string $termLang
     */
    public function setTermLang(string $termLang): void
    {
        $this->termLang = $termLang;
    }

    /**
     * @return string
     */
    public function getTermMode(): string
    {
        return $this->termMode;
    }

    /**
     * @param string $termMode
     */
    public function setTermMode(string $termMode): void
    {
        $this->termMode = $termMode;
    }

    /**
     * @return string
     */
    public function getTermLink(): string
    {
        return $this->termLink;
    }

    /**
     * @param string $termLink
     */
    public function setTermLink(string $termLink): void
    {
        $this->termLink = $termLink;
    }

    /**
     * @return bool
     */
    public function isExcludeFromParsing(): bool
    {
        return $this->excludeFromParsing;
    }

    /**
     * @param bool $excludeFromParsing
     */
    public function setExcludeFromParsing(bool $excludeFromParsing): void
    {
        $this->excludeFromParsing = $excludeFromParsing;
    }

    /**
     * @return bool
     */
    public function isCaseSensitive(): bool
    {
        return $this->caseSensitive;
    }

    /**
     * @param bool $caseSensitive
     */
    public function setCaseSensitive(bool $caseSensitive): void
    {
        $this->caseSensitive = $caseSensitive;
    }

    /**
     * @return int
     */
    public function getMaxReplacements(): int
    {
        return $this->maxReplacements;
    }

    /**
     * @param int $maxReplacements
     */
    public function setMaxReplacements(int $maxReplacements): void
    {
        $this->maxReplacements = $maxReplacements;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Synonym>
     */
    public function getSynonyms(): ObjectStorage
    {
        return $this->synonyms;
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Synonym $synonym
     */
    public function addSynonym(Synonym $synonym): void
    {
        $this->synonyms->attach($synonym);
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Synonym $synonym
     */
    public function removeSynonym(Synonym $synonym): void
    {
        $this->synonyms->detach($synonym);
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Synonym> $synonyms
     */
    public function setSynonyms(ObjectStorage $synonyms): void
    {
        $this->synonyms = $synonyms;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        $synonyms = [];

        /*
         * "toArray" is here necessary to prevent ObjectStorage complications when calling this function while
         * already iterating over the synonyms, otherwise the internal storage array pointer gets resettet.
         * See: https://github.com/featdd/dpn_glossary/issues/213
         */
        foreach ($this->getSynonyms()->toArray() as $synonym) {
            $synonyms[] = $synonym->__toArray();
        }

        return [
            'uid' => $this->getUid(),
            'pid' => $this->getPid(),
            'name' => $this->getName(),
            'parsing_name' => $this->getParsingName(),
            'url_segment' => $this->getUrlSegment(),
            'tooltiptext' => $this->getTooltiptext(),
            'term_type' => $this->getTermType(),
            'term_lang' => $this->getTermLang(),
            'term_mode' => $this->getTermMode(),
            'term_link' => $this->getTermLink(),
            'exclude_from_parsing' => $this->isExcludeFromParsing(),
            'synonyms' => $synonyms,
        ];
    }
}
