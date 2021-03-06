<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class ContentManagementExtension extends Extension
{
	public function getExtensionInformation()
	{
		return array(
			"name" => "Content Management Framework",
			"gitviewer" => "https://phabricator.stwalkerster.co.uk/rSFC",
			"description" => "Content Management utilities for siteframework",
			"filepath" => dirname(__FILE__),
		);
	}
	
	protected function autoload( $class )
	{
		$files = array(
			"ContentManagementExtensionHooks" => "ContentManagementExtensionHooks.php",
			"Page" => "DataObjects/Page.php",
			"Revision" => "DataObjects/Revision.php",
            "PageContentManagementContentBase" => "PageContentManagementContentBase.php",
            "CMSMainPageContentProvider" => "CMSMainPageContentProvider.php",
		);
		
		return array_key_exists($class, $files) ? $files[$class] : null;
	}
	
	protected function registerHooks()
	{
		Hooks::register( "BuildPageSearchPaths", array("ContentManagementExtensionHooks","buildPageSearchPaths"));
        Hooks::register( "PostSetupSmarty", array("ContentManagementExtensionHooks","smartySetup"));
        Hooks::register( "GetExtensionContent", array("ContentManagementExtensionHooks","getExtensionContent"));
        Hooks::register( "PreCreateMenu", array("ContentManagementExtensionHooks","setupMenu"));
	}
	

}