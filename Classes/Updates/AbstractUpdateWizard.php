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
 *  (c) 2026 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Doctrine\DBAL\Schema\Column;
use Featdd\DpnGlossary\Domain\Model\TermInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * @package Featdd\DpnGlossary\Updates
 */
abstract class AbstractUpdateWizard implements UpgradeWizardInterface
{
    /**
     * @param string $tablename
     * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
     */
    protected function getQueryBuilder(string $tablename = TermInterface::TABLE): QueryBuilder
    {
        /** @var \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable($tablename);
        $queryBuilder->getRestrictions()->removeAll();

        /** @var \TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction $deletedRestriction */
        $deletedRestriction = GeneralUtility::makeInstance(DeletedRestriction::class);

        $queryBuilder->getRestrictions()->add($deletedRestriction);

        return $queryBuilder;
    }

    /**
     * @param string $tableName
     * @param string $columnName
     */
    protected function tableColumnExists(string $tableName, string $columnName): bool
    {
        /** @var \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $schemaManager = $connectionPool->getConnectionForTable($tableName)->createSchemaManager();

        $tableColumns = array_filter(
            // TODO: Switch method to "introspectTableColumns" when upgrading to TYPO3 v15
            $schemaManager->listTableColumns($tableName),
            fn(Column $column) => $column->getObjectName()->getIdentifier()->getValue() === $columnName
        );

        return count($tableColumns) > 0;
    }
}
