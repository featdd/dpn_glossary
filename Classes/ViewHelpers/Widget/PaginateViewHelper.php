<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\ViewHelpers\Widget;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\ViewHelpers\Widget\Controller\PaginateController;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper;

/**
 * @package Featdd\DpnGlossary\ViewHelpers\Widget
 */
class PaginateViewHelper extends AbstractWidgetViewHelper
{

    /**
     * @var \Featdd\DpnGlossary\ViewHelpers\Widget\Controller\PaginateController
     */
    protected $controller;

    /**
     * @param \Featdd\DpnGlossary\ViewHelpers\Widget\Controller\PaginateController $paginateController
     */
    public function injectPaginateController(PaginateController $paginateController): void
    {
        $this->controller = $paginateController;
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('objects', 'array', 'Objects to paginate', true);
        $this->registerArgument('as', 'string', 'Objects passed as fluid variable with this name', true);
        $this->registerArgument('field', 'string', 'Field name of the property in the domain model', false, 'name');
    }

    /**
     * @return \TYPO3\CMS\Extbase\Mvc\ResponseInterface
     */
    public function render(): \TYPO3\CMS\Extbase\Mvc\ResponseInterface
    {
        return $this->initiateSubRequest();
    }
}
