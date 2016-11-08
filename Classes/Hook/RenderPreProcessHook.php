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

use Featdd\DpnGlossary\Service\LinkService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RenderPreProcessHook
{
    const URL_PARAM_DETAIL = 'tx_dpnglossary_glossarydetail';
    const URL_PARAM_DETAIL_TERM_KEY = '[term]';

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var \Featdd\DpnGlossary\Service\LinkService
     */
    protected $linkService;

    /**
     * @return RenderPreProcessHook
     */
    public function __construct()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->linkService = $objectManager->get(LinkService::class);
        /** @var ConfigurationManager $configurationManager */
        $configurationManager = $objectManager->get(ConfigurationManager::class);
        $tsConfig = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $settings = $tsConfig['plugin.']['tx_dpnglossary.']['settings.'];

        if (is_array($settings)) {
            $this->settings = GeneralUtility::removeDotsFromTS($settings);
        }
    }

    /**
     * @param array        $params
     * @param PageRenderer $pageRenderer
     */
    public function main(array &$params, PageRenderer $pageRenderer)
    {
        $getParams = GeneralUtility::_GET(self::URL_PARAM_DETAIL);

        if (
            0 < count($this->settings) &&
            $GLOBALS['TSFE']->id === (integer) $this->settings['detailPage'] &&
            true === (boolean) $this->settings['addCanonicalUrl'] &&
            array_key_exists('pageUid', $getParams)
        ) {
            $url = $this->linkService->buildLink(
                $this->settings['detailPage'],
                array(
                    self::URL_PARAM_DETAIL . self::URL_PARAM_DETAIL_TERM_KEY => $getParams['term'],
                ),
                true,
                $GLOBALS['TSFE']->sys_language_uid
            );

            $pageRenderer->addHeaderData('<link rel="canonical" href="' . $url . '"/>');
        }
    }
}
