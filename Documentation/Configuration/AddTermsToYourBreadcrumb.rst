============================
Add terms to your breadcrumb
============================

You can add terms to your breadcrumb using the data processor in your TypoScript configuration.

**Example:**

::

  dataProcessing {
    10 = TYPO3\CMS\Frontend\DataProcessing\MenuProcessor
    10 {
      special = rootline
      special.range = 0|-1
      includeNotInMenu = 1
      as = menuBreadcrumb
    }

    20 = Featdd\DpnGlossary\DataProcessing\AddTermToMenuProcessor
    20.menus = menuBreadcrumb
  }
