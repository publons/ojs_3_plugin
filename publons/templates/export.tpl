{**
 * plugins/generic/pln/templates/settingsForm.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * PLN plugin settings
 *
 *}
{strip}
{assign var="pageTitle" value="plugins.generic.publons.export_page"}
{include file="submission/comment/header.tpl"}
{/strip}

<div id="publonsExport">
{if $status==201 OR $status==201}
	<h3>{translate key="plugins.generic.publons.exportSuccessful"}</h3>
	<table width="100%" class="data">
		<tr valign="top">
			<td class="label">{translate key="plugins.generic.publons.reviewBody"}</td>
			<td class="value">{$rbody}</td>
		</tr>
	</table>
{else }
	<h3>{translate key="plugins.generic.publons.exportError"}</h3>
	<table width="100%" class="data">
		{if $status}
		<tr valign="top">
			<td class="label">{translate key="plugins.generic.publons.exportStatus"}</td>
			<td class="value">{$status}</td>
		</tr>
		{/if}
		{if $error OR $info}
		<tr valign="top">
			<td class="label">{translate key="plugins.generic.publons.exportErrorInfo"}</td>
			<td class="value">{$info}&nbsp;&nbsp;&nbsp;{$error}</td>
		</tr>
		{/if}
	</table>

{/if}

</div>
<input type="button" value="{translate key="plugins.generic.publons.backToReviewPage"}" class="button" onclick="window.close()" />
{include file="submission/comment/footer.tpl"}