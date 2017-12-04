{**
 * plugins/generic/pln/templates/settingsForm.tpl
 *
 *
 * Copyright (c) 2016 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * Export review to publons result page
 *
 *}
<div id="publons-header">
    <div id="publons-background">
        <img src="https://publons.com/static/images/logos/full/blue_white.png"/>
    </div>
</div>
<div id="publons-content">
    <div id="publonsExport">
        {if $status==201}
            <h2>
            {if $serverAction == 'DUPLICATE_REVIEW'}
                {translate key="plugins.generic.publons.export.duplicate"}
            {else}
                {translate key="plugins.generic.publons.export.successful"}
            {/if}
            </h2>
            <p>
                {if $serverAction == 'REVIEWER_CLAIMED'}
                    {translate key="plugins.generic.publons.export.next.autoClaimed"}
                {elseif $serverAction == 'PARTNER_TO_EMAIL'}
                    {translate key="plugins.generic.publons.export.next.partnerEmailed"} <br>
                    {translate key="plugins.generic.publons.export.next.setAutoClaim"}
                {elseif $serverAction == 'REVIEWER_EMAILED'}
                    {translate key="plugins.generic.publons.export.next.publonsEmailed"} <br>
                    {translate key="plugins.generic.publons.export.next.setAutoClaim"}
                {elseif $serverAction == 'REVIEWER_UNSUBSCRIBED'}
                    {translate key="plugins.generic.publons.export.next.linkClick"} <br>
                    {translate key="plugins.generic.publons.export.next.setAutoClaim"}
                {/if}
            </p>
        {else }
            <h3>{translate key="plugins.generic.publons.export.error"}</h3>
            <p>
                {if $status==400}
                    {translate key="plugins.generic.publons.export.error.400"}
                {elseif $status==500}
                    {translate key="plugins.generic.publons.export.error.500"}
                {elseif $info }
                    {$info}
                {/if}
            </p>
        {/if}

    </div>
    {if $serverAction == 'PARTNER_TO_EMAIL' ||  $serverAction == 'REVIEWER_EMAILED' || $serverAction == 'REVIEWER_UNSUBSCRIBED'}
        <a href="{ $claimURL}" target="_blank" class="pkp_button">{translate key="plugins.generic.publons.export.claimReview"}</a>
    {/if}
</div>
