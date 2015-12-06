{extends file="base.tpl"}
{block name="head"}
{include file="editorinit.tpl"}
{/block}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-create-header"}</legend>

	<div class="control-group">
		<label class="control-label" for="cmspagetitle">{message name="{$pageslug}-create-pagetitle"}</label>
		<div class="controls">
			<input class="input-xxlarge" type="text" id="cmspagetitle" name="cmspagetitle" placeholder="{message name="{$pageslug}-create-pagetitle-placeholder"}" required="true" value="{$cmspagetitle}" {if $allowEdit == "false"}disabled="true" {/if}/>
			<span class="help-inline">{message name="{$pageslug}-create-pagetitle-help"}</span>
		</div>
	</div>	
	<div class="control-group">
		<label class="control-label" for="slug">{message name="{$pageslug}-create-slug"}</label>
		<div class="controls">
			<input type="text" id="slug" name="slug" placeholder="{message name="{$pageslug}-create-slug-placeholder"}" required="true" value="{$slug}" {if $allowEdit == "false"}disabled="true" {/if}/>
			<span class="help-inline">{message name="{$pageslug}-create-slug-help"}</span>
		</div>
	</div>	
	<div class="control-group">
		<label class="control-label" for="accessright">{message name="{$pageslug}-create-accessright"}</label>
		<div class="controls">
			<input type="text" id="accessright" name="accessright" placeholder="{message name="{$pageslug}-create-accessright-placeholder"}" value="{$accessright}" {if $allowEdit == "false"}disabled="true" {/if} data-provide="typeahead" data-items="4" data-source='{$jsrightslist}'  />
			<span class="help-inline">{message name="{$pageslug}-create-accessright-help"}</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="accessright">{message name="{$pageslug}-create-menugroup"}</label>
		<div class="controls">
			<input type="text" id="menugroup" name="menugroup" placeholder="{message name="{$pageslug}-create-menugroup-placeholder"}" value="{$menugroup}" {if $allowEdit == "false"}disabled="true" {/if} data-provide="typeahead" data-items="4" data-source='{$jsmenugrouplist}' />
			<span class="help-inline">{message name="{$pageslug}-create-menugroup-help"}</span>
		</div>
	</div>

	<fieldset>
		<legend>{message name="{$pageslug}-create-header-content"}</legend>

		<textarea id="pagecontent" name="pagecontent"
				class="span12" rows="20"
				placeholder="{message name="{$pageslug}-create-pagecontent-placeholder"}" 
				{if $allowEdit == "false"}disabled="true" {/if}
					>{$pagecontent}</textarea>
	</fieldset>
	
	<div class="control-group">
		<div class="controls">
			<div class="btn-group">{if $allowEdit == "true"}<button type="submit" class="btn btn-primary">{message name="save"}</button>{/if}<a href="{$cScriptPath}/{$pageslug}" class="btn">{message name="getmeoutofhere"}</a></div>
		</div>
	</div>
</form>

<div class="accordion" id="accordion">
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" href="#collapseOne">
        {message name="{$pageslug}-create-header-history"}
      </a>
    </div>
    <div id="collapseOne" class="accordion-body collapse">
      <div class="accordion-inner">
        <ul>
			{foreach from="$history" item="rev"}
				<li><strong>Revision {$rev.id}</strong> @ {$rev.timestamp} by {$rev.username|escape}</li>
			{/foreach}
		</ul>
      </div>
    </div>
  </div>
</div>
{/block}