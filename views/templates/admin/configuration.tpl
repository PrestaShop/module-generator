<div class="bootstrap">
	<!-- Beautiful header -->
	{include file="./header.tpl"}

	<h2><img src="{$module_dir}img/logo.png" alt="{l s='Module Generator'}" border="0" /> {l s='Module Generator' mod='modulegenerator'}</h2>

	<div class="panel panel-default">
		<h3>
			<i class="icon-cogs"></i>
			{l s='Configuration' mod='testmodule'}
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

	<!-- Manage translations -->
	{include file="./translations.tpl"}

	<!-- Addons notice -->
	{include file="./addons.tpl"}
</div>