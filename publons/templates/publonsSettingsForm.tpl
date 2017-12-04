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

<p>{translate key="plugins.generic.publons.settings.info"}</p>

<div>

    <form class="pkp_form" id="publonsConnectionForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="connect" save=true}">
        {csrf}
        {include file="controllers/notification/inPlaceNotification.tpl" notificationId="publonsConnectionFormNotification"}

        {fbvFormArea id="publonsConnectionFormArea"}
            <table width="100%" class="data">
                <tr valign="top">
                    <td class="label">{fieldLabel name="username" required="true" key="user.email"}</td>
                    <td class="value">
                        {fbvElement type="text" id="username" name="username" value="" label="plugins.generic.publons.settings.usernameDescription"}
                    </td>
                </tr>
                <tr valign="top">
                    <td class="label">{fieldLabel name="password" required="true" key="user.password"}</td>
                    <td class="value">
                        {fbvElement type="text" id="password" name="password" value="" password="true" label="plugins.generic.publons.settings.passwordDescription"}
                    </td>
                </tr>
                <tr valign="top">
                    <td class="label">{fieldLabel name="auth_key" required="true" key="plugins.generic.publons.settings.auth_key"}</td>
                    <td class="value">
                        {fbvElement type="text" id="auth_key" name="auth_key" value="$auth_key" label="plugins.generic.publons.settings.auth_keyDescription"}
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
