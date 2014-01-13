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
		Module Confirm Uninstall
	</label>
	<div class="col-sm-9">
		<div class="btn-group toggle-select" data-toggle-name="confirm_uninstall" data-toggle="buttons-radio" >
		 	 <button type="button" value="1" class="btn" data-toggle="button">Yes</button>
		 	 <button type="button" value="0" class="btn active" data-toggle="button">No</button>
		</div>
		<input type="hidden" name="confirm_uninstall" value="0" />
		<div class="switch_display hide">
			<div class="clearfix form-group"></div>
			<input type="text" class="form-control" id="form-field-7" name="module_uninstall" value="Are you sure you want to uninstall? You will lose all your settings!" placeholder="Are you sure you want to uninstall? You will lose all your settings!">
			<span class="help-block"><i class="fa fa-info-circle"></i> Asking the administrator if he really does want to uninstall the module</span>
		</div>
	</div>
</div>