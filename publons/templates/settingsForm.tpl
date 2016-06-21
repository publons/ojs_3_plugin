{**
 * plugins/generic/publons/settingsForm.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Publons plugin settings
 *
 *}
{strip}
	{assign var="pageTitle" value="plugins.generic.publons.list"}
	{include file="common/header.tpl"}
{/strip}
<script>
        {literal}

        $(document).ready(function(){
            $(".fancybox").fancybox()
        })
        {/literal}
</script>
<ul class="menu">
	<li><a href="{plugin_url path="connect"}">{translate key="plugins.generic.publons.settings.connect"}</a></li>
	<li class="current"><a href="{plugin_url path="settings"}">{translate key="plugins.generic.publons.settings"}</a></li>
</ul>
<br/>
<div id="publonsSettings">

		<table width="100%" class="listing">
			<tr>
				<td colspan="4" class="headseparator">&nbsp;</td>
			</tr>
			<tr class="heading" valign="bottom">
				<td width="1%">ID{*translate key="article.id"*}</td>
				<td width="10%">{sort_heading key="plugins.generic.publons.reviewer" sort="reviewer"}</td>
				<td width="80%">{sort_heading key="article.title" sort="title"}</td>
				<td width="9%">{sort_heading key="plugins.generic.publons.date.added" sort="date"}</td>
			</tr>
			<tr>
				<td colspan="4" class="headseparator">&nbsp;</td>
			</tr>
	
		{iterate from=publonsReviews item=publonsReview}
			<tr valign="top">   
				<td>{$publonsReview->getArticleId()|escape}</td>
				<td>
				{assign var="user" value=$userDAO->getById($publonsReview->getReviewerId())}
				<a class="action" href="{url op="userProfile" path=$user->getId()}">{$user->getUsername()|escape}</a><br>
				{$user->getFullName()|escape}<br>
			{assign var=emailString value=$user->getFullName()|concat:" <":$user->getEmail():">"}
			{url|assign:"url" page="user" op="email" to=$emailString|to_array redirectUrl=$currentUrl}
			{icon name="mail" url=$url}
				</td>
				<td><a href="#text{$publonsReview->getArticleId()}" class="fancybox">{$publonsReview->getLocalizedTitle()|escape}</a><br>    <div style="display: none">
<div id="text{$publonsReview->getArticleId()}" >
<h3>{translate key="plugins.generic.publons.submission.review"}</h3>
<p>{$publonsReview->getLocalizedContent()|escape}</p>
</div>
        </div></td>
				<td>{$publonsReview->getDateAdded()|default:"&mdash;"}</td>
			</tr>
			<tr>
				<td colspan="4" class="{if $publonsReviews->eof()}end{/if}separator">&nbsp;</td>
			</tr>
		{/iterate}
		{if $publonsReviews->wasEmpty()}
			<tr>
				<td colspan="4" class="nodata">{translate key="publonsReviews.no"}</td>
			</tr>
			<tr>
				<td colspan="4" class="endseparator">&nbsp;</td>
			</tr>
		{else}
			<tr>
				<td colspan="1" align="left">{page_info iterator=$publonsReviews}</td>
				<td colspan="3" align="right">{page_links anchor="publonsReviews" name="publonsReviews" iterator=$publonsReviews}</td>
			</tr>
		{/if}
		</table>
</div>
{include file="common/footer.tpl"}
