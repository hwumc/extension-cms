{extends file="base.tpl"}
{block name="body"}
{if $allowCreate == "true"}
	<p><a href="{$cScriptPath}/{$pageslug}/create" class="btn btn-success">{message name="{$pageslug}-button-create"}</a></p>
{/if}
	<p>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>{message name="{$pageslug}-text-pagetitle"}</th>
					<th>{message name="{$pageslug}-text-pageslug"}</th>
					<th>{message name="{$pageslug}-text-accessright"}</th>
					<th>{message name="{$pageslug}-text-menugroup"}</th>
					<th>{message name="{$pageslug}-text-viewpage"}</th>
					<th>{message name="{$pageslug}-text-history"}</th>
					{if $allowEdit == "true"}<th>{message name="{$pageslug}-text-editpage"}</th>{/if}
					{if $allowDelete == "true"}<th>{message name="{$pageslug}-text-deletepage"}</th>{/if}
				</tr>
			</thead>
			<tbody>
				{foreach from="$pagelist" item="page" key="pageid" }
					<tr>
						<th>{$page->getTitle()|escape}</th>
						<td>{$page->getSlug()|escape}</td>
						<td>{$page->getAccessRight()|escape}</td>
						<td>{$page->getMenuGroupSlug()|escape}</td>
						<td><a href="{$cScriptPath}/{$page->getSlug()}" class="btn btn-small btn-info">{message name="{$pageslug}-button-viewpage"}</a></td>
						<td><a href="{$cScriptPath}/{$pageslug}/history/{$pageid}" class="btn btn-small">{message name="{$pageslug}-button-history"}</a></td>
						{if $allowEdit == "true"}<td><a href="{$cScriptPath}/{$pageslug}/edit/{$pageid}" class="btn btn-small btn-warning">{message name="{$pageslug}-button-editpage"}</a></td>{/if}
						{if $allowDelete == "true"}<td><a href="{$cScriptPath}/{$pageslug}/delete/{$pageid}" class="btn btn-small btn-danger">{message name="{$pageslug}-button-deletepage"}</a></td>{/if}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</p>
{/block}
{if $allowDelete == "true"}{/if}