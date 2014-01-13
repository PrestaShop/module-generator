[license]

<div class="bootstrap">
{if $ps_version == 0}
	<!-- Beautiful header -->
	{include file="./header.tpl"}
{/if}

	<h2><img src="{$module_dir}img/logo.png" alt="[text]" border="0" /> [text]</h2>
	<div class="panel panel-default">
		<h3>
			<i class="icon-cogs"></i>
			{l s='Configuration' mod='[module]'}
		</h3>
		<div class="form-group">
			[form]
		</div>
	</div>
	<!-- Addons notice -->
	{include file="./addons.tpl"}

{if $ps_version == 0}
	<!-- Manage translations -->
	{include file="./translations.tpl"}
{/if}
</div>