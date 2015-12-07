<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class CMSMainPageContentProvider implements MainPageContentProvider
{
	public function getContent($smarty) {
        $cmsPage = Page::getBySlug("main");

        if($cmsPage != false) {
            $imageGroup = ImageGroup::getById($cmsPage->getImageGroup());
            $files = array();
            if($imageGroup !== false) {
                $files = $imageGroup->getFiles();
            }

            $smarty->assign( "cmsImageGroupFiles", $files );
            $smarty->assign( "cmsPageContent", Revision::getById( $cmsPage->getRevision() )->getText() );
            $smarty->assign( "cmsPageHeader", $cmsPage->getTitle() );
        } else {
            $content = "<div class=\"alert alert-error\"><strong>Error</strong> Cannot find content page with slug 'main'. Is this page defined?</div>";
            $header = "Home";
            $smarty->assign( "cmsImageGroupFiles", array() );
            $smarty->assign( "cmsPageContent", $content);
            $smarty->assign( "cmsPageHeader", $header);
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
