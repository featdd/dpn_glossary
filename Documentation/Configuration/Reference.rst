.. _configuration-reference:

=========
Reference
=========

Extension settings
------------------

These settings are configured globally for the extension.

.. list-table::
   :header-rows: 1
   :widths: 30 20 20 50

   * - Key
     - Data type
     - Default
     - Description
   * - ``termSlugEvaluation``
     - string
     - :yaml:`unique`
     - Evaluation type for the term slug field.

Site settings
-------------

When the site set :yaml:`featdd/dpn-glossary` is included, configure these
values as TYPO3 site settings. In YAML files the keys are stored flat:

.. code-block:: yaml
   :caption: config/sites/<your-site>/settings.yaml

   dpn-glossary.storagePidList: '123'
   dpn-glossary.glossaryPage: 456
   dpn-glossary.parsingPids: '0'

.. list-table::
   :header-rows: 1
   :widths: 34 14 24 58

   * - Key
     - Data type
     - Default
     - Description
   * - ``dpn-glossary.view.layoutRootPath``
     - string
     - :file:`EXT:dpn_glossary/Resources/Private/Layouts/`
     - Fluid layout root path.
   * - ``dpn-glossary.view.templateRootPath``
     - string
     - :file:`EXT:dpn_glossary/Resources/Private/Templates/`
     - Fluid template root path.
   * - ``dpn-glossary.view.partialRootPath``
     - string
     - :file:`EXT:dpn_glossary/Resources/Private/Partials/`
     - Fluid partial root path.
   * - ``dpn-glossary.storagePidList``
     - string
     - empty
     - Comma-separated list of storage page IDs that contain glossary terms.
   * - ``dpn-glossary.glossaryPage``
     - page
     - :yaml:`0`
     - Page ID of the glossary plugin. The parser links terms to this page.
   * - ``dpn-glossary.parsingPids``
     - string
     - empty
     - Comma-separated list of pages that should be parsed. Use :yaml:`0` to parse all pages.
   * - ``dpn-glossary.parsingExcludePidList``
     - string
     - empty
     - Comma-separated list of pages that should not be parsed.
   * - ``dpn-glossary.disableParser``
     - bool
     - :yaml:`false`
     - Disable automatic parsing.
   * - ``dpn-glossary.parsingSpecialWrapCharacters``
     - string
     - empty
     - Comma-separated list of additional special characters that may wrap a term.
   * - ``dpn-glossary.parserRepositoryClass``
     - string
     - :php:`Featdd\DpnGlossary\Domain\Repository\ParserTermRepository`
     - Repository class used by the parser. Use :php:`Featdd\DpnGlossary\Domain\Repository\TermRepository` if the term wrapping TypoScript needs all term fields.
   * - ``dpn-glossary.maxReplacementPerPage``
     - int
     - :yaml:`-1`
     - Maximum replacements for each term on a page. :yaml:`-1` means unlimited.
   * - ``dpn-glossary.maxReplacementPerPageRespectSynonyms``
     - bool
     - :yaml:`false`
     - Count synonym replacements against the term replacement limit.
   * - ``dpn-glossary.parsingTags``
     - string
     - :yaml:`p`
     - Comma-separated list of HTML tags whose content should be parsed.
   * - ``dpn-glossary.forbiddenParentTags``
     - string
     - :yaml:`a,script`
     - Comma-separated list of parent tags inside which parsing is not allowed.
   * - ``dpn-glossary.forbiddenParsingTagClasses``
     - string
     - empty
     - Comma-separated list of classes that exclude the parsing tag itself.
   * - ``dpn-glossary.forbiddenParentClasses``
     - string
     - :yaml:`tx_dpn_glossary_exclude`
     - Comma-separated list of classes that exclude a parsing tag through any parent element.
   * - ``dpn-glossary.parseSynonyms``
     - bool
     - :yaml:`true`
     - Enable parsing of term synonyms.
   * - ``dpn-glossary.priorisedSynonymParsing``
     - bool
     - :yaml:`true`
     - Parse synonyms before the original term.
   * - ``dpn-glossary.limitParsingId``
     - string
     - empty
     - Limit parsing to one node with this HTML ID.
   * - ``dpn-glossary.useTermForSynonymParsingDataWrap``
     - bool
     - :yaml:`false`
     - Use the original term as content object data when rendering synonym links.
   * - ``dpn-glossary.excludeTermLinksTargetPages``
     - bool
     - :yaml:`false`
     - Do not parse a term when the current page is the target page of that term.
   * - ``dpn-glossary.listmode``
     - string
     - :yaml:`normal`
     - List mode of the glossary plugin. Supported values: :yaml:`normal`, :yaml:`character`, :yaml:`pagination`.
   * - ``dpn-glossary.previewmode``
     - string
     - :yaml:`newest`
     - Preview plugin mode. Supported values: :yaml:`newest`, :yaml:`random`.
   * - ``dpn-glossary.previewlimit``
     - int
     - :yaml:`5`
     - Number of terms rendered by preview plugins.
   * - ``dpn-glossary.addStylesheet``
     - bool
     - :yaml:`true`
     - Include the extension CSS file.
   * - ``dpn-glossary.overrideFluidStyledContentLayout``
     - bool
     - :yaml:`false`
     - Override the Fluid Styled Content layout so editors can exclude content elements from parsing.

Legacy TypoScript constants
---------------------------

If you use the legacy static TypoScript include instead of the site set, use the
old TypoScript constants:

.. list-table::
   :header-rows: 1
   :widths: 45 45

   * - Site setting
     - Legacy TypoScript constant
   * - ``dpn-glossary.storagePidList``
     - :typoscript:`plugin.tx_dpnglossary.persistence.storagePid`
   * - ``dpn-glossary.glossaryPage``
     - :typoscript:`plugin.tx_dpnglossary.settings.detailPage`
   * - ``dpn-glossary.view.layoutRootPath``
     - :typoscript:`plugin.tx_dpnglossary.view.layoutRootPath`
   * - ``dpn-glossary.view.templateRootPath``
     - :typoscript:`plugin.tx_dpnglossary.view.templateRootPath`
   * - ``dpn-glossary.view.partialRootPath``
     - :typoscript:`plugin.tx_dpnglossary.view.partialRootPath`
   * - All other ``dpn-glossary.*`` settings
     - :typoscript:`plugin.tx_dpnglossary.settings.*`
