[license]

<div class="bootstrap">
{if $ps_version == 0}
	<!-- Beautiful header -->
	{include file="./header.tpl"}
{/if}
	<!-- Module content -->
	<div id="modulecontent" class="panel panel-default">
		<h3>
			<i class="icon-cogs"></i>
			{l s='Configuration' mod='[module]'}
			<div class="panel-tools">
				<a class="btn btn-xs btn-link panel-collapse collapses"></a>
			</div>
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