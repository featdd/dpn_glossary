<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Daniel Dorndorf <dorndorf@dreipunktnull.com>, dreipunktnull
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
class ext_update {

	/**
	 * @return boolean
	 */
	public function access() {
		return TRUE;
	}

	/**
	 * @return string
	 */
	public function main() {
		//Check if changes already applied by checking existence of the descriptions column
		/** @var mysqli_result $check */
		$check = $GLOBALS['TYPO3_DB']->sql_query("SHOW COLUMNS FROM tx_dpnglossary_domain_model_term LIKE 'descriptions'");

		if (0 === $check->num_rows) {
			$GLOBALS['TYPO3_DB']->sql_query("
				ALTER TABLE tx_dpnglossary_domain_model_term
				ADD descriptions int(11) unsigned DEFAULT '0'
			");

			$GLOBALS['TYPO3_DB']->sql_query("
				CREATE TABLE IF NOT EXISTS tx_dpnglossary_domain_model_description (

					uid int(11) NOT NULL auto_increment,
					pid int(11) DEFAULT '0' NOT NULL,

					term int(11) unsigned DEFAULT '0' NOT NULL,
					meaning varchar(255) DEFAULT '' NOT NULL,
					text text NOT NULL,

					tstamp int(11) unsigned DEFAULT '0' NOT NULL,
					crdate int(11) unsigned DEFAULT '0' NOT NULL,
					cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
					deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
					hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
					starttime int(11) unsigned DEFAULT '0' NOT NULL,
					endtime int(11) unsigned DEFAULT '0' NOT NULL,

					t3ver_oid int(11) DEFAULT '0' NOT NULL,
					t3ver_id int(11) DEFAULT '0' NOT NULL,
					t3ver_wsid int(11) DEFAULT '0' NOT NULL,
					t3ver_label varchar(255) DEFAULT '' NOT NULL,
					t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
					t3ver_stage int(11) DEFAULT '0' NOT NULL,
					t3ver_count int(11) DEFAULT '0' NOT NULL,
					t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
					t3ver_move_id int(11) DEFAULT '0' NOT NULL,

					t3_origuid int(11) DEFAULT '0' NOT NULL,
					sys_language_uid int(11) DEFAULT '0' NOT NULL,
					l10n_parent int(11) DEFAULT '0' NOT NULL,
					l10n_diffsource mediumblob,

					PRIMARY KEY (uid),
					KEY parent (pid),
					KEY t3ver_oid (t3ver_oid,t3ver_wsid),
					KEY language (l10n_parent,sys_language_uid)

				);
			");


			$results = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,tooltiptext,description', 'tx_dpnglossary_domain_model_term', '', '', '');
			foreach($results as $result) {
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_dpnglossary_domain_model_description', array(
					'term' => $result['uid'],
					'meaning' => $result['tooltiptext'],
					'text' => $result['description'],
				));
			}
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_dpnglossary_domain_model_term', '', array('descriptions' => 1));

			$GLOBALS['TYPO3_DB']->sql_query("
				ALTER TABLE tx_dpnglossary_domain_model_term
				CHANGE description zzz_deleted_description text NOT NULL
			");

			return $GLOBALS['TYPO3_DB']->sql_affected_rows() . ' rows have been updated. System object caches cleared.';
		}

		/** @var mysqli_result $checkSortingColumn */
		$checkSortingColumn = $GLOBALS['TYPO3_DB']->sql_query("SHOW COLUMNS FROM tx_dpnglossary_domain_model_description LIKE 'sorting'");

		if (0 === $checkSortingColumn->num_rows) {
			$GLOBALS['TYPO3_DB']->sql_query("
				ALTER TABLE tx_dpnglossary_domain_model_description
				ADD sorting int(11) unsigned DEFAULT '0' NOT NULL
			");

			return 'The missing sorting column was added to descriptions';
		}

		return 'Extension is already updated!';
	}
}
