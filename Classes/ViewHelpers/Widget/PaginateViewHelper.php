<?php
namespace Featdd\DpnGlossary\ViewHelpers\Widget;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Daniel Dorndorf <dorndorf@featdd.de>
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

use Featdd\DpnGlossary\ViewHelpers\Widget\Controller\PaginateController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper;

/**
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PaginateViewHelper extends AbstractWidgetViewHelper
{

    /**
     * @var \Featdd\DpnGlossary\ViewHelpers\Widget\Controller\PaginateController
     */
    protected $controller;

    /**
     * @param \Featdd\DpnGlossary\ViewHelpers\Widget\Controller\PaginateController $paginateController
     * @return void
     */
    public function injectPaginateController(PaginateController $paginateController)
    {
        $this->controller = $paginateController;
    }

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('objects', 'array', 'Objects to paginate', true);
        $this->registerArgument('as', 'string', 'Objects passed as fluid variable with this name', true);
        $this->registerArgument('field', 'string', 'Field name of the property in the domain model', false, 'name');
    }

    /**
     * Gets the objects and "as" value also
     * the fieldname for what to sort for
     *
     * @return \TYPO3\CMS\Extbase\Mvc\ResponseInterface
     */
    public function render()
    {
        return $this->initiateSubRequest();
    }

}
