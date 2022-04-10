<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\UserFunc;

use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSlug;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\Entity\NullSite;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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

/**
 * @package Featdd\DpnGlossary\UserFunc
 */
class SlugPreviewUserFunc
{
    /**
     * @var array
     */
    protected $settings = [];

    public function __construct()
    {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);

        try {
            $this->settings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'dpnglossary');
        } catch (InvalidConfigurationTypeException $exception) {
            $this->settings = [];
        }
    }

    /**
     * @param array $parameters
     * @param \TYPO3\CMS\Backend\Form\FormDataProvider\TcaSlug $tcaSlug
     * @return string|null
     */
    public function slugPrefixUserFunc(array $parameters, TcaSlug $tcaSlug): ?string
    {
        $detailPageUid = (int) ($this->settings['detailPage'] ?? null);

        if (0 < $detailPageUid) {
            /** @var \TYPO3\CMS\Core\Site\Entity\Site $site */
            $site = $parameters['site'];
            $languageId = $parameters['languageId'];

            if ($site instanceof NullSite) {
                $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

                try {
                    $site = $siteFinder->getSiteByPageId($detailPageUid);
                } catch (SiteNotFoundException $exception) {
                    return LocalizationUtility::translate('tx_dpnglossary.error.slug_preview_site_missing', 'dpn_glossary');
                }
            }

            $prefixUrl = (string) $site->getRouter()->generateUri(
                $detailPageUid,
                [
                    '_language' => 0 < $languageId
                        ? $site->getLanguageById($languageId)
                        : $site->getDefaultLanguage(),
                    'tx_dpnglossary_glossary' => [
                        'action' => 'show',
                        'controller' => 'Term',
                        'term' => 'SLUG',
                    ],
                ]
            );

            return preg_replace('/SLUG\/?$/', '', $prefixUrl);
        }

        return null;
    }
}
