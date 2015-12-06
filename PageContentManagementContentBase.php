<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageContentManagementContentBase extends PageBase
{
    /**
     * @var Page
     */
    var $page = null;
    
    protected function runPage() {
        
        $this->mSmarty->assign( "cmsPageContent", Revision::getById( $this->page->getRevision() )->getText() );
        $this->mSmarty->assign( "cmsPageHeader", $this->page->getTitle() );
        $this->mBasePage = $this->page->getTemplateForDisplay();
    }
   
}
