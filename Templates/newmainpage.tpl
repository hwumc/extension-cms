{extends file="base.tpl"}
{block name="styleoverride"}
<link href="{$cWebPath}/style/mainpage.css" rel="stylesheet">

<style type="text/css">
    body {
        padding-bottom: 40px;
    }

    .sidebar-nav {
        padding: 9px 0;
    }

    @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
            float: none;
            padding-left: 5px;
            padding-right: 5px;
        }
    }

	span.headerCopyright {
		color: white;
		position: relative;
		text-shadow: 0 0 1px #000;
		font-size:smaller;
		text-align: right;
		display: block;
	}
</style>

{/block}

{block name="navbar"}
<div class="navbar-wrapper">
    <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
    <div class="container">

        <div class="navbar navbar-inverse">
            <div class="navbar-inner">
                <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                {include file="navigation.tpl"}
            </div><!-- /.navbar-inner -->
        </div><!-- /.navbar -->

    </div> <!-- /.container -->
</div><!-- /.navbar-wrapper -->
{/block}

{block name="navbarsecondaryitems"}{/block}

{block name="personalnav"}
{foreach from=$navbarsecondaryitems item="menu" key="menuheader"}
	<li class="dropdown-submenu">
		<a href="#" tabindex="-1"><i class="icon-folder-open"></i>&nbsp;{message name="navbar-{$menuheader}"}</a>
		<ul class="dropdown-menu">
			{foreach from=$menu item="section" key="sectionheader" name="dropdownsection"}
			<li class="nav-header">{message name="navbarmenu-{$sectionheader}"}</li>
			{foreach from=$section item="menuitem"}
			<li><a href="{$menuitem.link}"><i class="{$menuitem.icon}"></i>&nbsp;{message name="{$menuitem.displayname}"}</a></li>
			{/foreach}
			{if !$smarty.foreach.dropdownsection.last}
			<li class="divider"></li>
			{/if}
			{/foreach}
		</ul>
	</li>
{/foreach}
{if count($navbarsecondaryitems) > 0}
<li class="divider"></li>
{/if}
{foreach from="$mainmenu" item="menuitem" name="mainmenuloop"}
	{if $menuitem.issecondary == 1}
		{assign menusepneeded true}
		<li class="dropdown-submenu">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-folder-open"></i>&nbsp;{$menuitem.displayname|escape}{if isset($menuitem.data)}{$menuitem.data|escape}{/if}</a>
			{if isset($menuitem.items)}{assign "submenu" "{$menuitem.items}"}
			<ul class="dropdown-menu">
				{foreach from="$submenu" item="subitem" }
				<li>
					<a href="{$cScriptPath}{$subitem.link}" {if isset($subitem.current)}class="active" {/if}>
					{if isset($subitem.displayname)}
					{$subitem.displayname|escape}
					{else}
					{message name={$subitem.title}}
					{/if}
					{if isset($subitem.data)}{$subitem.data|escape}{/if}
					</a>
				</li>
				{/foreach}
			</ul>
			{/if}
		</li>
	{/if}
{/foreach}
{if $menusepneeded}
<li class="divider"></li>
{/if}
{/block}

{block name="primarynavbar" prepend}
<!--{print_r($mainmenu)}-->
<ul class="nav">
	{foreach from="$mainmenu" item="menuitem" name="mainmenuloop"}
	{if $menuitem.issecondary != 1}
    <li class="dropdown">
        <a href="{if isset($menuitem.link)}{$cScriptPath}{$menuitem.link}{else}#{/if}" {if !isset($menuitem.link)}class="dropdown-toggle" data-toggle="dropdown"{/if}>{$menuitem.displayname|escape}{if isset($menuitem.data)}{$menuitem.data|escape}{/if}{if !isset($menuitem.link)}&nbsp;<b class="caret"></b>{/if}</a>
		{if isset($menuitem.items)}{assign "submenu" "{$menuitem.items}"}
		<ul class="dropdown-menu">
			{foreach from="$submenu" item="subitem" }
			<li>
				<a href="{$cScriptPath}{$subitem.link}" {if isset($subitem.current)}class="active" {/if}>
				{if isset($subitem.displayname)}
				{$subitem.displayname|escape}
				{else}
				{message name={$subitem.title}}
				{/if}
				{if isset($subitem.data)}{$subitem.data|escape}{/if}
				</a>
			</li>
			{/foreach}
		</ul>
		{/if}
    </li>
	{/if}	
	{/foreach}
</ul>

{/block}

{block name="precontent"}
<!-- Carousel
    ================================================== -->
<div id="imageCarousel" class="carousel slide">
    <div class="carousel-inner">
        {foreach from=$cmsImageGroupFiles item="file" name="carouselimages"}
        <div class="item{if $smarty.foreach.carouselimages.first} active{/if}">
            <img src="{$file->getDownloadPath()}" alt="{$file->getName()}">
			<span class="headerCopyright" >{$file->getCopyright()|escape}</span>
        </div>
        {/foreach}
    </div>
    {if count($cmsImageGroupFiles) > 1}
        <a class="left carousel-control" href="#imageCarousel" data-slide="prev">&lsaquo;</a>
        <a class="right carousel-control" href="#imageCarousel" data-slide="next">&rsaquo;</a>
    {/if}
</div><!-- /.carousel -->
{/block}

{block name="maincontent"}
{$cmsPageContent}
{/block}

{block name="scriptfooter"}
<script>
    !function ($) {
        $(function() {
            $('#imageCarousel').carousel();
        });
    }(window.jQuery)
</script>
{/block}