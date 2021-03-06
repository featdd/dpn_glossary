<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\ViewHelpers\Widget\Controller;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Utility\ObjectUtility;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @package Featdd\DpnGlossary\ViewHelpers\Widget\Controller
 */
class PaginateController extends AbstractWidgetController
{
    /**
     * @var array
     */
    protected $configuration = [
        'characters' => 'A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z',
        'insertAbove' => true,
        'insertBelow' => false,
    ];

    /**
     * Objects to sort
     *
     * @var QueryResultInterface
     */
    protected $objects;

    /**
     * Query object to sort and count terms
     *
     * @var QueryInterface
     */
    protected $query;

    /**
     * Sorting fieldname of the object model
     * what was passed by in objects
     *
     * @var string
     */
    protected $field = '';

    /**
     * Current page character
     *
     * @var string
     */
    protected $currentCharacter = '';

    /**
     * Characters used in the pagination
     *
     * @var array
     */
    protected $characters = [];

    public function initializeAction(): void
    {
        /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObjectRenderer */
        $contentObjectRenderer = ObjectUtility::makeInstance(ContentObjectRenderer::class);

        ArrayUtility::mergeRecursiveWithOverrule(
            $this->configuration,
            (array) $this->settings['pagination'],
            true
        );

        $this->field = false === empty($this->widgetConfiguration['field']) ? $this->widgetConfiguration['field'] : 'name';
        $this->objects = $this->widgetConfiguration['objects'];
        $this->query = $this->objects->getQuery();

        // Apply stdWrap
        if (\is_array($this->configuration['characters'])) {
            /** @var $typoScriptService \TYPO3\CMS\Core\TypoScript\TypoScriptService */
            $typoScriptService = ObjectUtility::makeInstance(TypoScriptService::class);

            // It's required to convert the "new" array to dot notation one before we can use `cObjGetSingle`
            $this->configuration['characters'] = $typoScriptService->convertPlainArrayToTypoScriptArray($this->configuration['characters']);
            $this->configuration['characters'] = $contentObjectRenderer->cObjGetSingle(
                $this->configuration['characters']['_typoScriptNodeValue'],
                $this->configuration['characters']
            );
        }

        $this->characters = explode(',', $this->configuration['characters']);
    }

    /**
     * Main action terms will be sorted
     * by the currentCharacter
     *
     * @param string $character
     * @return void
     * @throws \Featdd\DpnGlossary\ViewHelpers\Widget\Controller\Exception
     */
    public function indexAction(string $character = ''): void
    {
        if (true === empty($character)) {
            $this->query->setLimit(1)->setOrderings([$this->field => QueryInterface::ORDER_ASCENDING]);
            $firstObject = $this->query->execute()->toArray();
            $this->query = $this->objects->getQuery();

            if (true === empty($firstObject)) {
                $this->view->assign('noObjects', true);
            } else {
                $getter = 'get' . GeneralUtility::underscoredToUpperCamelCase($this->field);

                if (true === method_exists($firstObject[0], $getter)) {
                    $this->currentCharacter = strtoupper($firstObject[0]->{$getter}()[0]);
                } else {
                    throw new Exception('Getter for "' . $this->field . '" in "' . \get_class($firstObject[0]) . '" does not exist',
                        1433257601);
                }
            }
        } else {
            $this->currentCharacter = $character;
        }

        $this->currentCharacter = str_replace(
            ['AE', 'OE', 'UE'],
            ['Ä', 'Ö', 'Ü'],
            $this->currentCharacter
        );

        $objects = $this->getMatchings()->execute()->toArray();

        $this->view->assign('configuration', $this->configuration);
        $this->view->assign('pagination', $this->buildPagination());
        $this->view->assign('contentArguments', [$this->widgetConfiguration['as'] => $objects]);
    }

    /**
     * Pagination array gets build up
     */
    protected function buildPagination(): array
    {
        $pages = [];
        $numberOfCharacters = count($this->characters);

        /*
         * Generates the pages and also checks if
         * the page has no objects
         */
        foreach ($this->characters as $character) {
            $pages[] = [
                'linkCharacter' => str_replace(
                    ['Ä', 'Ö', 'Ü'],
                    ['AE', 'OE', 'UE'],
                    $character
                ),
                'character' => $character,
                'isCurrent' => $character === $this->currentCharacter,
                'isEmpty' => 0 === $this->getMatchings($character)->execute()->count(),
            ];
        }

        return [
            'pages' => $pages,
            'current' => $this->currentCharacter,
            'numberOfPages' => $numberOfCharacters,
            'startCharacter' => $this->characters[0],
            'endCharacter' => $this->characters[\count($this->characters) + 1],
        ];
    }

    /**
     * This function builds the matchings.
     * It enables matchings like:
     * - single character: 'B'
     * - multiple characters: 'BDEFG'
     * - range of characters: 'B-G'
     *
     * @param string|null $characters
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     */
    protected function getMatchings(string $characters = null): QueryInterface
    {
        $matching = [];

        if ($characters === null) {
            $characters = $this->currentCharacter;
        }

        $characterLength = mb_strlen($characters);

        if ($characterLength === 1) {
            // single character B
            try {
                $matching = $this->query->like($this->field, $characters . '%');
            } catch (InvalidQueryException $exception) {
                // nothing
            }
        } else {
            if ($characterLength === 3 && $characters[1] === '-') {
                // range B-G
                // Build the characters like multiple characters B-G => BCDEFG

                // Fix orderings
                $firstCharacter = mb_ord($characters[0]);
                $lastCharacter = mb_ord($characters[2]);

                if ($firstCharacter - $lastCharacter > 0) {
                    $tmp = $firstCharacter;
                    $firstCharacter = $lastCharacter;
                    $lastCharacter = $tmp;
                }

                // Build the new String
                $characters = '';

                for ($char = $firstCharacter; $char <= $lastCharacter; ++$char) {
                    $characters .= mb_chr($char);
                }
            }

            // multiple characters BDEFG
            $charactersArray = mb_str_split($characters);

            foreach ($charactersArray as $char) {
                try {
                    $matching[] = $this->query->like($this->field, $char . '%');
                } catch (InvalidQueryException $exception) {
                    // nothing
                }
            }

            $matching = $this->query->logicalOr($matching);
        }

        return $this->query->matching($matching);
    }

    /**
     * If the pagination is used this function
     * will prepare the link arguments to get
     * back to the last pagination page
     *
     * @param string $field
     * @param string $paginationCharacters
     * @return array
     */
    public static function paginationArguments(string $field, string $paginationCharacters): array
    {
        $firstCharacter = mb_strtoupper(mb_substr($field, 0, 1, 'UTF-8'), 'UTF-8');
        $characters = array_change_key_case(explode(',', $paginationCharacters), CASE_UPPER);

        /*
         * Replace umlauts if they are in characters
         * else use A,O,U
         */
        $hasUmlauts = array_intersect(['Ä', 'Ö', 'Ü'], $characters);

        $umlautReplacement = 0 < count($hasUmlauts) ?
            ['AE', 'OE', 'UE'] :
            ['A', 'O', 'U'];

        $firstCharacter = str_replace(
            ['Ä', 'Ö', 'Ü'],
            $umlautReplacement,
            $firstCharacter
        );

        $characters = str_replace(
            ['Ä', 'Ö', 'Ü'],
            $umlautReplacement,
            $characters
        );

        $character = true === \in_array($firstCharacter, $characters, true) ?
            $firstCharacter :
            false;

        return [
            '@widget_0' => [
                'character' => $character,
            ],
        ];
    }
}
