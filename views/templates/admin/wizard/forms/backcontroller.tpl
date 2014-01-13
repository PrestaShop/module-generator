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
	<label for="form-field-4" class="col-sm-2 control-label">
		Module AdminController
	</label>
	<div class="col-sm-9">
		<div class="btn-group toggle-select" data-toggle-name="back_controller" data-toggle="buttons-radio">
		 	 <button type="button" value="1" class="btn" data-toggle="button">Yes</button>
		 	 <button type="button" value="0" class="btn active" data-toggle="button">No</button>
		</div>
		<input type="hidden" name="back_controller" value="0" />
		<div class="switch_display hide">
			<div class="clearfix form-group"></div>
			<select name="tabs_controller_back" class="selectpicker show-menu-arrow" multiple data-live-search="true">
				{foreach $tab_select as $tabs => $tab}
				<optgroup label="{$tabs}">
					{foreach $tab as $key => $value}
					<option value="{$value}">{$value}</option>
					{/foreach}
				</optgroup>
				{/foreach}
			</select>
		</div>
	</div>
</div>