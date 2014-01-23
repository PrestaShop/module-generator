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
	<label for="form-field-2" class="col-sm-2 control-label">
		{l s='Custom Hooks' mod='modulegenerator'}<br />
		<small>({l s='Dynamic hooks' mod='modulegenerator'})</small>
	</label>
	<div class="col-sm-9">
		<span class="switch prestashop-switch input-group col-lg-2">
			<input type="radio" name="custom_hook" id="custom_hook_on" value="1"/>
			<label for="custom_hook_on" class="radioCheck">
				<i class="color_success"></i> {l s='Yes' mod='modulegenerator'}
			</label>
			<input type="radio" name="custom_hook" id="custom_hook_off" value="0" checked="checked" />
			<label for="custom_hook_off" class="radioCheck">
				<i class="color_danger"></i> {l s='No' mod='modulegenerator'}
			</label>
			<a class="slide-button btn"></a>
		</span>
		<div class="switch_display hide">
			Custom...
		</div>
	</div>
</div>