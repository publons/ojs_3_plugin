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
                <button type="submit" class="button" disabled style="
                    border-radius: 3px;
                    border: none;
                    text-align: left;
                    background-color: #ccc;
                    transition-property: all;
                    transition-duration: 0.1s;
                    transition-timing-function: ease-out;
                    padding: 0px;">
                    <span title="{translate key="plugins.generic.publons.publishedReview"}" style="display: inline-block; text-align: left;padding: 0px 10px 0px 0px;line-height: 30px;text-decoration: none;border-radius: 3px;color: #111;font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                <img style="float: left;margin-right: 10px;border-top-left-radius: 3px;border-bottom-left-radius: 3px;" src="https://publons.com/static/images/logos/square/mono_white_shadow.png" height="30" width="30">
                {translate key="plugins.generic.publons.completeReview"}
                </span></button>
            {elseif !$published}
                <button id="sendToPublons" type="submit" class="button" style="
                    border-radius: 3px;
                    border: none;
                    text-align: left;
                    background-color: #ccc;
                    transition-property: all;
                    transition-duration: 0.1s;
                    transition-timing-function: ease-out;
                    padding: 0px;
                    cursor: pointer;"
                /><a title="{translate key="plugins.generic.publons.submitExportReview"}" style="display: inline-block; padding: 0px 10px 0px 0px;line-height: 30px; text-align: left;text-decoration: none;border-radius: 3px;color: #111;font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                <img style="float: left;margin-right: 10px;border-top-left-radius: 3px;border-bottom-left-radius: 3px;" src="https://publons.com/static/images/logos/square/blue_white_shadow.png" height="30" width="30">
                {translate key="plugins.generic.publons.submitExportReview"}
                </a></button>
            {else}
                <button type="submit" class="button" disabled style="
                    border-radius: 3px;
                    border: none;
                    text-align: left;
                    background-color: #ccc;
                    transition-property: all;
                    transition-duration: 0.1s;
                    transition-timing-function: ease-out;
                    padding: 0px;">
                    <span title="{translate key="plugins.generic.publons.publishedReview"}" style="display: inline-block; text-align: left; padding: 0px 10px 0px 0px;line-height: 30px;text-decoration: none;border-radius: 3px;color: #111;font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                <img style="float: left;margin-right: 10px;border-top-left-radius: 3px;border-bottom-left-radius: 3px;" src="https://publons.com/static/images/logos/square/blue_white_shadow.png" height="30" width="30">
                {translate key="plugins.generic.publons.publishedReview"}
                </span></button>
            {/if}
        </form>
    </td>
 </tr>