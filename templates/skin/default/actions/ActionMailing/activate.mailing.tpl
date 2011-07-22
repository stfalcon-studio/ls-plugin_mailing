{assign var="bNoSidebar" value=true}
{include file='header.tpl' menu='talk'}
			<div id="maincol">
				<dl>
				</dl>
				<dl> 
					<dt>{$aLang.ml_subj} </dt>
					<dd>{$oMailing->getMailingTitle()}</dd>
					<dt>{$aLang.ml_status}: </dt>
					<dd>
                        {if ($oMailing->getMailingActive())}
                            {$aLang.ml_ready}
                        {else}
                            {$aLang.ml_wait}
                        {/if}
                    </dd>
				</dl>
					<form method="post" action="">
					<div style="text-align:center">{if ($oMailing->getMailingActive())}
                        {$aLang.ml_deactivation}
                        {else}
                        {$aLang.ml_activation}
                        {/if}</div>
					<div style="text-align:center">
						<input type="submit" name="submit_mailing_activate" style="width:80px" value="{$aLang.ml_yes}">
						<input type="submit" name="cancel" style="width:80px" value="{$aLang.ml_no}">
					</div>
					</form>
				
		</div>
