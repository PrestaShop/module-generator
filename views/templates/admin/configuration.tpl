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

<div class="bootstrap">
{if $ps_version == 0}
	<!-- Beautiful header -->
	{include file="./header.tpl"}
{/if}

	<div class="panel panel-default">
		<h3>
			<i class="icon-cogs"></i>
			{l s='Configuration' mod='modulegenerator'}
			<div class="panel-tools">
				<a class="btn btn-xs btn-link panel-collapse collapses"></a>
			</div>
		</h3>
		<div class="form-group">
			<div class="smart-wizard form-horizontal">
				<div id="wizard" class="swMain">
					{include file="./wizard/wizardStep.tpl"}
					{include file="./wizard/step1.tpl"}
					{include file="./wizard/step2.tpl"}
					{include file="./wizard/step3.tpl"}
					{include file="./wizard/buttons.tpl"}
				</div>

			</div>
		</div>
	</div>
{if $ps_version == 0}
	<!-- Manage translations -->
	{include file="./translations.tpl"}
{/if}

	<!-- Addons notice -->
	{include file="./addons.tpl"}
</div>