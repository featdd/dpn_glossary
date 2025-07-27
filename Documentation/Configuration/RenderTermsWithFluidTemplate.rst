==================================
Render terms with a Fluid template
==================================

While for most cases a simple dataWrap in TypoScript is enough, it is also possible to render terms with a Fluid template.
You may only have to take care of unnecessary whitespace with `stdWrap.trim = 1` and the `<f:spaceless>` ViewHelper.

See this `GitHub Issue <https://github.com/featdd/dpn_glossary/issues/228>`_ for detailed information.

**Example:**

..  code-block:: TypoScript
    :caption: Your TypoScript template

  plugin.tx_dpnglossary.settings {
    termWraps {
        default >
        default = FLUIDTEMPLATE
        default {
            stdWrap.trim = 1

            templateRootPaths {
                10 = EXT:your_site_package/Resources/Private/Templates/
            }

            templateName = TermWraps/Default

            settings < plugin.tx_dpnglossary.settings

            dataProcessing {
                10 = TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor
                10 {
                    table = pages
                    pidInList = 0
                    uidInList = this
                    as = currentPage
                }
            }
        }
    }
  }

..  code-block:: html
    :caption: EXT:your_site_package/Resources/Templates/TermWraps/Default.html

  <html data-namespace-typo3-fluid="true"
          xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers">

    <f:spaceless>
      <f:link.action
          action="show"
          controller="Term"
          pluginName="Glossary"
          extensionName="DpnGlossary"
          arguments="{term: data.uid}"
          pageUid="{settings.detailPage}"
          class="dpn-glossary link"
      >{data.name}</f:link.action>
    </f:spaceless>

  </html>
