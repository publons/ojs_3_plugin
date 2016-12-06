{**
 * plugins/generic/publons/publonsStep.tpl
 *
 * Distributed under the GNU GPL v3.
 *
 * Publons plugin - sync review with publon step
 *
 *}
 <tr><td colspan="2">&nbsp;</td></tr>
 <tr>
    <td>6.</td>
    <td>
        <span>{translate key="plugins.generic.publons.export_page"} {if $infoURL }{translate key="plugins.generic.publons.reviewInstructions"} <a href="{$infoURL}" target="_blank">{$infoURL}</a> {/if}</span>
    </td>
</tr>
<tr>
    <td></td>
    <td>
        <form method="post" action="javascript:openComments('{url op="exportReviews" reviewId=$reviewId}');">
            <input type="hidden" name="reviewId" id="reviewId" value="{$reviewId}"/>
            {if $submission->getRecommendation() === null || $submission->getRecommendation() === ''}
                <button type="submit" class="publons-button" disabled>
                    <span title="{translate key="plugins.generic.publons.publishedReview"}">
                        <img src="https://publons.com/static/images/logos/square/mono_white_shadow.png" height="30" width="30">
                        {translate key="plugins.generic.publons.publishedReview"}
                    </span>
                </button>
            {elseif !$published}
                <button id="sendToPublons" type="submit" class="publons-button" style="cursor: pointer;"/>
                    <a title="{translate key="plugins.generic.publons.submitExportReview"}">
                        <img src="https://publons.com/static/images/logos/square/blue_white_shadow.png" height="30" width="30">
                        {translate key="plugins.generic.publons.submitExportReview"}
                    </a>
                </button>
            {else}
                <button type="submit" class="publons-button" disabled>
                    <span title="{translate key="plugins.generic.publons.publishedReview"}">
                        <img src="https://publons.com/static/images/logos/square/blue_white_shadow.png" height="30" width="30">
                        {translate key="plugins.generic.publons.publishedReview"}
                    </span>
                </button>
            {/if}
        </form>
    </td>
 </tr>
