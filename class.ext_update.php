<?php
namespace Featdd\DpnGlossary;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Daniel Dorndorf <dorndorf@featdd.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ext_update
{
    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $databaseConnection;

    /**
     * @return ext_update
     */
    public function __construct()
    {
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
    }

    /**
     * @return boolean
     */
    public function access()
    {
        return TRUE;
    }

    /**
     * @return string
     */
    public function main()
    {

        /*
         * Check if changes already applied by checking existence of the descriptions column
         */

        /** @var \mysqli_result $check */
        $check = $this->databaseConnection->sql_query('SHOW COLUMNS FROM tx_dpnglossary_domain_model_term LIKE "descriptions"');

        if (0 === $check->num_rows) {
            $this->databaseConnection->sql_query('
				ALTER TABLE tx_dpnglossary_domain_model_term
				ADD descriptions INT(11) UNSIGNED DEFAULT \'0\'
			');

            $this->databaseConnection->sql_query('
				CREATE TABLE IF NOT EXISTS tx_dpnglossary_domain_model_description (

					uid INT(11) NOT NULL AUTO_INCREMENT,
					pid INT(11) DEFAULT \'0\' NOT NULL,

					term INT(11) UNSIGNED DEFAULT \'0\' NOT NULL,
					meaning VARCHAR(255) DEFAULT \'\' NOT NULL,
					text TEXT NOT NULL,

					tstamp INT(11) UNSIGNED DEFAULT \'0\' NOT NULL,
					crdate INT(11) UNSIGNED DEFAULT \'0\' NOT NULL,
					cruser_id INT(11) UNSIGNED DEFAULT \'0\' NOT NULL,
					deleted TINYINT(4) UNSIGNED DEFAULT \'0\' NOT NULL,
					hidden TINYINT(4) UNSIGNED DEFAULT \'0\' NOT NULL,
					starttime INT(11) UNSIGNED DEFAULT \'0\' NOT NULL,
					endtime INT(11) UNSIGNED DEFAULT \'0\' NOT NULL,

					t3ver_oid INT(11) DEFAULT \'0\' NOT NULL,
					t3ver_id INT(11) DEFAULT \'0\' NOT NULL,
					t3ver_wsid INT(11) DEFAULT \'0\' NOT NULL,
					t3ver_label VARCHAR(255) DEFAULT \'\' NOT NULL,
					t3ver_state TINYINT(4) DEFAULT \'0\' NOT NULL,
					t3ver_stage INT(11) DEFAULT \'0\' NOT NULL,
					t3ver_count INT(11) DEFAULT \'0\' NOT NULL,
					t3ver_tstamp INT(11) DEFAULT \'0\' NOT NULL,
					t3ver_move_id INT(11) DEFAULT \'0\' NOT NULL,

					t3_origuid INT(11) DEFAULT \'0\' NOT NULL,
					sys_language_uid INT(11) DEFAULT \'0\' NOT NULL,
					l10n_parent INT(11) DEFAULT \'0\' NOT NULL,
					l10n_diffsource MEDIUMBLOB,

					PRIMARY KEY (uid),
					KEY parent (pid),
					KEY t3ver_oid (t3ver_oid,t3ver_wsid),
					KEY language (l10n_parent,sys_language_uid)

				);
			');


            $results = $this->databaseConnection->exec_SELECTquery('uid,tooltiptext,description', 'tx_dpnglossary_domain_model_term', '', '', '');
            foreach ($results as $result) {
                $this->databaseConnection->exec_INSERTquery('tx_dpnglossary_domain_model_description', array(
                    'term'    => $result['uid'],
                    'meaning' => $result['tooltiptext'],
                    'text'    => $result['description'],
                ));
            }
            $this->databaseConnection->exec_UPDATEquery('tx_dpnglossary_domain_model_term', '', array('descriptions' => 1));

            $this->databaseConnection->sql_query('
				ALTER TABLE tx_dpnglossary_domain_model_term
				CHANGE description zzz_deleted_description TEXT NOT NULL
			');

            return $this->databaseConnection->sql_affected_rows() . ' rows have been updated.';
        }

        /*
         * Add the missing sorting column
         */

        /** @var \mysqli_result $checkSortingColumn */
        $checkSortingColumn = $this->databaseConnection->sql_query('SHOW COLUMNS FROM tx_dpnglossary_domain_model_description LIKE "sorting"');

        if (0 === $checkSortingColumn->num_rows) {
            $this->databaseConnection->sql_query('
				ALTER TABLE tx_dpnglossary_domain_model_description
				ADD sorting INT(11) UNSIGNED DEFAULT \'0\' NOT NULL
			');

            return 'The missing sorting column was added to descriptions';
        }

        $checkImagesColumn = $this->databaseConnection->sql_query('SHOW COLUMNS FROM tx_dpnglossary_domain_model_description LIKE "images"');

        if (0 < $checkImagesColumn->num_rows) {
            $this->databaseConnection->sql_query('
				ALTER TABLE tx_dpnglossary_domain_model_term
				CHANGE COLUMN images media INT(11) UNSIGNED DEFAULT \'0\';
			');

            return 'Images field changed to media field';
        }

        $checkSynonymTable = $this->databaseConnection->sql_query('SHOW TABLES LIKE "tx_dpnglossary_domain_model_synonym"');

        if (0 === $checkSynonymTable->num_rows) {
            $this->databaseConnection->sql_query('
                CREATE TABLE tx_dpnglossary_domain_model_synonym (

                    uid              int(11)                         NOT NULL auto_increment,
                    pid              int(11) DEFAULT \'0\'             NOT NULL,
                
                    sorting          int(11) unsigned DEFAULT \'0\'    NOT NULL,
                
                    term             int(11) DEFAULT \'0\'             NOT NULL,
                    name             varchar(255) DEFAULT \'\'         NOT NULL,
                
                    tstamp           int(11) unsigned DEFAULT \'0\'    NOT NULL,
                    crdate           int(11) unsigned DEFAULT \'0\'    NOT NULL,
                    cruser_id        int(11) unsigned DEFAULT \'0\'    NOT NULL,
                    deleted          tinyint(4) unsigned DEFAULT \'0\' NOT NULL,
                    hidden           tinyint(4) unsigned DEFAULT \'0\' NOT NULL,
                    starttime        int(11) unsigned DEFAULT \'0\'    NOT NULL,
                    endtime          int(11) unsigned DEFAULT \'0\'    NOT NULL,
                
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

            return 'Added synonym table to your database';
        }

        /*
         * Nothing to change then exit
         */

        return 'Extension is already updated!';
    }
}
