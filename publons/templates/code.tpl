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
<form method="post" action="{*url op="exportReviews"*} javascript:openComments('{url op="exportReviews" rname=$rname remail=$remail rbody=$rbody rtitle=$rtitle rtitle_en=$rtitle_en journalId=$journalId articleId=$articleId reviewerId=$reviewerId}');" style="position: absolute; right: 0;">
	<input type="hidden" name="rbody" id="rbody" value="{$rbody}"/>
	<input type="hidden" name="rname" id="rname" value="{$rname}"/>
	<input type="hidden" name="remail" id="remail" value="{$remail}"/>
	<input type="hidden" name="rtitle" id="rtitle" value="{$rtitle}"/>
	<input type="hidden" name="rtitle_en" id="rtitle_en" value="{$rtitle_en}"/>

	<input type="hidden" name="journalId" id="journalId" value="{$journalId}"/>
	<input type="hidden" name="articleId" id="articleId" value="{$articleId}"/>
	<input type="hidden" name="reviewerId" id="reviewerId" value="{$reviewerId}"/>
	{if !$published}
		<input type="submit" class="button" value="{translate key="plugins.generic.publons.submitExportReview"}" style="
		border-radius: 3px;
		    border: none;
		    white-space: nowrap;
		    text-overflow: ellipsis;
		    color: #fff;
		    background-color: #336699;
		    box-shadow: 0 -2px 0 rgba(0, 0, 0, 0.2) inset;
		    transition-property: all;
		    transition-duration: 0.1s;
		    transition-timing-function: ease-out;
		    overflow: hidden;
		    position: relative;
		    padding: 0 15px;
		    line-height: 36px;
		    display: block;
		    text-align: center;
		    font-weight: 600;
		    outline: none;
		    border-top: 1px solid rgba(255, 255, 255, 0.1);
		    -ms-box-sizing: border-box;
		    -moz-box-sizing: border-box;
		    -webkit-box-sizing: border-box;
		    box-sizing: border-box;
		    -webkit-transition-property: all;
		    -webkit-transition-duration: 0.1s;
		    -webkit-transition-timing-function: ease-out;
		    -moz-transition-property: all;
		    -moz-transition-duration: 0.1s;
		    -moz-transition-timing-function: ease-out;
		    -ms-transition-property: all;
		    -ms-transition-duration: 0.1s;
		    -ms-transition-timing-function: ease-out;
		    -o-transition-property: all;
		    -o-transition-duration: 0.1s;
		    -o-transition-timing-function: ease-out;
		    font-size: 11px !important;
		    cursor: pointer;
		    text-transform: uppercase;
		    box-sizing: border-box;
		    font-family: "Roboto", sans-serif;"
		/>        <br>
	{else}
		<input type="submit" class="button" value="{translate key="plugins.generic.publons.publishedReview"}" disabled style="
		border-radius: 3px;
		    border: none;
		    white-space: nowrap;
		    text-overflow: ellipsis;
		    color: #444;
		    background-color: #eee;
		    box-shadow: 0 -2px 0 rgba(0, 0, 0, 0.2) inset;
		    transition-property: all;
		    transition-duration: 0.1s;
		    transition-timing-function: ease-out;
		    overflow: hidden;
		    position: relative;
		    padding: 0 8px;
		    line-height: 26px;
		    display: block;
		    text-align: center;
		    font-weight: 600;
		    outline: none;
		    border-top: 1px solid rgba(255, 255, 255, 0.1);
		    -ms-box-sizing: border-box;
		    -moz-box-sizing: border-box;
		    -webkit-box-sizing: border-box;
		    box-sizing: border-box;
		    -webkit-transition-property: all;
		    -webkit-transition-duration: 0.1s;
		    -webkit-transition-timing-function: ease-out;
		    -moz-transition-property: all;
		    -moz-transition-duration: 0.1s;
		    -moz-transition-timing-function: ease-out;
		    -ms-transition-property: all;
		    -ms-transition-duration: 0.1s;
		    -ms-transition-timing-function: ease-out;
		    -o-transition-property: all;
		    -o-transition-duration: 0.1s;
		    -o-transition-timing-function: ease-out;
		    font-size: 11px !important;
		    text-transform: uppercase;
		    box-sizing: border-box;
		    font-family: "Roboto", sans-serif;"/>
	{/if}
</form>
