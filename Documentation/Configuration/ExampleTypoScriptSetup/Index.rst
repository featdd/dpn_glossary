.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

Example TypoScript Setup
^^^^^^^^^^^^^^^^^^^^^^^^

The following example shows all usable settings for the extension:

::

    plugin.tx_dpnglossary {
        settings {
            pagination {
                characters = A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z
                insertAbove = 1
                insertBelow = 0
            }

            termWraps = CASE
            termWraps {
                key.field = term_type
                default = TEXT
                default {
                    field = name
                    dataWrap = |
                    typolink {
                        ATagParams.dataWrap = title="{field:tooltiptext}" class="dpnglossary link"
                        ATagParams.dataWrap {
                            override = title="{field:name}" class="dpnglossary link"
                            override.if.isFalse.data = field:tooltiptext
                        }
                        useCacheHash = 1
                    }
                }

                abbreviation {
                    dataWrap = <abbr title="{field:tooltiptext}" lang="{field:term_lang}">|</abbr>
                    dataWrap {
                        override = <abbr title="{field:name}" lang="{field:term_lang}">|</abbr>
                        override.if.isFalse.data = field:tooltiptext
                    }
                }

                acronym {
                    dataWrap = <acronym title="{field:tooltiptext}" lang="{field:term_lang}">|</acronym>
                    dataWrap {
                        override = <acronym title="{field:name}" lang="{field:term_lang}">|</acronym>
                        override.if.isFalse.data = field:tooltiptext
                    }
                }

                definition {
                    dataWrap = <dfn title="{field:tooltiptext}" lang="{field:term_lang}">|</dfn>
                    dataWrap {
                        override = <dfn title="{field:name}" lang="{field:term_lang}">|</dfn>
                        override.if.isFalse.data = field:tooltiptext
                    }
                }
            }
        }
    }

Configure Routing for terms and pagination
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

::

  DpnGlossary:
    type: Extbase
    limitToPages: [YOUR_PLUGINPAGE_UID]
    extension: DpnGlossary
    plugin: glossary
    routes:
    - { routePath: '/{character}', _controller: 'Term::list', _arguments: {'character': 'currentCharacter'} }
    - { routePath: '/{localized_term}/{term_name}', _controller: 'Term::show', _arguments: {'term_name': 'term'} }
    defaultController: 'Term::list'
    defaults:
      character: ''
    aspects:
      term_name:
        type: PersistedAliasMapper
        tableName: 'tx_dpnglossary_domain_model_term'
        routeFieldName: 'url_segment'
      character:
        type: StaticMultiRangeMapper
        ranges:
          - start: 'A'
            end: 'Z'
      localized_term:
        type: LocaleModifier
        default: 'term'
        localeMap:
        - locale: 'de_DE.*'
          value: 'begriff'

Configure full url preview for the term slug field
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Setting the detailpage uid in the tsconfig will enable the full url preview for terms slug fields:

::

  TCEFORM {
    tx_dpnglossary_domain_model_term {
      url_segment.config.previewUrl.pageUid = [YOUR_PLUGINPAGE_UID]
    }
  }

.. _example-exclude-pages:

Exclude pages from being parsed
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Pages can be statically excluded from parsing via TypoScript::

    plugin.tx_dpnglossary {
        settings.parsingExcludePidList = 42, 185, 365
    }

Pages can also dynamically excluded from parsing by page properties
:guilabel:`Page Properties > Behaviour > Settings for DPN Glossary`:

.. figure:: /Images/ExcludePageFromParsing.png
    :alt: Exclude page from parsing

    Exclude page from parsing

By making field :sql:`tx_dpnglossary_parsing_settings` of table
:sql:`pages` available for your editors, it is also possible to let (power)
editors decide, which pages should be parsed.

.. _example-exclude-content:

Exclude content from being parsed
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The following TypoScript constant defines HTML classes whose content will be
excluded from parsing::

    plugin.tx_dpnglossary {
        settings.forbiddenParsingTagClasses = tx_dpn_glossary_exclude, my_search_results
    }

Content wrapped with one of these classes will be excluded from parsing.

Content can also dynamically excluded from parsing by content properties
:guilabel:`Content Properties > Appearance > Settings for DPN Glossary`.

This only works if the default Fluid layout has been overriden to wrap the
content with the HTML class :html:`tx_dpn_glossary_exclude`  and this class is
still found in the :typoscript:`settings.forbiddenParsingTagClasses`.

You can set the following TypoScript constant to let this extension override
the Fluid Styled Content default layout::

    plugin.tx_dpnglossary {
        settings.overrideFluidStyledContentLayout = 1
    }

If you need to override the layout yourself make sure to add the following to the
surrounding tags class:

.. code-block:: html

    <div class="... {f:if(condition: data.tx_dpnglossary_disable_parser, then: ' tx_dpn_glossary_exclude')}">

Just like with the pages this property can be used to enable editors to exclude
content elements from parsing.
