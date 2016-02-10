<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Daniel Dorndorf <dorndorf@dreipunktnull.com>, dreipunktnull
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
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class dpnglossaryGlossarylistWizicon {

	/**
	 * Processing the wizard items array
	 *
	 * @param array $wizardItems The wizard items
	 * @return array Modified array with wizard items
	 */
	function proc($wizardItems)     {
		$wizardItems['plugins_tx_dpnglossary_glossarylist'] = array(
			'icon' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('dpn_glossary') . 'Resources/Public/Icons/plugin.png',
			'title' => $GLOBALS['LANG']->sL('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_list_title'),
			'description' => $GLOBALS['LANG']->sL('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_list_description'),
			'params' => '&defVals[tt_content][CType]=list&&defVals[tt_content][list_type]=dpnglossary_glossarylist'
		);

		$wizardItems['plugins_tx_dpnglossary_glossarydetails'] = array(
			'icon' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('dpn_glossary') . 'Resources/Public/Icons/plugin.png',
			'title' => $GLOBALS['LANG']->sL('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_detail_title'),
			'description' => $GLOBALS['LANG']->sL('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_detail_description'),
			'params' => '&defVals[tt_content][CType]=list&&defVals[tt_content][list_type]=dpnglossary_glossarydetail'
		);

		return $wizardItems;
	}
}
