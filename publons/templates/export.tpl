{**
 * plugins/generic/pln/templates/settingsForm.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * PLN plugin settings
 *
 *}
{strip}
    {include file="submission/comment/header.tpl"}
{/strip}
<div id="publons-header">
    <img src="https://publons.com/static/images/logos/full/blue_white.png"/>
</div>
<div id="publons-content">
    <div id="publonsExport">
    {if $status==201}
        <h2>
        {if $serverAction == 'REVIEWER_UNSUBSCRIBED'}
            {translate key="plugins.generic.publons.export.Successful"}
        {elseif $serverAction == 'REVIEWER_EMAILED'}
            {translate key="plugins.generic.publons.export.Successful"}
        {elseif $serverAction == 'REVIEWER_CLAIMED'}
            {translate key="plugins.generic.publons.export.Successful"}
        {elseif $serverAction == 'PARTNER_TO_EMAIL'}
            {translate key="plugins.generic.publons.export.Successful"}
        {elseif $serverAction == 'DUPLICATE_REVIEW'}
            {translate key="plugins.generic.publons.export.Duplicate"}
        {else}
            {translate key="plugins.generic.publons.export.Successful"}
        {/if}
        </h2>
        <p>
            {if $serverAction == 'REVIEWER_CLAIMED'}
                {translate key="plugins.generic.publons.export.next.AutoClaimed"}
            {elseif $serverAction == 'PARTNER_TO_EMAIL'}
                {translate key="plugins.generic.publons.export.next.PartnerEmailed"} <br>
                {translate key="plugins.generic.publons.export.next.SetAutoClaim"}
            {elseif $serverAction == 'REVIEWER_EMAILED'}
                {translate key="plugins.generic.publons.export.next.PublonsEmailed"} <br>
                {translate key="plugins.generic.publons.export.next.SetAutoClaim"}
            {elseif $serverAction == 'REVIEWER_UNSUBSCRIBED'}
                {translate key="plugins.generic.publons.export.next.LinkClick"} <br>
                {translate key="plugins.generic.publons.export.next.SetAutoClaim"}
            {else}
            {/if}
        </p>
    {else }
        <h3>{translate key="plugins.generic.publons.export.Error"}</h3>
        <table width="100%" class="data">
            {if $status}
            <tr valign="top">
                <td class="label">{translate key="plugins.generic.publons.exportStatus"}</td>
                <td class="value">{$status}</td>
            </tr>
            {/if}
            {if $error OR $info}
            <tr valign="top">
                <td class="label">{translate key="plugins.generic.publons.exportErrorInfo"}</td>
                <td class="value">{$info}&nbsp;&nbsp;&nbsp;{$error}</td>
            </tr>
            {/if}
        </table>

    {/if}

    </div>
    {if $serverAction == 'PARTNER_TO_EMAIL' ||  $serverAction == 'REVIEWER_EMAILED' || $serverAction == 'REVIEWER_UNSUBSCRIBED'}
        <a href="{ $claimURL}" target="_blank"><button class="primary autowidth" >{translate key="plugins.generic.publons.export.claimReview"}</button></a>
    {/if}
    <button class="autowidth" onclick="window.close()" >
        {translate key="plugins.generic.publons.backToReviewPage"}
    </button>
</div>
{include file="submission/comment/footer.tpl"}
