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

{block name="primarynavbar" prepend}
<ul class="nav">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{message name="navbar-primarynavigation"}&nbsp;<b class="caret"></b></a>
        <ul class="dropdown-menu">
            {foreach from="$mainmenu" item="menuitem" name="mainmenuloop"}
            {if isset($menuitem.items)}{assign "submenu" "{$menuitem.items}"}
            <li class="nav-header">{$menuitem.displayname|escape}{if isset($menuitem.data)}{$menuitem.data|escape}{/if}</li>
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
            {if ! $smarty.foreach.mainmenuloop.last}
            <li class="divider"></li>
            {/if}
            {else}
            <li>
                <a href="{$cScriptPath}{$menuitem.link}" {if isset($menuitem.current)}class="active" {/if}>{message name={$menuitem.title}}{if isset($menuitem.data)}{$menuitem.data|escape}{/if}</a>
                {/if}
            </li>
            {/foreach}
        </ul>
    </li>
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
        </div>
        {/foreach}
    </div>
    <a class="left carousel-control" href="#imageCarousel" data-slide="prev">&lsaquo;</a>
    <a class="right carousel-control" href="#imageCarousel" data-slide="next">&rsaquo;</a>
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