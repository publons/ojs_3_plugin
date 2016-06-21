{**
 * plugins/generic/publons/code.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Publons plugin setup instructions for journal managers
 *
 *}
<form method="post" action="{*url op="exportReviews"*} javascript:openComments('{url op="exportReviews" rname=$rname remail=$remail rbody=$rbody rtitle=$rtitle rtitle_en=$rtitle_en journalId=$journalId articleId=$articleId reviewerId=$reviewerId}');">
	<input type="hidden" name="rbody" id="rbody" value="{$rbody}"/>
	<input type="hidden" name="rname" id="rname" value="{$rname}"/>
	<input type="hidden" name="remail" id="remail" value="{$remail}"/>
	<input type="hidden" name="rtitle" id="rtitle" value="{$rtitle}"/>
	<input type="hidden" name="rtitle_en" id="rtitle_en" value="{$rtitle_en}"/>

	<input type="hidden" name="journalId" id="journalId" value="{$journalId}"/>
	<input type="hidden" name="articleId" id="articleId" value="{$articleId}"/>
	<input type="hidden" name="reviewerId" id="reviewerId" value="{$reviewerId}"/>
	{if !$published}
		<input type="submit" class="button" value="{translate key="plugins.generic.publons.submitExportReview"}"/>        <br>
	{else}
		<input type="submit" class="button" value="{translate key="plugins.generic.publons.publishedReview"}" disabled/>
	{/if}
</form>
