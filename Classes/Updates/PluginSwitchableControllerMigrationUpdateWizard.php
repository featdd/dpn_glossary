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
 *  (c) 2024 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package Featdd\DpnGlossary\Updates
 */
class PluginSwitchableControllerMigrationUpdateWizard extends AbstractUpdateWizard
{
    /**
     * This method is still necessary in TYPO3 v11
     *
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
        return 'Migrate switchable controller previews plugins to new seperate plugins';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Switchable controller actions were removed and should be replaced.';
    }

    /**
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        return [];
    }

    /**
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function updateNecessary(): bool
    {
        return count($this->getOldPluginRecords()) > 0;
    }

    /**
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function executeUpdate(): bool
    {
        /** @var \TYPO3\CMS\Core\Service\FlexFormService $flexFormService */
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);

        $queryBuilder = $this->getQueryBuilder('tt_content');
        $pluginRecords = $this->getOldPluginRecords();

        foreach ($pluginRecords as $pluginRecord) {
            $flexFormXml = $pluginRecord['pi_flexform'];
            $flexForm = $flexFormService->convertFlexFormContentToArray($flexFormXml);

            $queryBuilder
                ->update('tt_content')
                ->set(
                    'CType',
                    match ($flexForm['switchableControllerActions'] ?? '') {
                        'Term->previewRandom' => 'dpnglossary_glossarypreviewrandom',
                        'Term->previewSelected' => 'dpnglossary_glossarypreviewselected',
                        default => 'dpnglossary_glossarypreviewnewest',
                    }
                )
                ->set('list_type', '')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($pluginRecord['uid'], ParameterType::INTEGER)
                    )
                )
                ->executeStatement();
        }

        return true;
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    protected function getOldPluginRecords(): array
    {
        $queryBuilder = $this->getQueryBuilder('tt_content');

        return $queryBuilder
            ->select('uid', 'list_type', 'pi_flexform')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('dpnglossary_glossarypreview')),
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('list')),
                        $queryBuilder->expr()->or(
                            $queryBuilder->expr()->eq(
                                'list_type',
                                $queryBuilder->createNamedParameter('featdd.dpnglossary_glossarypreview')
                            ),
                            $queryBuilder->expr()->eq(
                                'list_type',
                                $queryBuilder->createNamedParameter('dpnglossary_glossarypreview')
                            )
                        )
                    ),
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
