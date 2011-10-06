{assign var="noSidebar" value=true}
{include file='header.tpl' menu='talk'}
<h1>{$aLang.ml_list}</h1>
{if ($aMailing)}
    <table class="table table-people table-talk">
        <thead>
            <tr>
                <td>{$aLang.ml_subj}</td>
                <td align="center">{$aLang.ml_progress}</td>
                <td>{$aLang.ml_status}</td>
                <td align="center">{$aLang.ml_date}</td>
                <td>{$aLang.ml_action}</td>
            </tr>
        </thead>
        <tbody>
            {foreach from=$aMailing item=oMailing}
                <tr>
                    <td>
                        {$oMailing->getMailingTitle()|escape:'html'}
                    </td>
                    <td align="center">
                        {$oMailing->getMailingSend()} / {$oMailing->getMailingCount()}
                    </td>
                    <td>
                        {if ($oMailing->getMailingCount() && ($oMailing->getMailingSend() == $oMailing->getMailingCount()))}
                            {$aLang.ml_send_all}
                        {elseif ($oMailing->getMailingCount() && $oMailing->getMailingActive())}
                            {$aLang.ml_ready}
                        {else}
                            {$aLang.ml_wait}
                        {/if}

                    </td>
                    <td align="center">
                        {$oMailing->getMailingDate()}
                    </td>
                    <td>

                        {if ($oMailing->getMailingSend() != $oMailing->getMailingCount())}
                            {if ($oMailing->getMailingActive())}
                                <a class="deactivation-mailing" href="{router page='mailing'}activate/{$oMailing->getMailingId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}"
                                   onclick="return confirm('{$aLang.ml_deactivation_confirm}');">{$aLang.ml_deactivation}</a>
                            {else}
                                <a class="activation-mailing" href="{router page='mailing'}activate/{$oMailing->getMailingId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}"
                                   onclick="return confirm('{$aLang.ml_activation_confirm}');">{$aLang.ml_activation}</a>
                            {/if}
                            &nbsp;
                        {/if}
                        {if ($oMailing->getMailingSend() == 0) }
                            <a class="edit-mailing" href="{router page='mailing'}edit/{$oMailing->getMailingId()}">{$aLang.ml_edit}</a>&nbsp;
                        {/if}
                        <a href="{router page='mailing'}delete/{$oMailing->getMailingId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}"  onclick="return confirm('{$aLang.ml_delete_confirm}');">{$aLang.ml_delete}</a>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
{else}
    {$aLang.ml_no_list}
{/if}
{include file='footer.tpl'}