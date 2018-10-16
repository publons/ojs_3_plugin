{**
 * plugins/generic/publons/publonsExportStep.tpl
 *
 * Copyright (c) 2017 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * Publons plugin - sync review with publon step shown on step 4 - completed page
 *
 *}

</div>
<div id="publons-export" class="section">
    <h3>{translate key="plugins.generic.publons.notice.title"}</h3>

    {if $submission->getRecommendation() === null || $submission->getRecommendation() === ''}
        <button type="submit" class="publons-button" disabled>
            <span title="{translate key="plugins.generic.publons.button.completeReview"}">
                <img src="https://publons.com/static/images/logos/square/mono_white_shadow.png" height="30" width="30">
                {translate key="plugins.generic.publons.button.completeReview"}
            </span>
        </button>

    {elseif !$published}
        <div class="pkp_linkActions">
            {assign var=contextId value="reviewStep4"}
            {assign var=staticId value=$contextId|concat:"-":$exportReviewAction->getId():"-button"}
            {assign var=buttonId value=$staticId|concat:"-"|uniqid}

            <a href="#" id="{$buttonId|escape}" title="{translate key="plugins.generic.publons.button.submitExportReview"}" class="pkp_controllers_linkAction pkp_linkaction_{$exportReviewAction->getId()} pkp_linkaction_icon_{$exportReviewAction->getImage()}">
                <button id="sendToPublons" type="submit" class="publons-button" style="cursor: pointer;">
                    <img src="https://publons.com/static/images/logos/square/blue_white_shadow.png" height="30" width="30">
                    {translate key="plugins.generic.publons.button.submitExportReview"}
                </button>
            </a>
        </div>

        <script>
            {* Attach the action handler to the button. *}
            $(function() {ldelim}
                $('#{$buttonId}').pkpHandler(
                    '$.pkp.controllers.linkAction.LinkActionHandler',
                        {include file="linkAction/linkActionOptions.tpl" action=$exportReviewAction selfActivate=$selfActivate staticId=$staticId}
                    );
            {rdelim});
        </script>

    {else}
        <button type="submit" class="publons-button" disabled>
            <span title="{translate key="plugins.generic.button.publons.publishedReview"}">
                <img src="https://publons.com/static/images/logos/square/blue_white_shadow.png" height="30" width="30">
                {translate key="plugins.generic.publons.button.publishedReview"}
            </span>
        </button>
    {/if}
