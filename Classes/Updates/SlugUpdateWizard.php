<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Updates;

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

use Featdd\DpnGlossary\Domain\Model\Term;
use PDO;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;

/**
 * @package Featdd\DpnGlossary\Updates
 */
class SlugUpdateWizard extends AbstractUpdateWizard
{
    public const SEGMENT_FIELD = 'name';
    public const SLUG_FIELD = 'url_segment';

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::class;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Auto generate slugs for terms';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'As the new slug field is introduced and you\'re up to use it'
            . ' in your routing configuration, this wizard will autogenerate'
            . ' all slugs for your terms.';
    }

    /**
     * @return bool
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function executeUpdate(): bool
    {
        $queryBuilder = $this->getQueryBuilder();
        /** @var \TYPO3\CMS\Core\DataHandling\SlugHelper $slugHelper */
        $slugHelper = GeneralUtility::makeInstance(
            SlugHelper::class,
            Term::TABLE,
            self::SLUG_FIELD,
            $GLOBALS['TCA'][Term::TABLE]['columns'][self::SLUG_FIELD]['config'] ?? []
        );

        $terms = $this->getEmptySlugTerms();

        foreach ($terms as $term) {
            $termUid = (int) $term['uid'];
            $termPid = (int) $term['pid'];
            $termSlug = $slugHelper->generate($term, $termPid);
            $state = RecordStateFactory::forName(Term::TABLE)->fromArray($term, $termPid, $termUid);

            try {
                if (false === $slugHelper->isUniqueInSite($termSlug, $state)) {
                    $termSlug .= '-' . $termUid;
                }
            } catch (SiteNotFoundException $e) {
                // nothing
            }

            $queryBuilder
                ->update(Term::TABLE)
                ->set(self::SLUG_FIELD, $termSlug)
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($termUid, PDO::PARAM_INT)
                    )
                )
                ->execute();
        }

        return true;
    }

    /**
     * @return bool
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function updateNecessary(): bool
    {
        return 0 < \count($this->getEmptySlugTerms());
    }

    /**
     * Returns an array of class names of Prerequisite classes
     *
     * This way a wizard can define dependencies like "database up-to-date" or
     * "reference index updated"
     *
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        return [DatabaseUpdatedPrerequisite::class];
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function getEmptySlugTerms(): array
    {
        $queryBuilder = $this->getQueryBuilder();

        return $queryBuilder
            ->select('pid', 'uid', 'sys_language_uid', self::SEGMENT_FIELD, self::SLUG_FIELD)
            ->from(Term::TABLE)
            ->where($queryBuilder->expr()->isNotNull(self::SLUG_FIELD))
            ->execute()
            ->fetchAll();
    }
}
