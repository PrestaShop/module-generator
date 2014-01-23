{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="page-head">
	<h2 class="page-title">
		{l s='Module Generator' mod='modulegenerator'}
	</h2>
	<ul class="breadcrumb page-breadcrumb">
		<li>
			<i class="icon-puzzle-piece"></i>Modules
		</li>
		<li>modulegenerator</li>
		<li>
			<i class="icon-wrench"></i>
			{l s='Configuration' mod='modulegenerator'}
		</li>
	</ul>
	<div class="page-bar toolbarBox">
		<div class="btn-toolbar">
			<ul class="cc_button nav nav-pills pull-right">
				{if $module_active == '1'}
				<li>
					<a id="desc-module-hook" class="toolbar_btn" href="{$module_form|escape:'htmlall':'UTF-8'}&amp;enable=0" title="{l s='Disable' mod='modulegenerator'}">
						<i class="process-icon-off"></i>
						<div>{l s='Disable' mod='modulegenerator'}</div>
					</a>
				</li>
				{else}
				<li>
					<a id="desc-module-hook" class="toolbar_btn" href="{$module_form|escape:'htmlall':'UTF-8'}&amp;enable=1" title="{l s='Enable' mod='modulegenerator'}">
						<i class="process-icon-off"></i>
						<div>{l s='Enable' mod='modulegenerator'}</div>
					</a>
				</li>
				{/if}
				<li>
					<a id="desc-module-hook" class="toolbar_btn" href="{$module_form|escape:'htmlall':'UTF-8'}&amp;uninstall={$module_name|escape:'htmlall':'UTF-8'}" title="{l s='Uninstall' mod='modulegenerator'}">
						<i class="process-icon-uninstall"></i>
						<div>{l s='Uninstall' mod='modulegenerator'}</div>
					</a>
				</li>
				<li>
					<a id="desc-module-hook" class="toolbar_btn" href="{$module_form|escape:'htmlall':'UTF-8'}&amp;reset" title="{l s='Reset' mod='modulegenerator'}">
						<i class="process-icon-reset"></i>
						<div>{l s='Reset' mod='modulegenerator'}</div>
					</a>
				</li>
				<li>
					<a id="desc-module-hook" class="toolbar_btn" href="{$module_hook|escape:'htmlall':'UTF-8'}" title="{l s='Manage hooks' mod='modulegenerator'}">
						<i class="process-icon-anchor"></i>
						<div>{l s='Manage hooks' mod='modulegenerator'}</div>
					</a>
				</li>
				<li>
					<a id="desc-module-back" class="toolbar_btn" href="{$module_back|escape:'htmlall':'UTF-8'}" title="{l s='Back' mod='modulegenerator'}">
						<i class="process-icon-back"></i>
						<div>{l s='Back' mod='modulegenerator'}</div>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>