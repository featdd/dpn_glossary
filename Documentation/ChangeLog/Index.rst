.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

.. _changelog:

ChangeLog
---------

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
