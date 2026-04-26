================================
Exclude contents from the parser
================================

.. _example-exclude-pages:

Exclude whole pages from being parsed
-------------------------------------

Pages can be statically excluded from parsing via site settings:

.. code-block:: yaml
   :caption: config/sites/<your-site>/settings.yaml

   dpn-glossary.parsingExcludePidList: '42,185,365'

Pages can also be dynamically excluded from parsing by page properties
:guilabel:`Page Properties > Behaviour > Settings for dreipunktnull Glossary`:

.. figure:: /Images/ExcludePageFromParsing.png
    :alt: Exclude page from parsing

    Exclude page from parsing

By making field :sql:`tx_dpnglossary_parsing_settings` of table
:sql:`pages` available for your editors, it is also possible to let (power)
editors decide, which pages should be parsed.

.. _example-exclude-content:

Exclude content elements from being parsed
------------------------------------------

The following site setting defines HTML classes whose content will be excluded
from parsing:

.. code-block:: yaml
   :caption: config/sites/<your-site>/settings.yaml

   dpn-glossary.forbiddenParentClasses: 'tx_dpn_glossary_exclude,my_search_results'

Content wrapped with one of these classes will be excluded from parsing.

Content can also be dynamically excluded from parsing by content properties
:guilabel:`Content Properties > Appearance > Settings for dreipunktnull Glossar`.

This only works if the default Fluid layout has been overridden to wrap the
content with the HTML class :html:`tx_dpn_glossary_exclude`  and this class is
still found in the ``dpn-glossary.forbiddenParentClasses`` site setting.

You can set the following site setting to let this extension override the Fluid
Styled Content default layout:

.. code-block:: yaml
   :caption: config/sites/<your-site>/settings.yaml

   dpn-glossary.overrideFluidStyledContentLayout: true

If you need to override the layout yourself make sure to add the following to the
surrounding tags class:

.. code-block:: html

    <div class="... {f:if(condition: data.tx_dpnglossary_disable_parser, then: ' tx_dpn_glossary_exclude')}">

Just like with the pages this property can be used to enable editors to exclude
content elements from parsing.
