=============
Configuration
=============

When the site set :yaml:`featdd/dpn-glossary` is included, the extension is
configured through TYPO3 site settings. These settings are passed to the
extension TypoScript automatically.

Projects that still use the legacy static TypoScript include can configure the
same behavior through TypoScript constants.

The whole linking of terms can be configured with TypoScript. The parsing
itself can also be defined as precisely as you need.

There are also example styles for the views and a tiny CSS3 Tooltip

+ CSS: EXT:dpn_glossary/Resources/Public/Css/styles.css

Special: Umlauts
----------------

If you want to add umlauts to the pagination you have to check the terms table collation.

+ Normal utf8 will not differ between Ä and A, you have to use "utf8_german2_ci" which would make a difference
+ You could change the 'name' column collation and add Ä,Ö,Ü to the comma list over typoscript
+ See `MySQL reference <https://dev.mysql.com/doc/refman/5.7/en/charset-collation-effect.html>`_ for more info

**Table of Contents**

.. toctree::
    :maxdepth: 2
    :glob:

    Reference
    ExampleTypoScriptSetup
    AddTermsToYourBreadcrumb
    RenderTermsWithFluidTemplate
    ConfigureRoutingForTermsAndPagination
    CreateXmlSitemapForTerms
    ExcludeContentFromParser
