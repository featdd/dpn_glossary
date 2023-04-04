<?php
namespace Featdd\DpnGlossary\PageTitle;

use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

/**
 * @package Featdd\DpnGlossary\PageTitle
 */
class CharacterPaginationPageTitleProvider extends AbstractPageTitleProvider
{
    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
