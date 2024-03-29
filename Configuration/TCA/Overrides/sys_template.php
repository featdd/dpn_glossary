<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(
    function () {
        ExtensionManagementUtility::addStaticFile(
            'dpn_glossary',
            'Configuration/TypoScript',
            'dreipunktnull Glossar'
        );
    }
);
