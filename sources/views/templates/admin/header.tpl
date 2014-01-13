[license]

<div class="page-head">
	<h2 class="page-title">
		{l s='Configure [text]' mod='[module]'} ([module]) 
	</h2>
	<div class="page-bar toolbarBox">
		<div class="btn-toolbar">
			<ul class="cc_button nav nav-pills pull-right">
				{if $module_active == '1'}
				<li>
					<a id="desc-module-hook" class="toolbar_btn" href="{$module_form|escape:'htmlall':'UTF-8'}&amp;enable=0" title="{l s='Disable' mod='[module]'}">
						<i class="process-icon-off"></i>
						<div>{l s='Disable' mod='[module]'}</div>
					</a>
				</li>
				{else}
				<li>
					<a id="desc-module-hook" class="toolbar_btn" href="{$module_form|escape:'htmlall':'UTF-8'}&amp;enable=1" title="{l s='Enable' mod='[module]'}">
						<i class="process-icon-off"></i>
						<div>{l s='Enable' mod='[module]'}</div>
					</a>
				</li>
				{/if}
				<li>
					<a id="desc-module-hook" class="toolbar_btn" href="{$module_form|escape:'htmlall':'UTF-8'}&amp;uninstall={$module_name|escape:'htmlall':'UTF-8'}" title="{l s='Uninstall' mod='[module]'}">
						<i class="process-icon-uninstall"></i>
						<div>{l s='Uninstall' mod='[module]'}</div>
					</a>
				</li>
				<li>
					<a id="desc-module-hook" class="toolbar_btn" href="{$module_form|escape:'htmlall':'UTF-8'}&amp;reset" title="{l s='Reset' mod='[module]'}">
						<i class="process-icon-reset"></i>
						<div>{l s='Reset' mod='[module]'}</div>
					</a>
				</li>
				<li>
					<a id="desc-module-hook" class="toolbar_btn" href="{$module_hook|escape:'htmlall':'UTF-8'}" title="{l s='Manage hooks' mod='[module]'}">
						<i class="process-icon-anchor"></i>
						<div>{l s='Manage hooks' mod='[module]'}</div>
					</a>
				</li>
				<li>
					<a id="desc-module-back" class="toolbar_btn" href="{$module_back|escape:'htmlall':'UTF-8'}" title="{l s='Back' mod='[module]'}">
						<i class="process-icon-back"></i>
						<div>{l s='Back' mod='[module]'}</div>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>