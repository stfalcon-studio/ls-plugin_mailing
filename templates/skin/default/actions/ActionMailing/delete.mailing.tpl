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
					<div style="text-align:center">{$aLang.ml_delete_confirm}</div>
					<div style="text-align:center">
						<input type="submit" name="delete_mailing" style="width:80px" value="{$aLang.ml_yes}">
						<input type="submit" name="cancel" style="width:80px" value="{$aLang.ml_cancel}">
					</div>
					</form>
				
		</div>
