<?php
namespace Dpn\DpnGlossary\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Daniel Dorndorf <dorndorf@dreipunktnull.com>, Dreipunktnull
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
use Dpn\DpnGlossary\Domain\Repository\TermRepository;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class WrapperService implements SingletonInterface {

	/**
	 * @var ContentObjectRenderer $cObj
	 */
	protected $cObj;

	/**
	 * @var array $tsConfig
	 */
	protected $tsConfig;

	/**
	 * @var objectManager $objectManager
	 */
	protected $objectManager;

	/**
	 * @var TermRepository $termRepository
	 */
	protected $termRepository;

	/**
	 * @var integer $maxReplacementPerPage
	 */
	protected $maxReplacementPerPage;

	/**
	 * @return void
	 */
	public function contentParser() {
		if (0 === $GLOBALS['TSFE']->type) {
			if (FALSE === $this->objectManager instanceof ObjectManager) {
				// Make instance of Object Manager
				$objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
				// Get Configuration Manager
				/** @var ConfigurationManager $configurationManager */
				$configurationManager = $objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManager');
				// Inject Content Object Renderer
				$this->cObj = $objectManager->get('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
				// Get Query Settings
				/** @var QuerySettingsInterface $querySettings */
				$querySettings = $objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface');
				// Get termRepository
				$this->termRepository = $objectManager->get('Dpn\DpnGlossary\Domain\Repository\TermRepository');
				// Get Typoscript Configuration
				$this->tsConfig = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
				// Reduce TS config to plugin
				$this->tsConfig = $this->tsConfig['plugin.']['tx_dpnglossary.'];
				// Exit if no termStorage is setted
				if (TRUE === empty($this->tsConfig['persistence.']['storagePid'])) {
					return;
				}
				// Set StoragePid in the query settings object
				$querySettings->setStoragePageIds(GeneralUtility::trimExplode(',', $this->tsConfig['persistence.']['storagePid']));
				// Assign query settings object to repository
				$this->termRepository->setDefaultQuerySettings($querySettings);
			}

			if (!isset($this->tsConfig['settings.']['parsingExcludePidList'])) {
				$this->tsConfig['settings.']['parsingExcludePidList'] = '';
			}

			$parsingPids = GeneralUtility::trimExplode(',', $this->tsConfig['settings.']['parsingPids']);
			$excludePids = GeneralUtility::trimExplode(',', $this->tsConfig['settings.']['parsingExcludePidList']);

			if (FALSE === in_array($GLOBALS['TSFE']->id, $excludePids) && (TRUE === in_array($GLOBALS['TSFE']->id, $parsingPids) || TRUE === in_array('0', $parsingPids)) && $GLOBALS['TSFE']->id !== intval($this->tsConfig['settings.']['detailsPid'])) {
				//Get max number of replacements per page and term
				$this->maxReplacementPerPage = (int)$this->tsConfig['settings.']['maxReplacementPerPage'];
				//Get Tags which content should be parsed
				$tags = GeneralUtility::trimExplode(',', $this->tsConfig['settings.']['parsingTags']);
				// Exit if no Terms were set
				if (TRUE === empty($tags)) {
					return;
				}
				//Create new DOMDocument
				$DOM = new \DOMDocument();
				//Prevent crashes caused by HTML5 entities with internal errors
				libxml_use_internal_errors(true);
				//Load Page HTML in DOM and check if HTML is valid else abort
				if (FALSE === $DOM->loadHTML('<?xml encoding="UTF-8">' . $GLOBALS['TSFE']->content)) {return;}
				$DOM->preserveWhiteSpace = false;
				/** @var \DOMElement $DOMBody */
				$DOMBody = $DOM->getElementsByTagName('body')->item(0);

				foreach ($tags as $tag) {
					$DOMTags = $DOMBody->getElementsByTagName($tag);
					foreach ($DOMTags as $DOMTag) {
						$this->nodeReplacer($DOMTag);
					}
				}
				//Return parsed html with decoded html entities and UTF-8 encoding
				$GLOBALS['TSFE']->content = html_entity_decode(str_replace('<?xml encoding="UTF-8">', '', $DOM->saveHTML()));
			}
		}
	}

	/**
	 * @param \DOMNode $DOMTag
	 * @return void
	 */
	protected function nodeReplacer(\DOMNode $DOMTag) {
		$tempDOM = new \DOMDocument();
		$tempDOM->loadHTML(
			'<?xml encoding="UTF-8">' .
			$this->htmlTagParser(
				$DOMTag->ownerDocument->saveHTML($DOMTag)
			)
		);
		$DOMTag->parentNode->replaceChild($DOMTag->ownerDocument->importNode($tempDOM->getElementsByTagName('body')->item(0)->childNodes->item(0), TRUE), $DOMTag);
	}

	/**
	 * @param string $html
	 * @return string
	 */
	protected function htmlTagParser($html) {
		//Start of content to be parsed
		$start = stripos($html, '>') + 1;
		//End of content to be parsed
		$end = strripos($html, '<');
		//Length of the content
		$length = $end - $start;
		//Paste everything between to textparser
		$parsed = $this->textParser(substr($html, $start, $length));
		//Replacing with parsed content
		$html = substr_replace($html, $parsed, $start, $length);

		return $html;
	}

	/**
	 * @param string $text
	 * @return string
	 */
	protected function textParser($text) {
		$terms = $this->termRepository->findAll();
		//Search whole content for Terms and replace them
		/** @var Term $term */
		foreach ($terms as $term) {
			$regex = '#(^|[\s\>[:punct:]])(' . preg_quote($term->getName()) . ')($|[\s\<[:punct:]])(?![^<]*>|[^<>]*<\/)#is';
			if (1 === preg_match($regex, $text)) {
				$text = preg_replace_callback(
					$regex,
					function($match) use ($term) {
						// Use term match to keep lowercase etc.
						$term->setName($match[2]);
						return $match[1] . $this->termWrapper($term) . $match[3];
					},
					$text, $this->maxReplacementPerPage
				);
			}
		}
		return $text;
	}

	/**
	 * @param \Dpn\DpnGlossary\Domain\Model\Term
	 * @return string
	 */
	protected function termWrapper(Term $term) {
		$ts = $this->tsConfig['settings.']['termWraps'];
		$tsArr = $this->tsConfig['settings.']['termWraps.'];
		$this->cObj->start($term->toArray());
		return $this->cObj->cObjGetSingle($ts, $tsArr);
	}
}