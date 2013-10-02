<?php $this->load->view('admin/header')?>
<div id="contacts">
    <script>
        var companies = <?php echo $companies;?>;
		var emails = <?php echo $emails;?>;
        var attrs = <?php echo json_encode($attrs)?>;
		var type = '<?php echo $types;?>';
    </script>
    <fieldset>
		<label for="company">Offeror:</label>
        <input id="comp_email" type="text" class="text" name="comp_email" class="ui-autocomplete-input"/>
       <div style="display:none;"> 
		<input type="button" id="add_company" class="btn" value="Add Offeror"/>
		
		<label for="company">Company</label>
        <input id="company" type="text" class="text" name="company" class="ui-autocomplete-input"/>
        <!--input type="button" id="add_company" value="Add Company"/-->

        <div id="staff_list">
            <label for="staff">Staff</label>
            <input id="staff" type="text" class="text" name="staff"/>

            <input type="button" id="edit_staff" class="btn" value="Edit"/>
        </div>
		</div>
    </fieldset>

    <div id="company_form">
        <form>
            <fieldset>
                <label for="company_name">Company Name</label>
                <input id="company_name" type="text" name="company_name" onkeyup="this.value = this.value.replace (/[^0-9\a-z\A-Z\-\_\ ]/gi, '')"/>
            </fieldset>
        </form>
    </div>

    <div id="staff_form">
        <form>
            <fieldset>
                <input id="company_id" type="hidden" name="company_id" value="" />
                <table>
                    <tr>
                        <td>Name</td>
                        <td><input id="appeal" type="text" name="appeal" onkeyup="this.value = this.value.replace (/[^0-9\a-z\A-Z\-\_\ ]/gi, '')"/></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><input id="email" type="text" name="email" /></td>
                    </tr>
                    <tr>
                        <td>Skype</td>
                        <td><input id="skype" type="text" name="skype" /></td>
                    </tr>
                    <tr>
                        <td>MSN</td>
                        <td><input id="msn" type="text" name="msn" /></td>
                    </tr>
                    <tr>
                        <td>Yahoo</td>
                        <td><input id="jahoo" type="text" name="jahoo" /></td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td><input id="phone" type="text" name="phone" /></td>
                    </tr>
                    <tr>
                        <td>Cell</td>
                        <td><input id="cell" type="text" name="cell" /></td>
                    </tr>
                    <tr>
                        <td>BBM</td>
                        <td><input id="bbm" type="text" name="bbm" /></td>
                    </tr>
                    <tr>
                        <td>AIM</td>
                        <td><input id="aim" type="text" name="aim" /></td>
                    </tr>
                    <tr>
                        <td>ICQ</td>
                        <td><input id="icq" type="text" name="icq" onkeyup="this.value = this.value.replace (/[^0-9]/gi, '')"/></td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>

</div>

