.. _admin-manual:

Administrator Manual
====================

Target group: **Administrators**

.. _admin-installation:

Installation
------------

#. Install the extension in the extension manager or composer (`composer req featdd/dpn-glossary`)

#. Add the static TypoScript to your Site template:

   #. Go to "Template" Module in your TYPO3 Backend
   #. Edit your template record
   #. Add the entry "dreipunktnull glossary" below the "Includes" tab in the "Include static (from extensions)" field.

#. Create a..

   * ..page and add the glossary plugin

     * Alternatively you can add a second glossary plugin on a separate page.
       Use this page UID as your detailpage and split your routing configuration for the list and detailpage.

   * ..storage and add your terms

#. Configure the TypoScript constants of the extension..

   * set the storage page uid
      * `plugin.tx_dpnglossary.persistence.storagePid = [Your storage page uid]`

   * set the detailpage uid (where you placed the glossary plugin)
      * `plugin.tx_dpnglossary.settings.detailPage = [Your detailpage uid]`
