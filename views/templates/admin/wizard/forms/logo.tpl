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