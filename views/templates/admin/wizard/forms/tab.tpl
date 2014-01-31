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
	<label for="form-field-2" class="col-sm-2 control-label required">
		{l s='Module Tab' mod='modulegenerator'}
	</label>
	<div class="col-sm-9">
		<select id="module_tab" name="module_tab" class="selectpicker show-menu-arrow show-tick required" data-live-search="true">
			<option value="administration">Administration</option>
			<option value="advertising_marketing">Advertising & Marketing</option>
			<option value="analytics_stats">Analytics & Stats</option>
			<option value="billing_invoicing">Billing & Invoices</option>
			<option value="checkout">Checkout</option>
			<option value="content_management">Content Management</option>
			<option value="emailing">E-mailing</option>
			<option value="export">Export</option>
			<option value="front_office_features">Front Office Features</option>
			<option value="i18n_localization">I18n & Localization</option>
			<option value="market_place">Market Place</option>
			<option value="migration_tools">Migration Tools</option>
			<option value="mobile">Mobile</option>
			<option value="others">Other Modules</option>
			<option value="payments_gateways">Payments & Gateways</option>
			<option value="payment_security">Payment Security</option>
			<option value="pricing_promotion">Pricing & Promotion</option>
			<option value="quick_bulk_update">Quick / Bulk update</option>
			<option value="search_filter">Search & Filter</option>
			<option value="seo">SEO</option>
			<option value="shipping_logistics">Shipping & Logistics</option>
			<option value="slideshows">Slideshows</option>
			<option value="smart_shopping">Smart Shopping</option>
			<option value="social_networks">Social Networks</option>
		</select>
		<span class="help-block"><i class="icon-info-circle"></i> {l s='The title for the section that shall contain this module in PrestaShop\'s back-office modules list.' mod='modulegenerator'}</span>
	</div>
</div>