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
        $user = User::getLoggedIn();
                
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
    
    public static function setupMenu( $args ) {
        $menu = $args[0];
        
        foreach( MenuGroup::getArray() as $group ) {
			if( ! isset( $menu[ strtolower($group->getSlug()) ] ) ) {
                $menu[ strtolower($group->getSlug()) ] = array(
                    "items" => array(),
                    "title" => strtolower($group->getSlug()),
                    "displayname" => $group->getDisplayName()
                );
			}
		}
        
        foreach (Page::getArray() as $page)
        {
            if(! User::getLoggedIn()->isAllowed($page->getAccessRight())){
                continue;   
            }
            
            if( $page->getSlug() == "main" ) {
                continue;   
            }
            
            $menugroup = $page->getMenuGroupObject();
            $slug = "main";
            if($menugroup != null)
            {
                $slug = $menugroup->getSlug();
                
                $menu[ strtolower($slug) ][ "items" ][ $page->getSlug() ] = array(
                    "displayname" => $page->getTitle(),
                    "link" => "/" . $page->getSlug(),
                    "title" => $page->getSlug()
                );
            }
        }
        
        return $menu;
    }
}