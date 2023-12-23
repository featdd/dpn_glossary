.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

=========
Reference
=========

Extension Settings
------------------

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

  Key
    termSlugEvaluation

  Data Type
    string

  Description
    Set the evaluation type for the term slug.

  Default
    uniqueInSite

.. ###### END~OF~TABLE ######

TypoScript
----------

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

  Constant
    settings.detailPage

  Data Type
    integer

  Description
    Page ID of the detailpage plugin (parser will link to this)

.. container:: table-row

  Constant
    settings.parsingPids

  Data Type
    string

  Description
    Comma list of pages which should be parsed (0 for all)

  Default
    0

.. container:: table-row

  Constant
    settings.parsingExcludePidList

  Data Type
    string

  Description
    Comma list of pages which should not be parsed. Can be used to :ref:`exclude
    pages from being pages <example-exclude-content>`


.. container:: table-row

  Constant
    settings.maxReplacementPerPage

  Data Type
    integer

  Description
    Maximum replacements for each term (-1 = any)

  Default
    -1

.. container:: table-row

  Constant
    settings.maxReplacementPerPageRespectSynonyms

  Data Type
    boolean

  Description
    Respect replacement counter when parsing synonyms

  Default
    0

.. container:: table-row

  Constant
    settings.limitParsingId

  Data Type
    string

  Description
    Limits parsing for terms to the *one* node/tag having this ID (e.g. 'content' for a `<div id="content">`)

.. container:: table-row

  Constant
    settings.parsingTags

  Data Type
    string

  Description
    Comma list of Tags which content will be parsed for terms

  Default
    p

.. container:: table-row

  Constant
    settings.forbiddenParentTags

  Data Type
    string

  Description
    Comma list of Tags which are not allowed as parents for a parsing tag

  Default
    a,script

.. container:: table-row

  Constant
    settings.forbiddenParsingTagClasses

  Data Type
    string

  Description
    Comma list of classes which are not allowed for the parsing tag

.. container:: table-row

  Constant
    settings.forbiddenParentClasses

  Data Type
    string

  Default
    tx_dpn_glossary_exclude

  Description
    Comma list of classes which are not allowed on any parent of the parsing tag.
    can be used to :ref:` exclude content from being parsed
    <example-exclude-content>`

.. container:: table-row

  Constant
    settings.listmode

  Data Type
    options

  Description
    Listmode of the listpage (normal, character, pagination)

  Default
    normal

.. container:: table-row

  Constant
    settings.previewmode

  Data Type
    options

  Description
    Previewmode for the preview plugin (newest or random)

  Default
    newest

.. container:: table-row

  Constant
    settings.previewlimit

  Data Type
    integer

  Description
    Limit for preview list

  Default
    5

.. container:: table-row

  Constant
    settings.disableParser

  Data Type
    boolean

  Description
    Disable the parser

  Default
    0

.. container:: table-row

  Constant
    settings.parseSynonyms

  Data Type
    boolean

  Description
    Enable the parsing of terms synonyms

  Default
    1

.. container:: table-row

  Constant
    settings.priorisedSynonymParsing

  Data Type
    boolean

  Description
    Parse for synonyms before the actual term

  Default
    1

.. container:: table-row

  Constant
    settings.parsingSpecialWrapCharacters

  Data Type
    string

  Description
    Comma list of special characters allowed to wrap the term

.. container:: table-row

  Constant
    settings.parserRepositoryClass

  Data Type
    string

  Description
    | The repository class the parser service should use, for example the normal TermRepository instead of the ParserTermRepository.
    | This can be useful for advanced scenarios like using the first description meaning as a tooltip text.
    | Example:
    ..  code-block:: typoscript

        plugin.tx_dpnglossary.settings {
          termWraps {
            default.typolink.ATagParams.dataWrap = title="{field:descriptions|0|meaning}" class="dpnglossary link"
          }
        }


.. container:: table-row

  Constant
    settings.overrideFluidStyledContentLayout

  Data Type
    boolean

  Description
    If set the default layout of FluidStyledContent is overriden by this
    extension. Can be used to :ref:`exclude content from being parsed
    <example-exclude-content>`

  Default
    0


.. container:: table-row

  Constant
    settings.excludeTermLinksTargetPages

  Data Type
    boolean

  Description
    Don't parse terms when current page is the term links target

  Default
    0

.. ###### END~OF~TABLE ######
