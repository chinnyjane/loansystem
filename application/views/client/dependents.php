<h4>DEPENDENTS </h4>
<hr/>

<table class="table-condensed table-no-bordered well" id="dependents" width="100%">
	<thead>
	<tr>
		<th>First Name</th>
		<th>Middle Name</th>
		<th>Last Name</th>
		<th>Date of Birth</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><input type="text" class="form-control input-sm" placeholder="First Name" name="depfname[]"  ></td>
		<td><input type="text" class="form-control input-sm" placeholder="Middle Name" name="depmname[]]" ></td>
		<td class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Last Name" name="deplname[]" ></td>
		<td class="col-sm-2"><input type="date" class="form-control input-sm" placeholder="mm/dd/yyyy" name="depbday[]"></td>
	</tr>
	<tbody>
	<tfoot>
		<tr><td>
		<button id="adddep" type="button" class="btn btn-xs btn-warning" ><i class="fa fa-plus"></i> Add Dependent</button></td>
		</tr>
	</tfoot>
</table>


<div class="clear"></div>

<h4>OUTSTANDING OBLIGATIONS</h4>
<hr/>
<table class="table-condensed table-no-bordered well" id="credit" width="100%">
	<thead>
	<tr><th>Name of Creditor</th>
		<th>Address</th>
		<th>Amount</th>
		<th>Remarks</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><input type="text" class="form-control input-sm" placeholder="Name of Creditor" name="creditor[]" id="spcontact" ></td>
		<td><input type="text" class="form-control input-sm" placeholder="Address" name="creditadd[]" id="spcontact" ></td>
		<td><input type="text" class="form-control input-sm" placeholder="Amount" name="creditamount[]" id="spcontact" ></td>
		<td><input type="text" class="form-control input-sm" placeholder="Remarks" name="remarks[]" id="spcontact" ></td>
	</tr>
	</tbody>
	<tfoot>
		<tr>
		<td><button id="addcreditor" type="button" class="btn btn-xs btn-warning" > <i class="fa fa-plus"></i> Add Creditor</button></td>
		</tr>
	</tfoot>
</table>

		


