[license]

{if $ps_version == 0}
<div class="bootstrap">
	<!-- Beautiful header -->
	{include file="./header.tpl"}
{/if}
	<!-- Module content -->
	<!-- Module content -->
	<div id="modulecontent" class="clearfix">
		<!-- Nav tabs -->
		<div class="col-lg-2">
			<div class="list-group">
				<a href="#documentation" class="list-group-item active" data-toggle="tab"><i class="icon-book"></i> {l s='Documentation' mod='[module]'}</a>
				<a href="#congif" class="list-group-item" data-toggle="tab"><i class="icon-indent"></i> {l s='Configuration' mod='[module]'}</a>
				<a href="#contacts" class="contacts list-group-item" data-toggle="tab"><i class="icon-envelope"></i> {l s='Contact' mod='[module]'}</a>
			</div>
		</div>
		<!-- Tab panes -->
		<div class="tab-content col-lg-10">

			<div class="tab-pane active panel" id="documentation">
				{include file="./tabs/documentation.tpl"}
			</div>

			<div class="tab-pane panel" id="congif">
				{include file="./tabs/config.tpl"}
			</div>

			{include file="./tabs/contact.tpl"}
		</div>
	</div>
{if $ps_version == 0}
	<!-- Manage translations -->
	{include file="./translations.tpl"}
</div>
{/if}