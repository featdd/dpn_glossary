.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration Reference
=======================

Plugin Settings
---------------

plugin.tx_dpnglossary {
	settings {
		termWraps {
			default {
				dataWrap = <span class="dpnglossary" lang="{field:term_lang}" title="{field:name}">|</span>
				typolink.ATagParams.dataWrap = title="{field:tooltiptext}" class="dpnglossary-link"
			}
			acronym {
				datawrap = <acronym title="{field:tooltiptext}" lang="{field:term_lang}">|</acronym>
			}
			abbreviation {
				dataWrap = <abbr title="{field:tooltiptext}" lang="{field:term_lang}">|</abbr>
			}
			acronym {
				dataWrap = <acronym title="{field:tooltiptext}" lang="{field:term_lang}">|</acronym>
			}
			definition {
				dataWrap = <dfn title="{field:tooltiptext}" lang="{field:term_lang}">|</dfn>
			}
		}
	}
}

Constants Settings
------------------
+------------------------+----------------------------------------------------------------------------+
| persistence.storagePid | Pid of the storage for the Terms you created                               |
+------------------------+----------------------------------------------------------------------------+
| settings.detailsPid    | Detailpage of the Glossary Plugin, the parser will use it if it's set.     |
|                        | Otherwise the parser will wrap the term in an anchor.                      |
+------------------------+----------------------------------------------------------------------------+
| parsingPids            | Page Ids which should be parsed for terms (comma list, default is 0 = any) |
+------------------------+----------------------------------------------------------------------------+
| parsingExcludePidList  | Page Ids which should not be parsed for terms (comma list)                 |
+------------------------+----------------------------------------------------------------------------+
| maxReplacementPerPage  | Sets how many replacements per term should be done (standard -1 = any)     |
+------------------------+----------------------------------------------------------------------------+
| parsingTags            | HTML tags which the parser searchs for terms (default = p)                 |
+------------------------+----------------------------------------------------------------------------+