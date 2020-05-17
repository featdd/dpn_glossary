<?php
defined('TYPO3_MODE') || die();

call_user_func(
    function () {
        /** @var \TYPO3\CMS\Core\Configuration\ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
        );

        try {
            $termSlugEvaluation = $extensionConfiguration->get('dpn_glossary', 'termSlugEvaluation');
        } catch (
        \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException |
        \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException $exception
        ) {
            $termSlugEvaluation = 'unique';
        }

        $GLOBALS['TCA'][\Featdd\DpnGlossary\Domain\Model\Term::TABLE]['columns']['url_segment']['config']['eval'] = $termSlugEvaluation;
    }
);
