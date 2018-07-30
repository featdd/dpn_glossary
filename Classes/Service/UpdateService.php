<?php
namespace Featdd\DpnGlossary\Service;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @package DpnGlossary
 * @subpackage Service
 */
class UpdateService implements SingletonInterface
{
    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $databaseConnection;

    /**
     * @var string
     */
    protected $currentVersion;

    /**
     * @var array
     */
    protected $updateChecks = array();

    /**
     * @throws \TYPO3\CMS\Core\Package\Exception
     */
    public function __construct()
    {
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
        $this->currentVersion = ExtensionManagementUtility::getExtensionVersion('dpn_glossary');
    }

    /**
     * @return bool
     */
    public function isUpdateNecessary()
    {
        $this->checkUpdatesNecessarity();

        return !empty($this->updateChecks);
    }

    /**
     * @return void
     */
    public function makeUpdates()
    {
        foreach ($this->updateChecks as $updateMethod => $updateNotice) {
            if (method_exists($this, $updateMethod)) {
                call_user_func(array($this, $updateMethod));
            }
        }
    }

    /**
     * @return string
     */
    public function getUpdateNotices()
    {
        $updateNotices = '<ul>' . PHP_EOL;

        foreach ($this->updateChecks as $updateNotice) {
            $updateNotices .= '<li>' . $updateNotice . '</li>' . PHP_EOL;
        }

        $updateNotices .= '</ul>';

        return $updateNotices;
    }

    /**
     * @return void
     */
    protected function checkUpdatesNecessarity()
    {
        $this->checkMissingDescriptions();
        $this->checkMediaColumn();
        $this->checkSortingColumn();
        $this->checkSynonymTable();
        $this->checkExcludeFromParsingColumn();
        $this->checkTermModeAndTermLinkColumn();
        $this->checkCaseSensitiveColumn();
    }

    /**
     * @return void
     */
    protected function checkMissingDescriptions()
    {
        /** @var \mysqli_result $checkSortingColumn */
        $check = $this->databaseConnection->sql_query('SHOW COLUMNS FROM tx_dpnglossary_domain_model_term LIKE "descriptions"');

        if (0 === $check->num_rows) {
            $this->updateChecks['updateMissingDescriptions'] = 'The missing "Descriptions" column & table was added';
        }
    }

