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
<form method="post" action="{*url op="exportReviews"*} javascript:openComments('{url op="exportReviews" reviewId=$reviewId}');" style="position: absolute; right: 0; top: -15px;">
	<input type="hidden" name="reviewId" id="reviewId" value="{$reviewId}"/>
	{if !$published}
		<button id="sendToPublons" type="submit" class="button" style="
		    border-radius: 3px;
            border: none;
            width: 280px;
            text-align: left;
            background-color: #ccc;
            transition-property: all;
            transition-duration: 0.1s;
            transition-timing-function: ease-out;
            padding: 0px;
            cursor: pointer;"
		/><a title="{translate key="plugins.generic.publons.submitExportReview"}" style="display: inline-block;padding: 0px;line-height: 40px; width: 100%; text-align: left;text-decoration: none;border-radius: 3px;color: #111;font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
    <img style="float: left;margin-right: 10px;border-top-left-radius: 3px;border-bottom-left-radius: 3px;" src="https://publons.com/static/images/logos/square/blue_white_shadow.png" height="40" width="40">
    {translate key="plugins.generic.publons.submitExportReview"}
        </a></button>        <br>
	{else}
		<button type="submit" class="button" disabled style="
		border-radius: 3px;
            border: none;
            width: 300px;
            text-align: left;
            background-color: #ccc;
            transition-property: all;
            transition-duration: 0.1s;
            transition-timing-function: ease-out;
            padding: 0px;"><span title="{translate key="plugins.generic.publons.publishedReview"}" style="display: inline-block; width: 100%; text-align: left;padding: 0px;line-height: 40px;text-decoration: none;border-radius: 3px;color: #111;font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
    <img style="float: left;margin-right: 10px;border-top-left-radius: 3px;border-bottom-left-radius: 3px;" src="https://publons.com/static/images/logos/square/blue_white_shadow.png" height="40" width="40">
    {translate key="plugins.generic.publons.publishedReview"}
        </span></button>
	{/if}
</form>
