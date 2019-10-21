{**
 * plugins/generic/publons/templates/publonsSettingsForm.tpl
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
        $('#publonsConnectionForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
    {rdelim});
</script>


<div>

    <form class="pkp_form" id="publonsConnectionForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="connect" save=true}">
        {csrf}

        <p>{translate key="plugins.generic.publons.settings.info"}</p>

        {include file="controllers/notification/inPlaceNotification.tpl" notificationId="publonsConnectionFormNotification"}

        {fbvFormArea id="publonsConnectionFormArea"}
            <table width="100%" class="data">
                <tr valign="top">
                    <td class="label">{fieldLabel name="auth_token" required="true" key="plugins.generic.publons.settings.auth_token"}</td>
                    <td class="value">
                        {fbvElement type="text" id="auth_token" name="auth_token" value="$auth_token" label="plugins.generic.publons.settings.auth_tokenDescription"}
                    </td>
                </tr>
                <tr valign="top">
                    <td class="label">{fieldLabel name="journalToken" required="true" key="plugins.generic.publons.settings.journalToken"}</td>
                    <td class="value">
                        {fbvElement type="text" id="auth_key" name="auth_key" value="$auth_key" label="plugins.generic.publons.settings.journalTokenDescription"}
                    </td>
                </tr>
                <tr valign="top">
                    <td class="label">{fieldLabel name="info_url" key="plugins.generic.publons.settings.url"}</td>
                    <td class="value">
                        {fbvElement type="text" id="info_url" name="info_url" value="$info_url" label="plugins.generic.publons.settings.urlDescription"}
                    </td>
                </tr>
            </table>
            <span class="formRequired">{translate key="common.requiredField"}</span>

        {/fbvFormArea}

        {fbvFormButtons}
    </form>
</div>
<p>{translate key="plugins.generic.publons.settings.ps"}</p>
