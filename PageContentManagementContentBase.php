<?php

/**
 * PageContentManagementContentBase short summary.
 *
 * PageContentManagementContentBase description.
 *
 * @version 1.0
 * @author stwalkerster
 */
class PageContentManagementContentBase extends PageBase
{
    var $page = null;
    
    protected function runPage() {
        
        $this->mSmarty->assign( "cmsPageContent", Revision::getById( $this->page->getRevision() )->getText() );
        $this->mSmarty->assign( "cmsPageHeader", $this->page->getTitle() );
        $this->mBasePage = "cms/cmspage.tpl";
    }
}
