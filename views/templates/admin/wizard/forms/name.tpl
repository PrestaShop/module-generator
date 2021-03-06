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

<div class="form-group">
	<label for="form-field-1" class="col-sm-2 control-label required">
		{l s='Module Name' mod='modulegenerator'}
	</label>
	<div class="col-sm-9">
		<input type="text" class="form-control required" value="" id="modulename" name="module_name" placeholder="{l s='Module Name' mod='modulegenerator'}">
		<span class="help-block"><i class="icon-info-circle"></i> {l s='This attributes serves as an internal identifier, without special characters or spaces, and keep it lower-case. In effect, the value MUST be the name of the module\'s folder.' mod='modulegenerator'}</span>
	</div>
</div>