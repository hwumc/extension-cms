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
			<span class="help-block">{message name="{$pageslug}-create-pagetitle-help"}</span>
		</div>
    </div>

    <div class="row-fluid">
        <div class="span6">
            <div class="control-group">
                <label class="control-label" for="slug">{message name="{$pageslug}-create-slug"}</label>
                <div class="controls">
                    <input type="text" id="slug" name="slug" placeholder="{message name="{$pageslug}-create-slug-placeholder"}" required="true" value="{$slug}" {if $allowEdit== "false"}disabled="true" {/if}/>
                    <span class="help-block">{message name="{$pageslug}-create-slug-help"}</span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="accessright">{message name="{$pageslug}-create-accessright"}</label>
                <div class="controls">
                    <input type="text" id="accessright" name="accessright" placeholder="{message name="{$pageslug}-create-accessright-placeholder"}" value="{$accessright}" {if $allowEdit== "false"}disabled="true" {/if} data-provide="typeahead" data-items="4" data-source='{$jsrightslist}'/>
                    <span class="help-block">{message name="{$pageslug}-create-accessright-help"}</span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="accessright">{message name="{$pageslug}-create-menugroup"}</label>
                <div class="controls">
                    <input type="text" id="menugroup" name="menugroup" placeholder="{message name="{$pageslug}-create-menugroup-placeholder"}" value="{$menugroup}" {if $allowEdit== "false"}disabled="true" {/if} data-provide="typeahead" data-items="4" data-source='{$jsmenugrouplist}'/>
                    <span class="help-block">{message name="{$pageslug}-create-menugroup-help"}</span>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label" for="template">{message name="{$pageslug}-create-template"}</label>
                <div class="controls">
                    <select name="template" id="template" {if $allowEdit == "false"} disabled="true" {/if} >
                        <option value="" {if $template == ""}selected="selected"{/if}>Default</option>
                        <optgroup label="Other templates">
                            {foreach from=$templates key="key" item="display"}
                                <option value="{$key|escape}" {if $template == $key}selected="selected"{/if}>{$display|escape}</option>
                            {/foreach}
                        </optgroup>
                    </select>
                    <span class="help-block">{message name="{$pageslug}-create-template-help"}</span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="imagegroup">{message name="{$pageslug}-create-imagegroup"}</label>
                <div class="controls">
                    <select name="imagegroup" id="imagegroup" {if $allowEdit =="false" }disabled="true" {/if} >
                        <option value="" {if $imagegroup == ""}selected="selected"{/if}>None</option>
                        {foreach from=$imagegroups key="key" item="display"}
                            <option value="{$key}" {if $imagegroup == $key}selected="selected"{/if}>{$display->getName()|escape}</option>
                        {/foreach}
                    </select>
                    <span class="help-block">{message name="{$pageslug}-create-imagegroup-help"}</span>
                </div>
            </div>

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

{/block}