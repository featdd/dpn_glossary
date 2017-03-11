<?php
namespace Featdd\DpnGlossary\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Daniel Dorndorf <dorndorf@featdd.de>
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

use Featdd\DpnGlossary\Hook\RenderPreProcessHook;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BacklinkViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var \Featdd\DpnGlossary\Service\LinkService
     * @inject
     */
    protected $linkService;

    /**
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = $objectManager->get(ConfigurationManager::class);

        $this->settings = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'dpnglossary');
    }

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerUniversalTagAttributes();
        $this->registerArgument('absolute', 'bool', 'Should the link be absolute', false, false);
    }

    /**
     * @return string
     */
    public function render()
    {
        if (true === (bool) $this->settings['useHttpReferer']) {
            $httpReferer = GeneralUtility::getIndpEnv('HTTP_REFERER');

            $url = false === empty($httpReferer)
                ? $httpReferer
                : $this->getLink();
        } else {
            $getParams = GeneralUtility::_GET(RenderPreProcessHook::URL_PARAM_DETAIL);

            $url = true === array_key_exists('pageUid', $getParams)
                ? $this->getLink($getParams['pageUid'])
                : $this->getLink();
        }

        $this->tag->addAttribute('href', $url);

        $this->tag->setContent(
            $this->renderChildren()
        );

        $this->tag->forceClosingTag(true);

        return $this->tag->render();
    }

    /**
     * @param int $pageUid
     * @return string
     */
    protected function getLink($pageUid = null)
    {
        $pageUid = null === $pageUid
            ? $this->settings['listPage']
            : $pageUid;

        return $this->linkService->buildLink(
            $pageUid,
            array(),
            (bool) $this->arguments['absolute'],
            $GLOBALS['TSFE']->sys_language_uid
        );
    }
}
