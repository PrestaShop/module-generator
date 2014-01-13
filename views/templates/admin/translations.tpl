<div class="clearfix"></div>
<div class="panel">
	<h3><i class="icon-cogs"></i> Configuration</h3>
	<select id="form-field-2" name="select_translation" class="selectpicker show-menu-arrow" title-icon="icon-flag" title='Manage translations'>
		{foreach $lang_select as $tabs}
			<option href="{$module_trad|escape:'htmlall':'UTF-8'}{$tabs@key}&#35;{$module_name}" {if !empty($tabs.subtitle)}data-subtext="{$tabs.subtitle}"{/if}>{$tabs.title}</a></option>
		{/foreach}
	</select>
</div>