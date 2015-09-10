<?php
namespace Dpn\DpnGlossary\Tests\Service;

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

use Dpn\DpnGlossary\Utility\ParserUtility;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ParserUtilityTest extends UnitTestCase {

	/**
	 * @var string
	 */
	protected $testHtmlScript = '
		<script>
			var test = \'<a href="#”>Test</a>\';
		</script>

		<!-- some comment -->

		<script type="text/javascript">
			var test = \'<a href="#”>Test</a>\';
		</script>
	';

	/**
	 * @var string
	 */
	protected $testHtmlScriptWrapped = '
		<!--DPNGLOSSARY<script>
			var test = \'<a href="#”>Test</a>\';
		</script>-->

		<!-- some comment -->

		<!--DPNGLOSSARY<script type="text/javascript">
			var test = \'<a href="#”>Test</a>\';
		</script>-->
	';

	/**
	 * @test
	 */
	public function protectInlineJSFromDOMTest() {
		$this->assertEquals(
			$this->testHtmlScriptWrapped,
			ParserUtility::protectInlineJSFromDOM($this->testHtmlScript)
		);
	}

	/**
	 * @test
	 */
	public function protectInlineJSFromDOMReverseTest() {
		$this->assertEquals(
			$this->testHtmlScript,
			ParserUtility::protectInlineJSFromDOMReverse($this->testHtmlScriptWrapped)
		);
	}

	/**
	 * @test
	 */
	public function getAndSetInnerTagContentTest() {
		$html = '<p class="bodytext">Testtext bla bla <a href="#">Test</a> Test</p>';
		$htmlExpected = '<p class="bodytext">Testtext bla bla <a href="#">Test</a> Test TESTSTRING</p>';

		$testCallback = function($content) {
			return $content . ' TESTSTRING';
		};

		$this->assertEquals(
			$htmlExpected,
			ParserUtility::getAndSetInnerTagContent($html, $testCallback)
		);

		$html = '<p>Testtext <p>Test</p>Test</p>';
		$htmlExpected = '<p>Testtext <p>Test</p>Test TESTSTRING</p>';

		$this->assertEquals(
			$htmlExpected,
			ParserUtility::getAndSetInnerTagContent($html, $testCallback)
		);
	}
}
