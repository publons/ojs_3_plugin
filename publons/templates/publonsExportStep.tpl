{**
 * plugins/generic/publons/publonsExportStep.tpl
 *
 * Copyright (c) 2017 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * Publons plugin - sync review with publon step shown on step 4 - completed page
 *
 *}

<form method="post" action="javascript:openComments('{url op="exportReviews" reviewId=$reviewId}');">
    <input type="hidden" name="reviewId" id="reviewId" value="{$reviewId}"/>
    {if $submission->getRecommendation() === null || $submission->getRecommendation() === ''}
        <button type="submit" class="publons-button" disabled>
            <span title="{translate key="plugins.generic.publons.button.completeReview"}">
                <img src="https://publons.com/static/images/logos/square/mono_white_shadow.png" height="30" width="30">
                {translate key="plugins.generic.publons.button.completeReview"}
            </span>
        </button>
    {elseif !$published}
        <button id="sendToPublons" type="submit" class="publons-button" style="cursor: pointer;">
            <a title="{translate key="plugins.generic.publons.button.submitExportReview"}">
                <img src="https://publons.com/static/images/logos/square/blue_white_shadow.png" height="30" width="30">
                {translate key="plugins.generic.publons.button.submitExportReview"}
            </a>
        </button>
    {else}
        <button type="submit" class="publons-button" disabled>
            <span title="{translate key="plugins.generic.button.publons.publishedReview"}">
                <img src="https://publons.com/static/images/logos/square/blue_white_shadow.png" height="30" width="30">
                {translate key="plugins.generic.publons.button.publishedReview"}
            </span>
        </button>
    {/if}
</form>
