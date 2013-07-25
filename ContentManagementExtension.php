<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class ContentManagementExtension extends Extension
{
	public function getExtensionInformation()
	{
		return array(
			"name" => "Content Management Framework",
			"gitviewer" => "https://gerrit.stwalkerster.co.uk/gitweb?p=siteframework/extensions/cms.git;a=tree;h=",
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
		);
		
		return array_key_exists($class, $files) ? $files[$class] : null;
	}
	
	protected function registerHooks()
	{
		Hooks::register( "BuildPageSearchPaths", array("ContentManagementExtensionHooks","buildPageSearchPaths"));
        Hooks::register( "PostSetupSmarty", array("ContentManagementExtensionHooks","smartySetup"));
	}
	

}