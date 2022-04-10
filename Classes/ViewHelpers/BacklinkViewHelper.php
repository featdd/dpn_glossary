<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\ViewHelpers;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Service\LinkService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * @package Featdd\DpnGlossary\ViewHelpers
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
    protected $settings = [];

    /**
     * @var \Featdd\DpnGlossary\Service\LinkService
     */
    protected $linkService;

    /**
     * @param \Featdd\DpnGlossary\Service\LinkService $linkService
     */
    public function injectLinkService(LinkService $linkService): void
    {
        $this->linkService = $linkService;
    }

    /**
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function initialize(): void
    {
        parent::initialize();

        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);

        $this->settings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'dpnglossary');
    }

    public function initializeArguments(): void
    {
        $this->registerUniversalTagAttributes();
        $this->registerArgument('absolute', 'bool', 'Should the link be absolute', false, false);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $httpReferer = GeneralUtility::getIndpEnv('HTTP_REFERER');

        $url = false === empty($httpReferer)
            ? $httpReferer
            : 'javascript:history.back(1)';

        $this->tag->addAttribute('href', $url);

        $this->tag->setContent(
            $this->renderChildren()
        );

        $this->tag->forceClosingTag(true);

        return $this->tag->render();
    }
}
