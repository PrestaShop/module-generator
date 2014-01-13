[license]

<div class="clearfix"></div>
<div class="panel">
	<h3><i class="icon-cogs"></i> Configuration</h3>
	<select id="form-field-2" name="select_translation" class="selectpicker show-menu-arrow" title-icon="icon-flag" title="{l s='Manage translations' mod='[module]'}">
		{foreach $lang_select as $lang}
			<option href="{$module_trad|escape:'htmlall':'UTF-8'}{$lang@key}&#35;{$module_name}" {if !empty($lang.subtitle)}data-subtext="{$lang.subtitle}"{/if}>{$lang.title}</a></option>
		{/foreach}
	</select>
</div>