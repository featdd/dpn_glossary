<?php
namespace Dpn\DpnGlossary\ViewHelpers\Widget;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Daniel Dorndorf <dorndorf@dreipunktnull.com>, dreipunktnull
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

use Dpn\DpnGlossary\ViewHelpers\Widget\Controller\PaginateController;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper;

/**
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PaginateViewHelper extends AbstractWidgetViewHelper {

	/**
	 * @var PaginateController
	 */
	protected $controller;

	/**
	 * @param PaginateController $paginateController
	 * @return void
	 */
	public function injectPaginateController(PaginateController $paginateController) {
		$this->controller = $paginateController;
	}

	/**
	 * Gets the objects and "as" value also
	 * the fieldname for what to sort for
	 *
	 * @param QueryResultInterface $objects
	 * @param string $as
	 * @param string $field
	 *
	 * @return ResponseInterface
	 */
	public function render(QueryResultInterface $objects, $as, $field = 'name') {
		return $this->initiateSubRequest();
	}

}
