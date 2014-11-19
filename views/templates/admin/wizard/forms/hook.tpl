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
		{l s='Add Hooks' mod='modulegenerator'}
	</label>
	<div class="col-sm-9">
		<select name="module_hook_front" class="selectpicker show-menu-arrow show-tick" multiple data-live-search="true">
			<optgroup label="General hooks">
				<option value="displayHeader">displayHeader</option>
				<option value="displayTop">displayTop</option>
				<option value="displayHome">displayHome</option>
				<option value="displayLeftColumn">displayLeftColumn</option>
				<option value="displayRightColumn">displayRightColumn</option>
				<option value="displayFooter">displayFooter</option>
			</optgroup>
			<optgroup label="Product page">
				<option value="displayLeftColumnProduct">displayLeftColumnProduct</option>
				<option value="displayRightColumnProduct">displayRightColumnProduct</option>
				<option value="displayProductButtons">displayProductButtons</option>
				<option value="actionProductOutOfStock">actionProductOutOfStock</option>
				<option value="displayFooterProduct">displayFooterProduct</option>
				<option value="displayProductTab">displayProductTab</option>
				<option value="displayProductTabContent">displayProductTabContent</option>
			</optgroup>
			<optgroup label="Cart page">
				<option value="actionCartSave">actionCartSave</option>
				<option value="displayShoppingCartFooter">displayShoppingCartFooter</option>
				<option value="displayShoppingCart">displayShoppingCart</option>
				<option value="displayCustomerAccountFormTop">displayCustomerAccountFormTop</option>
				<option value="displayCustomerAccountForm">displayCustomerAccountForm</option>
				<option value="actionCustomerAccountAdd">actionCustomerAccountAdd</option>
				<option value="displayCustomerAccount">displayCustomerAccount</option>
				<option value="displayMyAccountBlock">displayMyAccountBlock</option>
				<option value="displayMyAccountBlockfooter">displayMyAccountBlockfooter</option>
				<option value="actionAuthentication">actionAuthentication</option>
				<option value="actionBeforeAuthentication">actionBeforeAuthentication</option>
			</optgroup>
			<optgroup label="Search page">
				<option value="actionSearch">actionSearch</option>
			</optgroup>
			<optgroup label="Carrier choice page">
				<option value="displayBeforeCarrier">displayBeforeCarrier</option>
				<option value="displayCarrierList">displayCarrierList</option>
			</optgroup>
			<optgroup label="Payment page">
				<option value="displayPaymentTop">displayPaymentTop</option>
				<option value="displayPayment">displayPayment</option>
				<option value="displayPaymentReturn">displayPaymentReturn</option>
				<option value="displayOrderConfirmation">displayOrderConfirmation</option>
				<option value="displayBeforePayment">displayBeforePayment</option>
			</optgroup>
			<optgroup label="Order page">
				<option value="actionOrderReturn">actionOrderReturn</option>
				<option value="displayPDFInvoice">displayPDFInvoice</option>
			</optgroup>
			<!--
			<optgroup label="Mobile theme hooks">
				<option value="displayMobileTopSiteMap">displayMobileTopSiteMap</option>
				<option value="displayMobileHeader">displayMobileHeader</option>
				<option value="displayMobileShoppingCartTop">displayMobileShoppingCartTop</option>
				<option value="displayMobileAddToCartTop">displayMobileAddToCartTop</option>
				<option value="displayMobileShoppingCartTop">displayMobileShoppingCartTop</option>
				<option value="displayMobileShoppingCartBottom">displayMobileShoppingCartBottom</option>
				<option value="displayMobileTopSiteMap">displayMobileTopSiteMap</option>
				<option value="displayMobileFooterChoice">displayMobileFooterChoice</option>
				<option value="displayMobileShoppingCartTop">displayMobileShoppingCartTop</option>
				<option value="displayMobileShoppingCartBottom">displayMobileShoppingCartBottom</option>
				<option value="displayMobileIndex">displayMobileIndex</option>
				<option value="mobileCustomerAccount">mobileCustomerAccount</option>
				<option value="displayMobileTop">displayMobileTop</option>
				<option value="displayMobileAddToCartTop">displayMobileAddToCartTop</option>
				<option value="displayMobileShoppingCartTop">displayMobileShoppingCartTop</option>
				<option value="displayMobileShoppingCartButton">displayMobileShoppingCartButton</option>
				<option value="displayMobileShoppingCartBottom">displayMobileShoppingCartBottom</option>
				<option value="displayMobileShoppingCartTop">displayMobileShoppingCartTop</option>
				<option value="displayMobileShoppingCartBottom">displayMobileShoppingCartBottom</option>
			</optgroup>
			-->
		</select>

		<select name="module_hook_back" class="selectpicker show-menu-arrow show-tick" multiple data-live-search="true">
			<optgroup label="General hooks">
				<option value="displayBackOfficeHome">displayBackOfficeHome</option>
				<option value="displayBackOfficeHeader">displayBackOfficeHeader</option>
				<option value="displayBackOfficeTop">displayBackOfficeTop</option>
				<option value="displayBackOfficeFooter">displayBackOfficeFooter</option>
			</optgroup>
			<optgroup label="Orders and order details">
				<option value="actionValidateOrder">actionValidateOrder</option>
				<option value="actionPaymentConfirmation">actionPaymentConfirmation</option>
				<option value="actionOrderStatusUpdate">actionOrderStatusUpdate</option>
				<option value="actionOrderStatusPostUpdate">actionOrderStatusPostUpdate</option>
				<option value="actionProductCancel">actionProductCancel</option>
				<option value="displayInvoice">displayInvoice</option>
				<option value="displayAdminOrder">displayAdminOrder</option>
				<option value="actionOrderSlipAdd">actionOrderSlipAdd</option>
			</optgroup>
			<optgroup label="Products">
				<option value="actionProductSave">actionProductSave</option>
				<option value="actionUpdateQuantity">actionUpdateQuantity</option>
				<option value="actionProductAttributeUpdate">actionProductAttributeUpdate</option>
				<option value="actionProductAttributeDelete">actionProductAttributeDelete</option>
				<option value="actionWatermark">actionWatermark</option>
				<option value="displayAttributeForm">displayAttributeForm</option>
				<option value="displayAttributeGroupForm">displayAttributeGroupForm</option>
				<option value="displayAttributeGroupPostProcess">displayAttributeGroupPostProcess</option>
				<option value="displayFeatureForm">displayFeatureForm</option>
				<option value="displayFeaturePostProcess">displayFeaturePostProcess</option>
				<option value="displayFeatureValueForm">displayFeatureValueForm</option>
				<option value="displayFeatureValuePostProcess">displayFeatureValuePostProcess</option>
			</optgroup>
			<optgroup label="Statistics">
				<option value="displayAdminStatsGraphEngine">displayAdminStatsGraphEngine</option>
				<option value="displayAdminStatsGridEngine">displayAdminStatsGridEngine</option>
				<option value="displayAdminStatsModules">displayAdminStatsModules</option>
			</optgroup>
			<optgroup label="Clients">
				<option value="displayAdminCustomers">displayAdminCustomers</option>
			</optgroup>
			<optgroup label="Carriers">
				<option value="actionCarrierUpdate">actionCarrierUpdate</option>
			</optgroup>
		</select>
		<span class="help-block"><i class="icon-info-circle"></i> {l s='Select multiple hooks for the front office and/or the back office' mod='modulegenerator'}</span>
	</div>
</div>