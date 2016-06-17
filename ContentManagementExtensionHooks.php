<?php
// check for invalid entry point
if (!defined("HMS")) die("Invalid entry point");

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
        /** @var Smarty $smarty */
        $smarty = $args[0];

        $smarty->addTemplateDir(dirname(__FILE__) . "/Templates/");

        return $smarty;
    }

    public static function getExtensionContent($args)
    {
        $user = User::getLoggedIn();

        /** @var Page $p */
        foreach (Page::getArray() as $p) {
            if ($p->getSlug() == $args[1]) {
                if (!$user->isAllowed($p->getAccessRight())) {
                    if($user->isAnonymous()) {
                        $page = new PageLogin();
                        return $page;
                    } else{
                        continue;
                    }
                }

                $page = new PageContentManagementContentBase();
                $page->page = $p;
                return $page;
            }
        }

        return null;
    }

    public static function setupMenu($args)
    {
        $menu = $args[0];

        /** @var MenuGroup $group */
        foreach (MenuGroup::getArray() as $group) {
            if (!isset($menu[strtolower($group->getSlug())])) {
                $menu[strtolower($group->getSlug())] = array(
                    "items" => array(),
                    "title" => strtolower($group->getSlug()),
                    "displayname" => $group->getDisplayName(),
                    "issecondary" => $group->getIsSecondary(),
                    "priorty" => $group->getPriority()
                );
            }
        }

        /** @var Page $page */
        foreach (Page::getArray() as $page) {
            if (!User::getLoggedIn()->isAllowed($page->getAccessRight())) {
                continue;
            }

            if ($page->getSlug() == "main") {
                continue;
            }

            if ($page->getPromoted() == 1) {
                $menu[$page->getSlug()] = array(
                    "displayname" => $page->getTitle(),
                    "link" => "/" . $page->getSlug(),
                    "title" => $page->getSlug(),
                    "issecondary" => 0,
                    "priority" => $page->getPriority()
                );
            } else {
                $menugroup = $page->getMenuGroupObject();
                $slug = "main";
                if ($menugroup != null) {
                    $slug = $menugroup->getSlug();

                    $menu[strtolower($slug)]["items"][$page->getSlug()] = array(
                        "displayname" => $page->getTitle(),
                        "link" => "/" . $page->getSlug(),
                        "title" => $page->getSlug()
                    );
                }
            }
        }

        return $menu;
    }
}