.. _changelog:

=========
ChangeLog
=========

v6.1.3
  * Prevent TypeErrors and handle missing StoragePids properly

v6.1.2
  * Remove "TCEforms" tag from FlexForm XML
  * Use real slug value instead of dummy due to restrictions
  * Code optimizations
  * Add hook to auto clear terms cache if term in storage was edited
  * Don't use range array for trim function if characters are empty

v6.1.1
  * Add missing upgrade wizard registration via PHP attributes
  * Add documentation updates

v6.1.0
  * Add support for PHP 8.4
  * Remove deprecated function calls and other optimizations
  * Add storagePid check for term detail page

v6.0.0
  * Add support for TYPO3 v13
  * Drop support for TYPO3 v11

v5.3.2
  * Use proper icon registry configuration file (thanks to Achim Fritz)

v5.3.1
  * Add safety check for accidentally empty created synonyms

v5.3.0
  * Add transliteration for character grouped term list

v5.2.4
  * Prevent parser href/src protection from redundant base64 encoding

v5.2.3
  * Prevent nested ObjectStorage iterations on synonyms

v5.2.2
  * Use proper version constraint to allow all PHP 8.3.X versions

v5.2.1
  * Update required PHP version

v5.2.0
  * Make the repository used in the parser configurable
  * Add hidden palette for language fields to prevent errors for editors

v5.1.0
  * Add option to exclude parsing for term links target pages

v5.0.4
  * Add missing term link to parsing term
  * Prevent array key warnings
  * Add URL segment to term model
  * Simplify term anchor links and adjust slug preview prefix

v5.0.3
  * Use umlaut count to update matching group index

v5.0.2
  * Use custom replacement function for umlaut matching groups
  * Move ExtensionManagementUtility::addPageTSConfig() to ext_localconf.php

v5.0.1
  * Add missing switchable controller actions migration for preview plugin

v5.0.0
  * Add support for TYPO3 v12 (dropped for TYPO3 v10)

    * The extension TypoScript file extensions have been changed from .txt to .typoscript

  * Add canonical URL for character pagination index

v4.2.0
  * Limit parsing of DOM to configurable node/tag (ID) (thanks to Julian Hofmann)
  * Optimize parser performance with separate simplified term parser object (thanks to Julian Hofmann)
  * Dynamic page based and content based exclusion from parsing (thanks to Lina Wolf)
  * Add menu processor for terms
  * Case sensitivity workaround for umlauts
  * Add page title provider for character pagination and term detailpage
  * Documentation updates
  * Add special option for range routing mapper, for adding special chars

v4.1.0
  * Add SEO title & description for terms
  * Optimize slug preview (no extra configuration needed anymore)

v4.0.0
  * Add support for TYPO3 v11 (dropped for TYPO3 v9)
  * Use of symfony dependency injection
  * Added custom character pagination API to replace widget based pagination

v3.2.4
  * Prevent term parser from self referencing on detail pages

v3.2.3
  * Use correct ordering for newest terms query
  * Use proper title/alt attribute property in file reference

v3.2.2
  * Use multibyte functions to properly process non ascii characters for pagination

v3.2.1
  * Fix TCA sorting for synonyms & descriptions in term inline fields
  * Add option to keep the origin term for data wrap when parsing synonyms

v3.2.0
  * Change parsing order to parse the whole content for each term
  * Add special wrap character option for term regular expression

v3.1.6
  * Add missing renderType for preview FlexForm

v3.1.5
  * Fix broken TypoScript due to an auto indent issue

v3.1.4
  * Add hook to also run parser when config.no_cache is true
  * Add "forbidden parent classes" new parsing exclude feature

v3.1.3
  * Add option to priories synonym parsing before the main term
  * Fixed parsing priority issue with synonyms

v3.1.2
  * Use "unique" as default evaluation for slug to prevent initial errors

v3.1.1
  * Add better evaluation for the term slug

v3.1.0
  * Add compatibility for TYPO3 10 LTS

v3.0.5
  * Update typoscript syntax to prevent deprecation warnings
  * Parser optimizations
  * add slash replacement for slug field
  * Add preview option for the slug field, see: `documentation <https://docs.typo3.org/p/featdd/dpn-glossary/3.0/en-us/Configuration/ExampleTypoScriptSetup/Index.html#configure-full-url-preview-for-the-term-slug-field>`_
  * Bugfix for dom picture repair function due to backtrack limit issues

v3.0.4
  * Add page title provider
  * Bugfix for html5 picture issues
  * Bugfix for cache identifier
  * Max replacement option for each term
  * TCA optimizations
  * Code refactorings

v3.0.3
  * Update composer.json

v3.0.2
  * Bugfix for upgrade wizard

v3.0.1
  * Fix upgrade wizard for TYPO3 9.5.1 due to broken slug helper method

v3.0.0
  * Compatibility to 9.5 LTS
  * Add slug field for routing (migration comes with the install wizard)
  * IMPORTANT!: Removed seperat detailpage plugin
  * Remove backpage param and always use http referer or history.back(1) for backlink

v2.7.5
  * Fix terms cache for translations
  * Use better hook for parsing terms

v2.7.4
  * Add case sensitive option for terms
  * Realurl configuration as hook
  * Link mode for terms
  * small optimizations

v2.7.3
  * Fix issue with the terms maximum replacement per page

v2.7.2
  * Add term mode feature and term link instead of glossary detailpage

v2.7.1
  * Increase missed TYPO3 verison depenedency in composer.json

v2.7.0
  * TYPO3 compatibility

v2.6.13
  * Add option to disable parsing for terms

v2.6.12
  * Fix 6.2 Compability
  * Small cleanup and refactorings
  * Fix problem with html special chars

v2.6.11
  * Use deep import to keep wraps around replaced terms

v2.6.10
  * Removed unwanted warning caused by null param

v2.6.9
  * Fixed compability issue with 6.2

v2.6.8
  * Updated fluid namespaces
  * refactoring of the update script
  * fixed multiple languages in tcaform

v2.6.7
  * Cleanup and optimizing templates

v2.6.6
  * Bugfix in regex properly escaping slashes

v2.6.5
  * Added conformer documentation for the extension
