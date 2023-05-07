<?php
declare(strict_types=1);

use Featdd\DpnGlossary\Domain\Model\Term;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die();

call_user_func(
    function () {
        /** @var \TYPO3\CMS\Core\Configuration\ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);

        try {
            $termSlugEvaluation = $extensionConfiguration->get('dpn_glossary', 'termSlugEvaluation');
        } catch (ExtensionConfigurationExtensionNotConfiguredException|ExtensionConfigurationPathDoesNotExistException $exception) {
            $termSlugEvaluation = 'unique';
        }

        $GLOBALS['TCA'][Term::TABLE]['columns']['url_segment']['config']['eval'] = $termSlugEvaluation;
    }
);
