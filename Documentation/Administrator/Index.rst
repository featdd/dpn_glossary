.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator Manual
====================

Target group: **Administrators**

.. _admin-installation:

Installation
------------

#. Install the extension in the extension manager or composer (`composer req featdd/dpn-glossary`)

#. Add the static TypoScript to your Site template

#. Create a..

   - ..page and add the glossary plugin
   - ..storage and add your terms

#. Configure the TypoScript constants of the extension..

   - set the storage page uid
      * `plugin.tx_dpnglossary.persistence.storagePid = [Your storage page uid]`

   - set the detailpage uid (where you placed the glossary plugin)
      * `plugin.tx_dpnglossary.settings.detailPage = [Your detailpage uid]`
