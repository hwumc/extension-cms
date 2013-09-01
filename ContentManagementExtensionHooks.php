<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class ContentManagementExtensionHooks
{
	public static function buildPageSearchPaths($args)
	{
        $paths = $args[0];
        $paths[] = dirname(__FILE__) . "/Page/";
		return $paths;
	}
    
    public static function smartySetup($args)
    {
        $smarty = $args[0];
        
        $smarty->addTemplateDir(dirname(__FILE__) . "/Templates/");
        
        return $smarty;
    }
    
    public static function getExtensionContent( $args ) {    
        $user = User::getById( Session::getLoggedInUser() );
        
        foreach(Page::getArray() as $p) {
            if($p->getSlug() == $args[1])
            {
                if(! $user->isAllowed( $p->getAccessRight() ) )
                {
                    continue;   
                }
                
                $page = new PageContentManagementContentBase();
                $page->page = $p;
                return $page;
            }
        }
        
        return null;
    }
}