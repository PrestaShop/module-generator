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

<form id="upload" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<label for="form-field-1" class="col-sm-2 control-label">
			{l s='Module Logo' mod='modulegenerator'}
		</label>
		<div class="col-sm-9">
			<div id="drop">
				{l s='Drop Here' mod='modulegenerator'}
				<a>{l s='Browse' mod='modulegenerator'}</a>
				<input type="file" name="upl" />
			</div>
			<ul>
			<!-- The file uploads will be shown here -->
			</ul>
			<span class="help-block"><i class="icon-info-circle"></i> PNG 32x32.</span>
		</div>
	</div>
</form>