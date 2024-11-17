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

/**
 * @package Featdd\DpnGlossary\Updates
 */
class PluginCTypeMigrationUpdateWizard extends AbstractUpdateWizard
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
        return 'Update old plugin registration style';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Having the vendor name in the plugin registration'
            . ' is not needed anymore, and breaks the plugin in v12.'
            . ' This update migrates your existing plugins to an own CType.';
    }

    /**
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        return [PluginSwitchableControllerMigrationUpdateWizard::class];
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
        $queryBuilder = $this->getQueryBuilder('tt_content');
        $pluginRecords = $this->getOldPluginRecords();

        foreach ($pluginRecords as $pluginRecord) {
            $queryBuilder
                ->update('tt_content')
                ->set(
                    'CType',
                    match ($pluginRecord['list_type']) {
                        'dpnglossary_glossary', 'featdd.dpnglossary_glossary' => 'dpnglossary_glossary',
                        'dpnglossary_glossarypreview', 'featdd.dpnglossary_glossarypreview' => 'dpnglossary_glossarypreview',
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
            ->select('uid', 'list_type')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('list')),
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq(
                        'list_type',
                        $queryBuilder->createNamedParameter('featdd.dpnglossary_glossary')
                    ),
                    $queryBuilder->expr()->eq(
                        'list_type',
                        $queryBuilder->createNamedParameter('dpnglossary_glossary')
                    ),
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
