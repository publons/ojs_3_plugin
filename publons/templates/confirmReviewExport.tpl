{**
 * plugins/generic/publons/templates/confirmReviewExport.tpl
 *
 * Copyright (c) 2016 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * Publons confirm you want to send review to publons page
 *
 *}

{strip}
{include file="submission/comment/header.tpl"}
{/strip}

<script>
    $(function() {ldelim}
        // Attach the form handler.
        $('#exportToPublonsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
    {rdelim});
</script>

<div id="publons-header">
    <img src="https://publons.com/static/images/logos/full/blue_white.png"/>
</div>
<div id="publons-content">
    <div id="publonsExport">

        <h2>{translate key="plugins.generic.publons.confirmation.title"}</h2>

        <p>{translate key="plugins.generic.publons.confirmation.termsAndConditions"}</p>

        <form class="pkp_form" id="exportToPublonsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="connect" save=true}">
            {csrf}

            <input type="hidden" name="reviewId" id="reviewId" value="{$reviewId}"/>
            <button type="submit" class="pkp_button submitFormButton" style="cursor: pointer;"/>
                {translate key="plugins.generic.publons.button.submitExportReview"}
            </button>

        {fbvFormButtons}
        </form>

    </div>
</div>


{include file="submission/comment/footer.tpl"}
