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

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 * @package Featdd\DpnGlossary\Domain\Model
 */
class Term extends AbstractTerm
{
    /**
     * @var string
     */
    protected string $seoTitle = '';

    /**
     * @var string
     */
    protected string $metaDescription = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Description>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $descriptions;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $media;

    public function __construct()
    {
        parent::__construct();
        $this->descriptions = new ObjectStorage();
        $this->media = new ObjectStorage();
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
        $record = parent::__toArray();

        $record['descriptions'] = [];
        $record['media'] = [];

        foreach ($this->getDescriptions() as $description) {
            $record['descriptions'][] = $description->__toArray();
        }

        foreach ($this->getMedia() as $media) {
            $record['media'][] = $media->getOriginalResource()->toArray();
        }

        return $record;
    }
}
