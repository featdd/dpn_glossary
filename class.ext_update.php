<?php
namespace Featdd\DpnGlossary;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Daniel Dorndorf <dorndorf@featdd.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Featdd\DpnGlossary\Service\UpdateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ext_update
{
    /**
     * @var \Featdd\DpnGlossary\Service\UpdateService
     */
    protected $updateService;

    /**
     * @return \Featdd\DpnGlossary\ext_update
     */
    public function __construct()
    {
        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->updateService = $objectManager->get(UpdateService::class);
    }

    /**
     * @return string
     */
    public function main()
    {
        $updateNotices = '';

        if ($this->updateService->isUpdateNecessary()) {
            $this->updateService->makeUpdates();

            $updateNotices = $this->updateService->getUpdateNotices();
        }

        return $updateNotices;
    }

    /**
     * @return bool
     */
    public function access()
    {
        return $this->updateService->isUpdateNecessary();
    }
}
