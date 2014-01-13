<div class="form-group">
	<label for="form-field-4" class="col-sm-2 control-label">
		Module AdminController
	</label>
	<div class="col-sm-9">
		<div class="btn-group toggle-select" data-toggle-name="back_controller" data-toggle="buttons-radio">
		 	 <button type="button" value="1" class="btn" data-toggle="button">Yes</button>
		 	 <button type="button" value="0" class="btn active" data-toggle="button">No</button>
		</div>
		<input type="hidden" name="back_controller" value="0" />
		<div class="switch_display hide">
			<div class="clearfix form-group"></div>
			<select name="tabs_controller_back" class="selectpicker show-menu-arrow" multiple data-live-search="true">
				{foreach $tab_select as $tabs => $tab}
				<optgroup label="{$tabs}">
					{foreach $tab as $key => $value}
					<option value="{$value}">{$value}</option>
					{/foreach}
				</optgroup>
				{/foreach}
			</select>
		</div>
	</div>
</div>