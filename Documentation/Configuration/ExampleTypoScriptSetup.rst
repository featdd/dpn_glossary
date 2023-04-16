.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

========================
Example TypoScript Setup
========================

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
