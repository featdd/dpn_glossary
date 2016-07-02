<?php
namespace Featdd\DpnGlossary\Hook;

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

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RenderPreProcessHook
{
    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var UriBuilder
     */
    protected $uriBuilder;

    public function __construct()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->uriBuilder = $objectManager->get(UriBuilder::class);
        /** @var ConfigurationManager $configurationManager */
        $configurationManager = $objectManager->get(ConfigurationManager::class);
        $tsConfig = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $settings = $tsConfig['plugin.']['tx_dpnglossary.']['settings.'];
        $this->settings = GeneralUtility::removeDotsFromTS($settings);
    }

    /**
     * @param array        $params
     * @param PageRenderer $pageRenderer
     */
    public function main(array &$params, PageRenderer $pageRenderer)
    {
        $getParams = $_GET['tx_dpnglossary_glossarydetail'];

        if (
            $GLOBALS['TSFE']->id === (integer) $this->settings['detailPage'] &&
            true === (boolean) $this->settings['addCanonicalUrl'] &&
            array_key_exists('pageUid' ,$getParams)
        ) {
            $url = $this->uriBuilder
                ->setTargetPageUid($this->settings['detailPage'])
                ->setArguments(array(
                    'tx_dpnglossary_glossarydetail[term]' => $getParams['term']
                ))
                ->buildFrontendUri();

            $pageRenderer->addHeaderData('<link rel="canonical" href="' . $url . '"/>');
        }
    }
}
