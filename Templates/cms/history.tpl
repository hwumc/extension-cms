{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<h3>Page History: {$page->getTitle()|escape}</h3>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Date</th>
            <th>User</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody></tbody>
        {foreach from="$history" item="rev"}
        <tr>
            <td>{$rev.timestamp}</td>
            <td>{include file="userdisplay.tpl" user=$rev.userobject}</td>
            <td>{if $rev.active == 1}<span class="label">Current</span>{/if}</td>
            <td><a class="btn" href="{$cScriptPath}/{$pageslug}/view/{$rev.id}">View</a></td>
            <td><a class="btn" href="{$cScriptPath}/{$pageslug}/edit/{$page->getId()}/{$rev.id}">Edit</a></td>
        </tr>
        {/foreach}
    </tbody>
</table>
{/block}