<div id="offer_data">
    <!--form action="<?php echo site_url("admin/offer/savePost"); ?>" method="post" id="offer_form"-->
        <input type="hidden" name="type" value="<?php echo $type?>" class="hidden"/>
        <input id="staff_id" type="hidden" name="staff_id" value="" class="hidden"/>
        <table id="add_offers_table">
            <thead>
                <tr>
                    <th>Manufacturer</th>
                    <th>Model</th>
                    <!--th>Color</th>
                    <th>Spec</th-->
                    <!--th>Qty</th-->
				<!--	<?php if ($types!='offer') :?>
					  <th>Qty factory</th>
                    <th>Price</th>
					  <th>Brand</th>
                    <th>Location</th>
					<?php endif;?> -->
                    <th>Currency</th>
                    <th>Offer Price</th>
                    <th>Notes</th>
                    <!--th>Actions</th-->
                </tr>
            </thead>
            <tbody>
                <?php for($i=0; $i<5; $i++):?>
                <tr class="<?php echo $i%2?"odd":"even"?>">
                    <td>
                        <input id="manufacturer_id" type="hidden" name="row[<?php echo $i?>][attribute][manufacturer][id]" class="required hidden" rel="Manufacturer"/>
                        <input id="manufacturer" type="text" name="row[<?php echo $i?>][attribute][manufacturer][value]" class="attribute" />
                    </td>
                    <td>
                        <input id="model_id" type="hidden" name="row[<?php echo $i?>][attribute][model][id]" class="required" rel="Model"/>
                        <input id="model" type="text" name="row[<?php echo $i?>][attribute][model][value]" class="attribute" />
                    </td>
                    <!--td>
                        <input id="color_id" type="hidden" name="row[<?php echo $i?>][attribute][color][id]"  rel="Color"/>
                        <input id="color" type="text" name="row[<?php echo $i?>][attribute][color][value]" class="attribute"/>
                    </td>
                    <td>
                        <input id="spec_id" type="hidden" name="row[<?php echo $i?>][attribute][spec][id]"  rel="Spec"/>
                        <input id="spec" type="text" name="row[<?php echo $i?>][attribute][spec][value]" class="attribute"/>
                    </td-->
                    <!--td>
                        <input id="qty" type="text" name="row[<?php echo $i?>][qty]" onkeyup="this.value = this.value.replace (/\D/, '')"/>
                    </td-->
				<!--	<?php if ($types!='offer') :?>
					 <td>
                        <input id="qty_factor" type="text" name="row[<?php echo $i?>][qty_factor]" onkeyup="onlyDigits(this)" />
                    </td>
                    <td>
                        <input id="price" type="text" name="row[<?php echo $i?>][price]" class="" onkeyup="onlyDigits(this)"/>
                    </td>
					  <td>
                        <input id="brand" type="text" name="row[<?php echo $i?>][brand]" class=""/>
                    </td>
					  <td>
                        <input id="location" type="text" name="row[<?php echo $i?>][location]" class=""/>
                    </td>
					<?php endif;?> -->
                    <td>
                        <input id="currency_id" type="hidden" name="row[<?php echo $i?>][attribute][currency][id]" class="required" rel="Currency"/>
                        <input id="currency" type="text" name="row[<?php echo $i?>][attribute][currency][value]" class="attribute"/>
                    </td>
                    <td>
                        <input id="price" type="text" name="row[<?php echo $i?>][price]" onkeyup="onlyDigits(this)" class="required" rel="Price"/>
                    </td>
                    <td>
                        <textarea id="notes" name="row[<?php echo $i?>][notes]" onkeyup="this.value = this.value.replace (/[^0-9\a-z\A-Z\-\_]/gi, '')"></textarea>
                    </td>
                    <!--td>
                        <button id="clear" class="button">Clear</button>
                        <button id="copy" class="button">Copy</button>
                    </td-->
                </tr>
                <?php endfor;?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" align="center">
                        <!--input type="button" class="button" id="add_offer" name="submit" value="Add" /-->
                        <input type="button" class="button" id="apply_offer" name="add_offer" value="Add+" />
                        <input type="button" class="button" id="clear-table" name="clear_offer" value="Clear" />
                    </td>
                </tr>
            </tfoot>
        </table>
        <script>
            var offers = $("#add_offers_table").offers();
        </script>
    <!--/form-->
	
	<script>
    function onlyDigits(el) {
      el.value = el.value.replace(/[^\d\.]/g, "");
      if(el.value.match(/\./g).length > 1) {
        el.value = el.value.substr(0, el.value.lastIndexOf("."));
      }
    }
  </script>
</div>

<div id="attribute_edit_container">
    <div id="messages"></div>
		<table>
			<tr>
			<th>Manufacturer</th>
			<th>Model</th>
			<!--th>Color</th>
			<th>Spec</th-->
			</tr>
			<tr>
			<td>
				<input type="hidden" name="code" id="attr_code1" value="manufacturer"/>		
				<input type="text" class="text" name="value" id="attr_value1" onkeyup="this.value = this.value.replace (/[^0-9\a-z\A-Z\-\_\ ]/gi, '')"/>
				<input type="button" class="btn" value="Add" onclick=add_attr('1') />
			</td>
			<td>
				<input type="hidden" name="code" id="attr_code2" value="model"/>		
				<input type="text" class="text" name="value" id="attr_value2" onkeyup="this.value = this.value.replace (/[^0-9\a-z\A-Z\-\_\ ]/gi, '')"/>
				<input type="button" class="btn" value="Add" onclick=add_attr('2') />
			</td>
			<!--td>
				<input type="hidden" name="code" id="attr_code3" value="color"/>		
				<input type="text" class="text" name="value" id="attr_value3" onkeyup="this.value = this.value.replace (/[^0-9\a-z\A-Z\-\_\ ]/gi, '')"/>
				<input type="button" value="Add" class="btn" onclick=add_attr('3') />
			</td>
			<td>
				<input type="hidden" name="code" id="attr_code4" value="spec"/>		
				<input type="text" class="text" name="value"  id="attr_value4" onkeyup="this.value = this.value.replace (/[^0-9\a-z\A-Z\-\_\ ]/gi, '')"/>
				<input type="button" value="Add"class="btn" onclick=add_attr('4') />
			</td-->
			</tr>
		</table>			
			
</div>

<script type="text/javascript" src="<?php echo base_url() ?>skin/js/add_offers.js"></script>
<?php $this->load->view('admin/footer')?>
