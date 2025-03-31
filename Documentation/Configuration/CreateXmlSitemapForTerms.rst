============================
Create XML Sitemap for Terms
============================

Requirements: System extension "SEO" installed (and setup.typoscript added)

Add the following code to you setup:

.. code-block:: typoscript

  plugin.tx_seo.config {
      xmlSitemap {
          sitemaps {
              glossar {
                  provider = TYPO3\CMS\Seo\XmlSitemap\RecordsXmlSitemapDataProvider
                  config {
                      table = tx_dpnglossary_domain_model_term
                      sortField = name
                      lastModifiedField = tstamp
                      pid = 123 #uid of the sysfolder where your term are stored
                      url {
                          pageId = 456 #uid of the page where the glossary plugin is placed

                          fieldToParameterMap {
                              uid = tx_dpnglossary_glossary[term]
                          }

                          additionalGetParameters {
                              tx_dpnglossary_glossary.controller = Term
                              tx_dpnglossary_glossary.action = show
                          }

                          useCacheHash = 1
                      }
                  }
              }
          }
      }
  }

Official documentaton: `<https://docs.typo3.org/m/typo3/reference-coreapi/11.5/en-us/ApiOverview/Seo/XmlSitemap.html>`_
