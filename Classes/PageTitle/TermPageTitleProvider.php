<?php
namespace Featdd\DpnGlossary\PageTitle;

use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

class TermPageTitleProvider extends AbstractPageTitleProvider
{
    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
}