    protected function updateMissingDescriptions()
    {
        $this->databaseConnection->sql_query('
            ALTER TABLE tx_dpnglossary_domain_model_term
            ADD descriptions int(11) UNSIGNED DEFAULT \'0\'
        ');
        $this->databaseConnection->sql_query('
            CREATE TABLE tx_dpnglossary_domain_model_description (

                uid              int(11)                           NOT NULL AUTO_INCREMENT,
                pid              int(11) DEFAULT \'0\'             NOT NULL,
            
                sorting          int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
            
                term             int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
                meaning          varchar(255) DEFAULT \'\'         NOT NULL,
                text             text                              NOT NULL,
            
                tstamp           int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
                crdate           int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
                cruser_id        int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
                deleted          tinyint(4) UNSIGNED DEFAULT \'0\' NOT NULL,
                hidden           tinyint(4) UNSIGNED DEFAULT \'0\' NOT NULL,
                starttime        int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
                endtime          int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
            
                t3ver_oid        int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_id         int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_wsid       int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_label      varchar(255) DEFAULT \'\'         NOT NULL,
                t3ver_state      tinyint(4) DEFAULT \'0\'          NOT NULL,
                t3ver_stage      int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_count      int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_tstamp     int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_move_id    int(11) DEFAULT \'0\'             NOT NULL,
            
                t3_origuid       int(11) DEFAULT \'0\'             NOT NULL,
                sys_language_uid int(11) DEFAULT \'0\'             NOT NULL,
                l10n_parent      int(11) DEFAULT \'0\'             NOT NULL,
                l10n_diffsource  mediumblob,
            
                PRIMARY KEY (uid),
                KEY parent (pid),
                KEY t3ver_oid (t3ver_oid,t3ver_wsid),
                KEY language (l10n_parent,sys_language_uid)
            );
        ');

        $results = $this->databaseConnection->exec_SELECTquery(
            'uid,tooltiptext,description',
            'tx_dpnglossary_domain_model_term',
            ''
        );

        foreach ($results as $result) {
            $this->databaseConnection->exec_INSERTquery('tx_dpnglossary_domain_model_description', array(
                'term' => $result['uid'],
                'meaning' => $result['tooltiptext'],
                'text' => $result['description'],
            ));
        }

        $this->databaseConnection->exec_UPDATEquery(
            'tx_dpnglossary_domain_model_term',
            '',
            array('descriptions' => 1)
        );

        $this->databaseConnection->sql_query('
            ALTER TABLE tx_dpnglossary_domain_model_term
            CHANGE description zzz_deleted_description text NOT NULL
        ');
    }

    /**
     * @return void
     */
    protected function checkSortingColumn()
    {
        /** @var \mysqli_result $checkSortingColumn */
        $check = $this->databaseConnection->sql_query('SHOW COLUMNS FROM tx_dpnglossary_domain_model_description LIKE "sorting"');

        if (0 === $check->num_rows) {
            $this->updateChecks['updateSortingColumn'] = 'The missing sorting column was added to descriptions';
        }
    }

    /**
     * @return void
     */
    protected function updateSortingColumn()
    {
        $this->databaseConnection->sql_query('
            ALTER TABLE tx_dpnglossary_domain_model_description
            ADD sorting int(11) UNSIGNED DEFAULT \'0\' NOT NULL
        ');
    }

    /**
     * @return void
     */
    protected function checkMediaColumn()
    {
        /** @var \mysqli_result $checkSortingColumn */
        $check = $this->databaseConnection->sql_query('SHOW COLUMNS FROM tx_dpnglossary_domain_model_description LIKE "images"');

        if (0 < $check->num_rows) {
            $this->updateChecks['updateMediaColumn'] = 'Images field changed to media field';
        }
    }

    /**
     * @return void
     */
    protected function updateMediaColumn()
    {
        $this->databaseConnection->sql_query('
            ALTER TABLE tx_dpnglossary_domain_model_term
            CHANGE COLUMN images media int(11) UNSIGNED DEFAULT \'0\';
        ');
    }

    /**
     * @return void
     */
    protected function checkSynonymTable()
    {
        /** @var \mysqli_result $checkSortingColumn */
        $check = $this->databaseConnection->sql_query('SHOW TABLES LIKE "tx_dpnglossary_domain_model_synonym"');

        if (0 === $check->num_rows) {
            $this->updateChecks['updateSynonymTable'] = 'Added synonym table to your database';
        }
    }

    /**
     * @return void
     */
    protected function updateSynonymTable()
    {
        $this->databaseConnection->sql_query('
            CREATE TABLE tx_dpnglossary_domain_model_synonym (
            
                uid              int(11)                           NOT NULL AUTO_INCREMENT,
                pid              int(11) DEFAULT \'0\'             NOT NULL,
            
                sorting          int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
            
                term             int(11) DEFAULT \'0\'             NOT NULL,
                name             varchar(255) DEFAULT \'\'         NOT NULL,
            
                tstamp           int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
                crdate           int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
                cruser_id        int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
                deleted          tinyint(4) UNSIGNED DEFAULT \'0\' NOT NULL,
                hidden           tinyint(4) UNSIGNED DEFAULT \'0\' NOT NULL,
                starttime        int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
                endtime          int(11) UNSIGNED DEFAULT \'0\'    NOT NULL,
            
                t3ver_oid        int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_id         int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_wsid       int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_label      varchar(255) DEFAULT \'\'         NOT NULL,
                t3ver_state      tinyint(4) DEFAULT \'0\'          NOT NULL,
                t3ver_stage      int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_count      int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_tstamp     int(11) DEFAULT \'0\'             NOT NULL,
                t3ver_move_id    int(11) DEFAULT \'0\'             NOT NULL,
            
                t3_origuid       int(11) DEFAULT \'0\'             NOT NULL,
                sys_language_uid int(11) DEFAULT \'0\'             NOT NULL,
                l10n_parent      int(11) DEFAULT \'0\'             NOT NULL,
                l10n_diffsource  mediumblob,
            
                PRIMARY KEY (uid),
                KEY parent (pid),
                KEY t3ver_oid (t3ver_oid,t3ver_wsid),
                KEY language (l10n_parent,sys_language_uid)
            );
        ');
    }

    /**
     * @return void
     */
    protected function checkExcludeFromParsingColumn()
    {
        /** @var \mysqli_result $checkSortingColumn */
        $check = $this->databaseConnection->sql_query('SHOW COLUMNS FROM tx_dpnglossary_domain_model_term LIKE "exclude_from_parsing"');

        if (0 === $check->num_rows) {
            $this->updateChecks['updateExcludeFromParsingColumn'] = 'Add missing exclude_from_parsing column';
        }
    }

    /**
     * @return void
     */
    protected function updateExcludeFromParsingColumn()
    {
        $this->databaseConnection->sql_query('
            ALTER TABLE tx_dpnglossary_domain_model_term
            ADD exclude_from_parsing tinyint(4) unsigned DEFAULT \'0\' NOT NULL
        ');
    }

    /**
     * @return void
     */
    protected function checkTermModeAndTermLinkColumn()
    {
        /** @var \mysqli_result $checkSortingColumn */
        $check = $this->databaseConnection->sql_query('SHOW COLUMNS FROM tx_dpnglossary_domain_model_term LIKE "term_mode"');

        if (0 === $check->num_rows) {
            $this->updateChecks['updateTermModeAndTermLinkColumn'] = 'Add missing term_mode & term_link column';
        }
    }

    /**
     * @return void
     */
    protected function updateTermModeAndTermLinkColumn()
    {
        $this->databaseConnection->sql_query('
            ALTER TABLE tx_dpnglossary_domain_model_term
            ADD term_mode varchar(255) DEFAULT \'\'  NOT NULL,
            ADD term_link varchar(255) DEFAULT \'\'  NOT NULL
        ');
    }

    /**
     * @return void
     */
    protected function checkCaseSensitiveColumn()
    {
        /** @var \mysqli_result $checkSortingColumn */
        $check = $this->databaseConnection->sql_query('SHOW COLUMNS FROM tx_dpnglossary_domain_model_term LIKE "case_sensitive"');

        if (0 === $check->num_rows) {
            $this->updateChecks['updateCaseSensitiveColumn'] = 'Add missing case_sensitive column';
        }
    }

    /**
     * @return void
     */
    protected function updateCaseSensitiveColumn()
    {
        $this->databaseConnection->sql_query('
            ALTER TABLE tx_dpnglossary_domain_model_term
            ADD case_sensitive tinyint(4) UNSIGNED DEFAULT \'0\' NOT NULL
        ');
    }
}
