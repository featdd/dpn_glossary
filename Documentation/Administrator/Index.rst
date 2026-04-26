.. _admin-manual:

Administrator Manual
====================

Target group: **Administrators**

.. _admin-installation:

Installation
------------

#. Install the extension with Composer:

   .. code-block:: bash

      composer req featdd/dpn-glossary

#. Add the site set :yaml:`featdd/dpn-glossary` to your site configuration.

   This loads the extension TypoScript, Page TSconfig, site setting
   definitions and the default route enhancer configuration.

   You can add the set in the backend via :guilabel:`Sites > Setup`
   or in your site configuration:

   .. code-block:: yaml
      :caption: config/sites/<your-site>/config.yaml

      dependencies:
        - featdd/dpn-glossary

   If you use a site package, you can also add the dependency to your own site
   set instead of adding it directly to every site configuration.

#. Create the required pages:

   * Create a page and add the glossary plugin.

     * Alternatively you can add a second glossary plugin on a separate page.
       Use this page UID as your detail page and split your routing configuration for the list and detail page.

   * Create a storage folder and add your terms.

#. Configure the site settings of the extension.

   The required settings are the storage page for terms and the page that
   contains the glossary plugin:

   .. code-block:: yaml
      :caption: config/sites/<your-site>/settings.yaml

      dpn-glossary.storagePidList: '123'
      dpn-glossary.glossaryPage: 456

   If you want the automatic parser to run on all pages, also configure:

   .. code-block:: yaml

      dpn-glossary.parsingPids: '0'

   You can edit these values in the backend site settings editor as well. See
   :ref:`configuration-reference` for all available settings.

Legacy static TypoScript include
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

For projects that do not use site sets, the legacy static TypoScript include is
still available:

#. Go to the :guilabel:`Template` module in the TYPO3 backend.
#. Edit your template record.
#. Add :guilabel:`dreipunktnull Glossar` below :guilabel:`Includes > Include static (from extensions)`.

Then configure the legacy TypoScript constants:

* Set the storage page uid:

  .. code-block:: typoscript

     plugin.tx_dpnglossary.persistence.storagePid = [your storage page uid]

* Set the detail page uid where you placed the glossary plugin:

  .. code-block:: typoscript

     plugin.tx_dpnglossary.settings.detailPage = [your detail page uid]
