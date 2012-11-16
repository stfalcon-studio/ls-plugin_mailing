{assign var="bNoSidebar" value=true}
{include file='header.tpl' menu='talk'}
<style type="text/css" media='all'> 
    @import url({$sTemplateWebPathPluginMailing}/css/style.css); 
</style>
<div class="topic people top-blogs talk-table">
<h1>{$aLang.plugin.mailing.ml_list}</h1>
{if ($aMailing)}
    <table class="table table-blogs" cellspacing="0">
            <thead>                   
                    <tr>
                            <th class="cell-info">&nbsp;</th>
                            <th class="cell-name cell-tab">
                                    {$aLang.plugin.mailing.ml_subj}
                            </th>
                            <th class="cell-readers cell-tab">
                                    {$aLang.plugin.mailing.ml_progress}
                            </th>
                            <th class="cell-readers cell-tab">
                                    {$aLang.plugin.mailing.ml_status}
                            </th>
                            <th class="cell-rating cell-tab align-center">
                                    {$aLang.plugin.mailing.ml_date}
                            </th>
                            <th class="cell-rating cell-tab align-center">
                                    {$aLang.plugin.mailing.ml_action}
                            </th>
                    </tr>
            </thead>

           <tbody>
                {if $aMailing}
                        {foreach from=$aMailing item=oMailing}

                                <tr>
                                        <td class="cell-info">&nbsp;</td>
                                        <td class="cell-name">
                                                {$oMailing->getMailingTitle()|escape:'html'}
                                        </td>
                                        <td class="cell-name">
                                                {$oMailing->getMailingSend()} / {$oMailing->getMailingCount()}
                                        </td>
                                        <td class="cell-name">
                                            {if ($oMailing->getMailingSend() == $oMailing->getMailingCount())}
                                                {$aLang.plugin.mailing.ml_send_all}
                                            {elseif ($oMailing->getMailingActive())}
                                                {$aLang.plugin.mailing.ml_ready}
                                            {else}
                                                {$aLang.plugin.mailing.ml_wait}
                                            {/if}
                                            {if $oMailing->getMailingDirect()}{$aLang.plugin.mailing.ml_send_direct}{/if}
                                            {if $oMailing->getMailingTalk()}{$aLang.plugin.mailing.ml_send_talk}{/if}
                                        </td>
                                        <td class="cell-name">
                                                {$oMailing->getMailingDate()}
                                        </td>
                                        <td class="cell-name">
                                            {if ($oMailing->getMailingSend() != $oMailing->getMailingCount())}
                                                <a class="deactivation-mailing" href="{router page='mailing'}activate/{$oMailing->getMailingId()}">
                                                    {if ($oMailing->getMailingActive())}
                                                {$aLang.plugin.mailing.ml_deactivation}{else}{$aLang.plugin.mailing.ml_activation}{/if}</a>&nbsp;
                                            {/if}
                                            {if ($oMailing->getMailingSend() == 0) }
                                                 <a class="edit-mailing" href="{router page='mailing'}edit/{$oMailing->getMailingId()}">{$aLang.plugin.mailing.ml_edit}</a>&nbsp;
                                            {/if}
                                            <a class="delete-mailing" href="{router page='mailing'}delete/{$oMailing->getMailingId()}">{$aLang.plugin.mailing.ml_delete}</a>
                                        </td>
                                </tr>
                        {/foreach}
                {else}
                        <tr>
                                <td colspan="5">
                                        {$aLang.plugin.mailing.ml_no_list}
                                </td>
                        </tr>
                {/if}
            </tbody>
    </table>
{/if}
</div>
{include file='footer.tpl'}