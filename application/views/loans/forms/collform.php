
	<div id="REMForm" <?php echo $REM;?> >
		<h4>Real Estate Information</h4>
		<div class="row form-group">
			<div class="col-md-3">
				<label>Type of Property</label>
				<select name="REM[typeOfproperty]" class="form-control input-sm">
					<option>Residential Vacant Lot</option>
					<option>Residential House and Lot</option>
					<option>Commercial Lot</option>
					<option>Commercial Building and Lot</option>
					<option>Agricultural/Industrial Lot</option>					
				</select>
			</div>
			<div class="col-md-3">
				<label>Title No.</label>
                <input name="REM[title]" class="form-control input-sm" placeholder="TCT no.">
			</div>
            <div class="col-md-3">
				<label>Lot No.</label>
                <input name="REM[lot]" class="form-control input-sm" placeholder="Lot no.">
			</div>
            <div class="col-md-3">
				<label>Area.</label>
                <div class="input-group">					
               		<input name="REM[area]" class="form-control input-sm" placeholder="Area">
                    <span class="input-group-addon">sqm</span>
                </div>
			</div>
		</div>
        <div class="row form-group">
        	<div class="col-md-3">
            	<label>Address</label>
                <input type="text" name="remaddress">
            </div>
        </div>
	</div>
	
	<div id="CMForm" <?php echo $CM;?>>
		<h4>Chattel Information</h4>
		<div class="row form-group">
			<div class="col-md-3">
				<label>Manufacturer</label>
				<input type="text" class="form-control input" name="CM[manufacturer]">					
			</div>
			<div class="col-md-3">
				<label>Model Name</label>
				<input type="text" name="CM[model]" class="form-control input">
			</div>
			<div class="col-md-3">
				<label>Year Model</label>
				<input type="number" name="CM[year]" class="form-control input">
			</div>
			<div class="col-md-3">
				<label>Year Registered:</label>
				<input type="number" class="form-control input" name="CM[year_registered]">				
				</select>
			</div>
		</div>
        <div class="row form-group">
        	<div class="col-md-3">
            	<label>Type of the Body</label>
                <input type="text" name="CM[bodytype]" class="form-control input-sm">
            </div>
            <div class="col-md-3">
            	<label>No. of Cylinders</label>
                <input type="number" name="CM[cylinders]" class="form-control input-sm">
            </div>
            <div class="col-md-3">
            	<label>Color</label>
                <input type="text" name="CM[color]" class="form-control input-sm">
            </div>
            <div class="col-md-3">
            	<label>Route</label>
                <input type="text" name="CM[route]" class="form-control input-sm">
            </div>
        </div>
        <div class="row form-group">
        	<div class="col-md-3">
            	<label>File No.</label>
                <input type="text" name="CM[fileno]" class="form-control input-sm">
            </div>
            <div class="col-md-3">
            	<label>Plate No.</label>
                <input type="text" name="CM[plateno]" class="form-control input-sm">
            </div>
            <div class="col-md-3">
            	<label>Engine No.</label>
                <input type="text" name="CM[engine]" class="form-control input-sm">
            </div>
            <div class="col-md-3">
            	<label>Chassis No.</label>
                <input type="text" name="CM[chassis]" class="form-control input-sm">
            </div>
        </div>
	</div>