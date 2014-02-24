<?php
namespace Dpn\DpnGlossary\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Daniel Dorndorf <dorndorf@dreipunktnull.com>, Dreipunktnull
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

use Dpn\DpnGlossary\Domain\Model\Term;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ArrayUtility;

/**
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class WrapperService implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
	 */
	protected $cObj;

	/**
	 * @var array $tsConfig
	 */
	protected  $tsConfig;

	/**
	 * @return void
	 */
	public function contentParser() {
		if (FALSE === $this->objectManager instanceof \TYPO3\CMS\Extbase\Object\ObjectManager) {
			// Make instance of Object Manager
			$objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
			// Get Configuration Manager
			$configurationManager = $objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManager');
			// Inject Content Object Renderer
			$this->cObj = $objectManager->get('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
			// Get Query Settings
			$querySettings = $objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface');
			// Get termRepository
			$termRepository = $objectManager->get('Dpn\DpnGlossary\Domain\Repository\TermRepository');
			// Get Typoscript Configuration
			$this->tsConfig = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
			// Reduce TS config to plugin
			$this->tsConfig = $this->tsConfig['plugin.']['tx_dpnglossary.'];
			// Set StoragePid in the query settings object
			$querySettings->setStoragePageIds(GeneralUtility::trimExplode(',', $this->tsConfig['persistence.']['storagePid']));
			// Assign query settings object to repository
			$termRepository->setDefaultQuerySettings($querySettings);
		}

		$parsingPids = GeneralUtility::trimExplode(',', $this->tsConfig['settings.']['parsingPids']);

		if ((TRUE === in_array($GLOBALS['TSFE']->id, $parsingPids) || TRUE === in_array('0', $parsingPids)) && $GLOBALS['TSFE']->id !== intval($this->tsConfig['settings.']['detailsPid'])) {
			//Find all Terms
			$terms = $termRepository->findAll();

			$maxReplacementPerPage = (int) $this->tsConfig['settings.']['maxReplacementPerPage'];

			//Search whole content for Terms and replace them
			foreach ($terms as $term) {
				if (1 === preg_match('/\b' . $term->getName() . '\b/i', $GLOBALS['TSFE']->content)) {
					$GLOBALS['TSFE']->content = preg_replace('/\b' . $term->getName() . '\b/i', $this->termWrapper($term), $GLOBALS['TSFE']->content, $maxReplacementPerPage);
				}
			}
		}
	}

	/**
	 * @param \Dpn\DpnGlossary\Domain\Model\Term
	 * @return string
	 */
	public function termWrapper(Term $term) {
		$ts         = $this->tsConfig['settings.']['termWraps'];
		$tsArr      = $this->tsConfig['settings.']['termWraps.'];
		$this->cObj->start($term->toArray());
		return $this->cObj->cObjGetSingle($ts, $tsArr);
	}
}
