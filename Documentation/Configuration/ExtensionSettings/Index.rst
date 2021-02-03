.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

Extension Settings
^^^^^^^^^^^^^^^^^^

All constants have to be added to

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

Reference
^^^^^^^^^

All constants have to be added to
plugin.tx_dpnglossary.settings:

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
    Comma list of pages which should not be parsed


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

  Description
    Comma list of classes which are not allowed on any parent of the parsing tag

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

.. ###### END~OF~TABLE ######
