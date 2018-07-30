<?php
namespace Featdd\DpnGlossary;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Service\UpdateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * @package DpnGlossary
 */
class ext_update
{
    /**
     * @var \Featdd\DpnGlossary\Service\UpdateService
     */
    protected $updateService;

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
