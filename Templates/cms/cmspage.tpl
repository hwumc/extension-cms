﻿{extends file="base.tpl"}
{block name="pageheader"}
	<div class="page-header">
	  <h1>{$cmsPageHeader|escape}</h1>
	</div>
{/block}
{block name="pagedescription"}{/block}
{block name="body"}{$cmsPageContent}{/block}