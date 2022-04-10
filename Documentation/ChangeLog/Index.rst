.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

.. _changelog:

ChangeLog
---------

v4.1.0
  - Add SEO title & description for terms
  - Optimize slug preview (no extra configuration needed anymore)

v4.0.0
  - Add support for TYPO3 v11 (dropped for TYPO3 v9)
  - Use of symfony dependency injection
  - Added custom character pagination API to replace widget based pagination

v3.2.4
  - Prevent term parser from self referencing on detail pages

v3.2.3
  - Use correct ordering for newest terms query
  - Use proper title/alt attribute property in file reference

v3.2.2
  - Use multibyte functions to properly process non ascii characters for pagination

v3.2.1
  - Fix TCA sorting for synonyms & descriptions in term inline fields
  - Add option to keep the origin term for data wrap when parsing synonyms

v3.2.0
  - Change parsing order to parse the whole content for each term
  - Add special wrap character option for term regular expression

v3.1.6
  - Add missing renderType for preview FlexForm

v3.1.5
  - Fix broken TypoScript due to an auto indent issue

v3.1.4
  - Add hook to also run parser when config.no_cache is true
  - Add "forbidden parent classes" new parsing exclude feature

v3.1.3
  - Add option to priories synonym parsing before the main term
  - Fixed parsing priority issue with synonyms

v3.1.2
  - Use "unique" as default evaluation for slug to prevent initial errors

v3.1.1
  - Add better evaluation for the term slug

v3.1.0
  - Add compatibility for TYPO3 10 LTS

v3.0.5
  - Update typoscript syntax to prevent deprecation warnings
  - Parser optimizations
  - add slash replacement for slug field
  - Add preview option for the slug field, see: `documentation <https://docs.typo3.org/p/featdd/dpn-glossary/3.0/en-us/Configuration/ExampleTypoScriptSetup/Index.html#configure-full-url-preview-for-the-term-slug-field>`_
  - Bugfix for dom picture repair function due to backtrack limit issues

v3.0.4
  - Add page title provider
  - Bugfix for html5 picture issues
  - Bugfix for cache identifier
  - Max replacement option for each term
  - TCA optimizations
  - Code refactorings

v3.0.3
  - Update composer.json

v3.0.2
  - Bugfix for upgrade wizard

v3.0.1
  - Fix upgrade wizard for TYPO3 9.5.1 due to broken slug helper method

v3.0.0
  - Compatibility to 9.5 LTS
  - Add slug field for routing (migration comes with the install wizard)
  - IMPORTANT!: Removed seperat detailpage plugin
  - Remove backpage param and always use http referer or history.back(1) for backlink

v2.7.5
  - Fix terms cache for translations
  - Use better hook for parsing terms

v2.7.4
  - Add case sensitive option for terms
  - Realurl configuration as hook
  - Link mode for terms
  - small optimizations

v2.7.3
  - Fix issue with the terms maximum replacement per page

v2.7.2
  - Add term mode feature and term link instead of glossary detailpage

v2.7.1
  - Increase missed TYPO3 verison depenedency in composer.json

v2.7.0
  - TYPO3 compatibility

v2.6.13
  - Add option to disable parsing for terms

v2.6.12
  - Fix 6.2 Compability
  - Small cleanup and refactorings
  - Fix problem with html special chars

v2.6.11
  - Use deep import to keep wraps around replaced terms

v2.6.10
  - Removed unwanted warning caused by null param

v2.6.9
  - Fixed compability issue with 6.2

v2.6.8
  - Updated fluid namespaces
  - refactoring of the update script
  - fixed multiple languages in tcaform

v2.6.7
  - Cleanup and optimizing templates

v2.6.6
  - Bugfix in regex properly escaping slashes

v2.6.5
  - Added conformer documentation for the extension
