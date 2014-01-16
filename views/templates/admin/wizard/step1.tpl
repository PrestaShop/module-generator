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

<div id="step-1">
	<form id="validFirstStep">
	<!-- Name -->
	{include file="./forms/name.tpl"}
	<!-- Author -->
	{include file="./forms/author.tpl"}
	<!-- Display Name -->
	{include file="./forms/display.tpl"}
	<!-- Description -->
	{include file="./forms/description.tpl"}
	<!-- Confirm Uninstall -->
	{include file="./forms/uninstall.tpl"}
	<!-- SQL Install/Uninstall -->
	{include file="./forms/sql.tpl"}
	<!-- Back Controller -->
	{include file="./forms/backcontroller.tpl"}
	<!-- Tab -->
	{include file="./forms/tab.tpl"}
	<!-- Hook -->
	{include file="./forms/hook.tpl"}
	<!-- Custom Hook -->
	{*include file="./forms/customhook.tpl"*}
	<!-- Version -->
	{include file="./forms/version.tpl"}
	<!-- Need Instance -->
	{include file="./forms/instance.tpl"}
	<!-- Dependencies -->
	{include file="./forms/dependencies.tpl"}
	<!-- Version Compliance -->
	{include file="./forms/compliance.tpl"}
	</form>
</div>