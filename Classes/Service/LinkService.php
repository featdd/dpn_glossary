<?php
namespace Featdd\DpnGlossary\Service;

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

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class LinkService implements SingletonInterface
{
    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    protected $uriBuilder;

    /**
     * @return LinkService
     */
    public function __construct()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var ConfigurationManager $configurationManager */
        $configurationManager = $objectManager->get(ConfigurationManager::class);
        /** @var ContentObjectRenderer $contentObjectRenderer */
        $contentObjectRenderer = $objectManager->get(ContentObjectRenderer::class);
        $configurationManager->setContentObject($contentObjectRenderer);
        $this->uriBuilder = $objectManager->get(UriBuilder::class);
        $this->uriBuilder->injectConfigurationManager($configurationManager);
    }

    /**
     * @param int   $pageId
     * @param array $arguments
     * @param bool  $absolut
     * @param int   $sysLanguageUid
     * @return string
     */
    public function buildLink($pageId, array $arguments = array(), $absolut = false, $sysLanguageUid = 0)
    {
        if (0 < $sysLanguageUid) {
            $arguments = array_merge(
                array('L' => $sysLanguageUid),
                $arguments
            );
        }

        return $this->uriBuilder
            ->reset()
            ->setTargetPageUid($pageId)
            ->setArguments($arguments)
            ->setCreateAbsoluteUri($absolut)
            ->buildFrontendUri();
    }
}
