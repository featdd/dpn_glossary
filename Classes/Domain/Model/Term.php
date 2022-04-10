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
 *  (c) 2021 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 * @package Featdd\DpnGlossary\Domain\Model
 */
class Term extends AbstractEntity
{
    public const TABLE = 'tx_dpnglossary_domain_model_term';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $parsingName = '';

    /**
     * @var string
     */
    protected $tooltiptext = '';

    /**
     * @var string
     */
    protected $termType = '';

    /**
     * @var string
     */
    protected $termLang = '';

    /**
     * @var string
     */
    protected $termMode = '';

    /**
     * @var string
     */
    protected $termLink = '';

    /**
     * @var string
     */
    protected $seoTitle = '';

    /**
     * @var string
     */
    protected $metaDescription = '';

    /**
     * @var bool
     */
    protected $excludeFromParsing = false;

    /**
     * @var bool
     */
    protected $caseSensitive = false;

    /**
     * @var int
     */
    protected $maxReplacements = -1;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Description>
     */
    protected $descriptions;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Synonym>
     */
    protected $synonyms;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $media;

    public function __construct()
    {
        $this->descriptions = new ObjectStorage();
        $this->synonyms = new ObjectStorage();
        $this->media = new ObjectStorage();
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
        if (true === empty($this->parsingName)) {
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
     * @return string
     */
    public function getSeoTitle(): string
    {
        return true === empty($this->seoTitle)
            ? $this->name
            : $this->seoTitle;
    }

    /**
     * @param string $seoTitle
     */
    public function setSeoTitle(string $seoTitle): void
    {
        $this->seoTitle = $seoTitle;
    }

    /**
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription(string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return bool
     */
    public function getExcludeFromParsing(): bool
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
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Description>
     */
    public function getDescriptions(): ObjectStorage
    {
        return $this->descriptions;
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Description $description
     */
    public function addDescription(Description $description): void
    {
        $this->descriptions->attach($description);
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Description $description
     */
    public function removeDescription(Description $description): void
    {
        $this->descriptions->detach($description);
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Description> $descriptions
     */
    public function setDescriptions(ObjectStorage $descriptions): void
    {
        $this->descriptions = $descriptions;
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
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $media
     */
    public function setMedia(ObjectStorage $media): void
    {
        $this->media = $media;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
     */
    public function addMedia(FileReference $file): void
    {
        $this->media->attach($file);
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
     */
    public function removeMedia(FileReference $file): void
    {
        $this->media->detach($file);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getMedia(): ObjectStorage
    {
        return $this->media;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return [
            'uid' => $this->getUid(),
            'pid' => $this->getPid(),
            'name' => $this->getName(),
            'parsing_name' => $this->getParsingName(),
            'tooltiptext' => $this->getTooltiptext(),
            'term_type' => $this->getTermType(),
            'term_lang' => $this->getTermLang(),
            'term_mode' => $this->getTermMode(),
            'term_link' => $this->getTermLink(),
            'exclude_from_parsing' => $this->getExcludeFromParsing(),
            'descriptions' => $this->getDescriptions(),
            'synonyms' => $this->getSynonyms(),
            'media' => $this->getMedia(),
        ];
    }
}
