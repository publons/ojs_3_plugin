{**
 * plugins/generic/publons/templates/publonsAuthForm.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Connect to Publons Network
 *
 *}
{strip}
	{assign var="pageTitle" value="plugins.generic.publons.displayName"}
	{include file="common/header.tpl"}
{/strip}

<ul class="menu">
	<li class="current"><a href="{plugin_url path="connect"}">{translate key="plugins.generic.publons.settings.connect"}</a></li>
	<li><a href="{plugin_url path="settings"}">{translate key="plugins.generic.publons.settings"}</a></li>
</ul>

<p>{translate key="plugins.generic.publons.connect.info"}</p>

<div style="margin: 1em 0;">

	<form method="post" action="{plugin_url path="connect"}"">
		{include file="common/formErrors.tpl"}

		<table width="100%" class="data">
			<tr valign="top">
				<td class="label">{fieldLabel name="username" required="true" key="user.username"}</td>
				<td class="value"><input type="text" size="73" name="username" id="username" value="{$username|escape}" size="20" maxlength="90" class="textField" /></td>
			</tr>			 
			<tr>
				<td>&nbsp;</td>
				<td>{translate key="plugins.generic.publons.settings.usernameDescription"}</td>
			</tr>
			<tr valign="top">
				<td class="label">{fieldLabel name="password" required="true" key="user.password"}</td>
				<td class="value">
					<input type="password" size=73" name="password" id="password" value="{$password|escape}" size="20" maxlength="90" class="textField"/>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>{translate key="plugins.generic.publons.settings.passwordDescription"}</td>
			</tr>
			<tr valign="top">
				<td class="label">{fieldLabel name="auth_key" required="true" key="plugins.generic.publons.settings.auth_key"}</td>
				<td class="value">
					<input type="password" size="73" name="auth_key" id="auth_key" value="{$auth_key|escape}" size="20" maxlength="90" class="textField"/>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>{translate key="plugins.generic.publons.settings.auth_keyDescription"}</td>
			</tr>
		</table>
		<input type="submit" class="button defaultButton" name="save" value="{translate key="common.save"}"	/> 
		<input type="button" class="button" value="{translate key="common.cancel"}" onclick="document.location='{plugin_url path=""}';"/>
	</form>
	<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
<p>{translate key="plugins.generic.publons.connect.ps"}</p>
{include file="common/footer.tpl"}
