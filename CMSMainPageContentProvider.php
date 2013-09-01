<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class CMSMainPageContentProvider implements MainPageContentProvider
{
	public function getContent($smarty) {
        $cmsPage = null;
        foreach(Page::getArray() as $p) {
            if($p->getSlug() == "main")
            {
                $cmsPage = $p;
            }
        }
        
        if($cmsPage != null) {
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
        return "cms/cmspage.tpl";
    }
}
