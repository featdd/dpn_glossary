<?php
namespace Featdd\DpnGlossary\Hook;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Service\LinkService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * @package DpnGlossary
 * @subpackage Hook
 */
class RenderPreProcessHook
{
    public const URL_PARAM_DETAIL = 'tx_dpnglossary_glossarydetail';
    public const URL_PARAM_DETAIL_TERM_KEY = '[term]';

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var \Featdd\DpnGlossary\Service\LinkService
     */
    protected $linkService;

    public function __construct()
    {
        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->linkService = $objectManager->get(LinkService::class);
        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = $objectManager->get(ConfigurationManager::class);

        try {
            $settings = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'dpnglossary');

            if (\is_array($settings)) {
                $this->settings = GeneralUtility::removeDotsFromTS($settings);
            }
        } catch (InvalidConfigurationTypeException $e) {
            // should not happen
        }
    }

    /**
     * @param array $params
     * @param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
     */
    public function main(array &$params, PageRenderer $pageRenderer): void
    {
        $getParams = GeneralUtility::_GET(self::URL_PARAM_DETAIL);

        if (
            true === \is_array($getParams) &&
            true === array_key_exists('pageUid', $getParams) &&
            true === (boolean) $this->settings['addCanonicalUrl'] &&
            0 < \count($this->settings) &&
            $GLOBALS['TSFE']->id === (integer) $this->settings['detailPage']
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
