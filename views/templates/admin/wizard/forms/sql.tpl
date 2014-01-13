<div class="form-group">
	<label for="form-field-4" class="col-sm-2 control-label">
		Module SQL Install/Uninstall
	</label>
	<div class="col-sm-9">
		<div class="btn-group toggle-select" data-toggle-name="need_sql_install" data-toggle="buttons-radio" >
		 	 <button type="button" value="1" class="btn" data-toggle="button">Yes</button>
		 	 <button type="button" value="0" class="btn active" data-toggle="button">No</button>
		</div>
		<input type="hidden" name="need_sql_install" value="0" />

		<div class="switch_display hide">
			<div class="clearfix form-group"></div>
			<textarea placeholder="install SQL" id="form-field-8" name="sql_install" class="form-control textarea-animated"></textarea>
			<span class="help-block"><i class="fa fa-info-circle"></i> Use PREFIX & ENGINE_DEFAULT</span>
			<div class="clearfix form-group"></div>
			<textarea placeholder="uninstall SQL" id="form-field-9" name="sql_uninstall" class="form-control textarea-animated"></textarea>
			<span class="help-block"><i class="fa fa-info-circle"></i> Use PREFIX & ENGINE_DEFAULT</span>
		</div>
	</div>
</div>