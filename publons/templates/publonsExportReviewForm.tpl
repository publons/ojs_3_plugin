{**
 * plugins/generic/publons/templates/publonsExportReviewForm.tpl
 *
 * Copyright (c) 2017 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * Connect to Publons Network
 *
 *}
{strip}
    {assign var="pageTitle" value="plugins.generic.publons.displayName"}
{/strip}

<script>
    $(function() {ldelim}
        // Attach the form handler.
        $('#publonsExportReviewForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
    {rdelim});
</script>

<div>
    <form class="pkp_form" id="publonsExportReviewForm" method="post" action="{$pageURL}">

        <div id="publons-header">
            <div id="publons-background">
                <img src="https://publons.com/static/images/logos/full/blue_white.png"/>
            </div>
        </div>

        <div id="publons-content">
            <div id="publonsExport">

                <h2>{translate key="plugins.generic.publons.confirmation.title"}</h2>

                <p>{translate key="plugins.generic.publons.confirmation.termsAndConditions"}</p>
            </div>
        </div>

        {csrf}
        {include file="controllers/notification/inPlaceNotification.tpl" notificationId="publonsExportReviewFormNotification"}

        {fbvFormArea id="publonsExportReviewFormArea"}

        {/fbvFormArea}

        {fbvFormButtons submitText="plugins.generic.publons.button.submitExportReview" }
    </form>
</div>
