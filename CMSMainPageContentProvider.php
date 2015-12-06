<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class CMSMainPageContentProvider implements MainPageContentProvider
{
	public function getContent($smarty) {
        $cmsPage = Page::getBySlug("main");

        if($cmsPage != false) {
            $smarty->assign( "cmsPageContent", Revision::getById( $cmsPage->getRevision() )->getText() );
            $smarty->assign( "cmsPageHeader", $cmsPage->getTitle() );
            return "CMS Content.";
        } else {
            $content = "<div class=\"alert alert-error\"><strong>Error</strong> Cannot find content page with slug 'main'. Is this page defined?</div>";
            $header = "Home";
            $smarty->assign( "cmsPageContent", $content);
            $smarty->assign( "cmsPageHeader", $header);
            
            return "CMS Content.";
        }
    }
    
    public function getPageTemplate() {
        $cmsPage = Page::getBySlug("main");
        if($cmsPage != false) {
            return $cmsPage->getTemplateForDisplay();
        }

        return "cms/cmspage.tpl";
    }
}
