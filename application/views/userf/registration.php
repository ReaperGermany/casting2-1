<?php $this->load->view('header')?>
<div class="top">
    <div class="content">
        <h1 id="login2"><a href="<?php echo base_url()?>"></a></h1>        
        <div class="clear"></div>
    </div>
</div>
<div class="wellcome">
	<div class="content">
    	<h2>Registration </h2>
    </div>
</div>        
<div class="registration">
<script type="text/javascript" src="<?php echo base_url() ?>skin/js/registration.js"></script>

<form id="registration_form" onsubmit="registre(); return false;">
    <div class="acaunt">
        <label for="login" id="login_l">Login:</label>
        <input id="login" class="text" name="login" type="text" />

        <label for="pass" id="pass_l">Password:</label>
        <input id="pass" class="text" name="password" type="password" />
        <label for="pass_comfirm" style="width:130px" id="pass_comfirm_l">Password confirm:</label>
        <input id="pass_comfirm" class="text" name="password_comfirm" type="password" />
        <div class="clear"></div>
    </div>

    <div class="company">
        <h3>Company information </h3>
        <label for="name_bus" id="name_bus_l">Name of bus.</label>
        <input id="name_bus" class="text" name="name_bus" type="text" />
        <div class="clear"></div>
        <label for="name_cor" id="name_cor_l">Corporate name</label>
        <input id="name_cor" class="text" name="name_cor" type="text" />
        <div class="clear"></div>
        <label for="aim" id="aim_l">AIM</label>
        <input id="aim" class="text" name="aim" type="text" />
        <label for="msn" id="msn_l">MSN</label>
        <input id="msn" class="text" name="msn" type="text" />
        <div class="clear"></div>
        <label for="skype" id="skype_l">Skype</label>
        <input id="skype" class="text" name="skype" type="text" />
        <label for="email" id="email_l">E-mail</label>
        <input id="email" class="text" name="email" type="text" />
       <div class="clear"></div>
        <label for="cor_id" id="cor_id_l">Corporate ID#</label>
        <input id="cor_id" class="text" name="cor_id" type="text" />
        <label for="fed_tax_id" id="fed_tax_id_l">Federal Tax ID#</label>
        <input id="fed_tax_id" class="text" name="fed_tax_id" type="text" />
       <div class="clear"></div>
        <label for="data_open" id="data_open_l">Date Business opened:</label>
        <input id="data_open" class="text" name="data_open" type="text" />
        <div class="clear"></div>
        <label for="state_reg" id="state_reg_l">State of registration:</label>
        <input id="state_reg" class="text" name="state_reg" type="text" />
         <div class="clear"></div>
            <div class="col2">
                <h4>Type Corparation </h4>
                <input id="type_cor" name="type_cor" type="hidden" />
                <input id="type_cor1" class="radio" name="type_cor_r" type="radio" value="LLC" onclick="document.getElementById('type_cor').value=this.value"/>
                <label for="type_cor1" id="type_cor1_l">LLC</label><div class="clear"></div>
                <input id="type_cor2" class="radio" name="type_cor_r" type="radio" value="Sole Proprietor" onclick="document.getElementById('type_cor').value=this.value"/>
                <label for="type_cor2" id="type_cor2_l">Sole Proprietor</label><div class="clear"></div>
                <input id="type_cor3" class="radio" name="type_cor_r" type="radio" value="Partnership" onclick="document.getElementById('type_cor').value=this.value"/>
                <label for="type_cor3" id="type_cor3_l">Partnership</label><div class="clear"></div>
                <input id="type_cor4" class="radio" name="type_cor_r" type="radio" value="Corporation" onclick="document.getElementById('type_cor').value=this.value"/>
                <label for="type_cor4" id="type_cor4_l">Corporation</label>
            </div>

            <div class="col2">
                <h4>Business Type:</h4>
                <input id="type_bus" name="type_bus" type="hidden" />
                <input id="type_bus1" class="radio" name="type_bus_r" type="radio" value="Wholesale" onclick="document.getElementById('type_bus').value=this.value"/>
                <label for="type_bus1" id="type_bus1_l">Wholesale</label><div class="clear"></div>
                <input id="type_bus2" class="radio" name="type_bus_r" type="radio" value="Retail" onclick="document.getElementById('type_bus').value=this.value"/>
                <label for="type_bus2" id="type_bus2_l">Retail</label><div class="clear"></div>
                <input id="type_bus3" class="radio" name="type_bus_r" type="radio" value="Other" onclick="document.getElementById('type_bus').value=this.value"/>
                <label for="type_bus3" id="type_bus3_l">Other</label><div class="clear"></div>
            </div>
			<div class="clear"></div>
            <div class="col2">
                <h4>Type Premises:</h4>
                <input id="type_premise" name="type_premise" type="hidden" />
                <input id="type_premise1" class="radio" name="type_premise_r" type="radio" value="Own Premises" onclick="document.getElementById('type_premise').value=this.value"/>
                <label for="type_premise1" id="type_premise1_l">Own Premises</label><div class="clear"></div>
                <input id="type_premise2" class="radio" name="type_premise_r" type="radio" value="Lease Premises" onclick="document.getElementById('type_premise').value=this.value"/>
                <label for="type_premise2" id="type_premise2_l">Lease Premises</label><div class="clear"></div>
            </div>

            <div class="col2">
                <h4>Do you have wire capability?</h4>
                <input id="wire_capab" name="wire_capab" type="hidden" />
                <input id="wire_capab1" class="radio" name="wire_capab_r" type="radio" value="Yes" onclick="document.getElementById('wire_capab').value=this.value"/>
                <label for="wire_capab1" id="wire_capab1_l">Yes</label><div class="clear"></div>
                <input id="wire_capab2" class="radio" name="wire_capab_r" type="radio" value="No" onclick="document.getElementById('wire_capab').value=this.value"/>
                <label for="wire_capab2" id="wire_capab2_l">No</label><div class="clear"></div>
            </div>
			<div class="clear"></div>
        <label for="credit_limit" style="width:200px" id="credit_limit_l">Est. Credit Limit Requested? $</label>
        <input id="credit_limit" class="text" name="credit_limit" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
        <div class="clear"></div>
            <div>
                <h4>Financial Statement Availability?</h4>
                <input id="financil_avab" name="financil_avab" type="hidden" />
                <input id="financil_avab1" class="radio" name="financil_avab_r" type="radio" value="Yes" onclick="document.getElementById('financil_avab').value=this.value"/>
                <label for="financil_avab1" id="financil_avab1_l">Yes</label><div class="clear"></div>
                <input id="financil_avab2" class="radio" name="financil_avab_r" type="radio" value="No" onclick="document.getElementById('financil_avab').value=this.value"/>
                <label for="financil_avab2" id="financil_avab2_l">No</label><div class="clear"></div>
            </div>
            <div class="clear"></div>
    </div>

    <div class="payment">
        <h3>METHOD OF PAYMENT</h3>
        <div>
                <input id="pay_cc" class="check" name="pay_cc" type="checkbox" value="true" />
                <label for="pay_cc" id="pay_cc_l">Company Check</label><div class="clear"></div>
                <input id="pay_trans" class="check" name="pay_trans" type="checkbox" value="true"/>
                <label for="pay_trans" id="pay_trans_l">Wire Transfer</label><div class="clear"></div>
                <input id="pay_cert" class="check" name="pay_cert" type="checkbox" value="true" />
                <label for="pay_cert" id="pay_cert_l">Certified Check</label><div class="clear"></div>
                <input id="pay_money" class="check" name="pay_money" type="checkbox" value="true"/>
                <label for="pay_money" id="pay_money_l">Money Order</label><div class="clear"></div>
        </div>

    </div>

    <div class="bank">
        <h3>BANK REFERENCE</h3>
        <label for="name" id="name_l">Bank Name:</label>
        <input id="name" class="text requred" name="name" type="text" />
        <div class="clear"></div>
        <label for="contact" id="contact_l">Contact</label>
        <input id="contact" class="text requred" name="contact" type="text" />
        <div class="clear"></div>
        <label for="bank_adress" id="bank_adress_l">Address</label>
        <input id="bank_adress" class="text requred" name="bank_adress" type="text" />
        <div class="clear"></div>
        <label for="bank_city" id="bank_city_l">City</label>
        <input id="bank_city" class="text requred" name="bank_city" type="text" />
        <label for="bank_state" class="mini" id="bank_state_l">State</label>
        <input id="bank_state" class="text requred" name="bank_state" type="text" />
        <label for="bank_zip"  class="mini" id="bank_zip_l">Zip</label>
        <input id="bank_zip" class="text requred" name="bank_zip" type="text" />
        <div class="clear"></div>
        <label for="bank_phone" id="bank_phone_l">Phone</label>
        <input id="bank_phone" class="text requred" name="bank_phone" type="text" onkeyup="this.value = this.value.replace (/\D/, '')"  />
        <label for="bank_fax" class="mini" id="bank_fax_l">Fax</label>
        <input id="bank_fax" class="text requred" name="bank_fax" type="text" onkeyup="this.value = this.value.replace (/\D/, '')"  />
        <div class="clear"></div>
        <label for="check_acct" id="check_acct_l">Checking Acct #</label>
        <input id="check_acct" class="text requred" name="check_acct" type="text" />
        <div class="clear"></div>
        <label for="line_credit" id="line_credit_l">Line of Credit/Loan</label>
        <input id="line_credit" class="text requred" name="line_credit" type="text" />
    </div>
	<div class="clear"></div>
    <div class="billing col2">
        <h3>BILLING</h3>
        <label for="billink_adress" id="billink_adress_l">Address</label>
        <input id="billink_adress" class="text" name="billink_adress" type="text" />
        <div class="clear"></div>
        <label for="billing_email" id="billing_email_l">E-mail</label>
        <input id="billing_email" class="text" name="billing_email" type="text" />
        <div class="clear"></div>
        <label for="billing_city" id="billing_city_l">City</label>
        <input id="billing_city" class="text" name="billing_city" type="text" />
        <label for="billing_state" class="mini" id="billing_state_l">State</label>
        <input id="billing_state" class="text" name="billing_state" type="text" />
        <div class="clear"></div>
        <label for="billing_zip" id="billing_zip_l">Zip</label>
        <input id="billing_zip" class="text" name="billing_zip" type="text" />
        <div class="clear"></div>
        <label for="billing_phone" id="billing_phone_l">Phone</label>
        <input id="billing_phone" class="text" name="billing_phone" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
        <label for="billing_fax" class="mini" id="billing_fax_l">Fax</label>
        <input id="billing_fax" class="text" name="billing_fax" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
    </div>

    <div class="shipping col2">
        <h3>SHIPPING</h3>
        <label for="shipping_adress" id="shipping_adress_l">Address</label>
        <input id="shipping_adress" class="text" name="shipping_adress" type="text" />
        <div class="clear"></div>
        <label for="shipping_email" id="shipping_email_l">E-mail</label>
        <input id="shipping_email" class="text" name="shipping_email" type="text" />
        <div class="clear"></div>
        <label for="shipping_city" id="shipping_city_l">City</label>
        <input id="shipping_city" class="text" name="shipping_city" type="text" />
        <label for="shipping_state" class="mini" id="shipping_state_l">State</label>
        <input id="shipping_state" class="text" name="shipping_state" type="text" />
        <div class="clear"></div>
        <label for="shipping_zip" id="shipping_zip_l">Zip</label>
        <input id="shipping_zip" class="text" name="shipping_zip" type="text" />
        <div class="clear"></div>
        <label for="shipping_phone" id="shipping_phone_l">Phone</label>
        <input id="shipping_phone" class="text" name="shipping_phone" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
        <label for="shipping_fax" class="mini" id="shipping_fax_l">Fax</label>
        <input id="shipping_fax" class="text" name="shipping_fax" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
    </div>
	<div class="clear"></div>
    <div class="trade col2">
        <h3>TRADE REFERENCES</h3>
        <label for="supplier1" id="supplier1_l">Supplier</label>
        <input id="supplier1" class="text requred" name="supplier1" type="text" />
        <div class="clear"></div>
        <label for="accaunt1" id="accaunt1_l">Account No</label>
        <input id="accaunt1" class="text requred" name="accaunt1" type="text" />
        <div class="clear"></div>
        <label for="contact_name1" id="contact_name1_l">Contact Name</label>
        <input id="contact_name1" class="text requred" name="contact_name1" type="text" />
        <div class="clear"></div>
        <label for="trade_adress1" id="strade_adress1_l">Address</label>
        <input id="trade_adress1" class="text requred" name="trade_adress1" type="text" />
        <div class="clear"></div>
        <label for="trade_city1" id="trade_city1_l">City</label>
        <input id="trade_city1" class="text requred" name="trade_city1" type="text" />
        <label for="trade_state1" class="mini" id="trade_state1_l">State</label>
        <input id="trade_state1" class="text requred" name="trade_state1" type="text" />
        <div class="clear"></div>
        <label for="trade_zip1" id="trade_zip1_l">Zip</label>
        <input id="trade_zip1" class="text requred" name="trade_zip1" type="text" />
        <div class="clear"></div>
        <label for="trade_phone1" id="trade_phone1_l">Phone</label>
        <input id="trade_phone1" class="text requred" name="trade_phone1" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
        <label for="trade_fax1" class="mini" id="trade_fax1_l">Fax</label>
        <input id="trade_fax1" class="text requred" name="trade_fax1" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
    </div>
	<div class="trade col2">
        <h3>TRADE REFERENCES</h3>
        <label for="supplier2" id="supplier2_l">Supplier</label>
        <input id="supplier2" class="text requred" name="supplier2" type="text" />
        <div class="clear"></div>
        <label for="accaunt2" id="accaunt2_l">Account No</label>
        <input id="accaunt2" class="text requred" name="accaunt2" type="text" />
        <div class="clear"></div>
        <label for="contact_name2" id="contact_name2_l">Contact Name</label>
        <input id="contact_name2" class="text requred" name="contact_name2" type="text" />
        <div class="clear"></div>
        <label for="trade_adress2" id="strade_adress2_l">Address</label>
        <input id="trade_adress2" class="text requred" name="trade_adress2" type="text" />
        <div class="clear"></div>
        <label for="trade_city2" id="trade_city2_l">City</label>
        <input id="trade_city2" class="text requred" name="trade_city2" type="text" />
        <label for="trade_state2" class="mini" id="trade_state2_l">State</label>
        <input id="trade_state2" class="text requred" name="trade_state2" type="text" />
        <div class="clear"></div>
        <label for="trade_zip2" id="trade_zip2_l">Zip</label>
        <input id="trade_zip2" class="text requred" name="trade_zip2" type="text" />
        <div class="clear"></div>
        <label for="trade_phone2" id="trade_phone2_l">Phone</label>
        <input id="trade_phone2" class="text requred" name="trade_phone2" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
        <label for="trade_fax2" class="mini" id="trade_fax2_l">Fax</label>
        <input id="trade_fax2" class="text requred" name="trade_fax2" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
    </div>
    <div class="clear"></div>
	<div class="trade col2">
        <h3>TRADE REFERENCES</h3>
        <label for="supplier3" id="supplier3_l">Supplier</label>
        <input id="supplier3" class="text requred" name="supplier3" type="text" />
        <div class="clear"></div>
        <label for="accaunt3" id="accaunt3_l">Account No</label>
        <input id="accaunt3" class="text requred" name="accaunt3" type="text" />
        <div class="clear"></div>
        <label for="contact_name3" id="contact_name3_l">Contact Name</label>
        <input id="contact_name3" class="text requred" name="contact_name3" type="text" />
        <div class="clear"></div>
        <label for="trade_adress3" id="strade_adress3_l">Address</label>
        <input id="trade_adress3" class="text requred" name="trade_adress3" type="text" />
        <div class="clear"></div>
        <label for="trade_city3" id="trade_city3_l">City</label>
        <input id="trade_city3" class="text requred" name="trade_city3" type="text" />
        <label for="trade_state3" class="mini" id="trade_state3_l">State</label>
        <input id="trade_state3" class="text requred" name="trade_state3" type="text" />
        <div class="clear"></div>
        <label for="trade_zip3" id="trade_zip3_l">Zip</label>
        <input id="trade_zip3" class="text requred" name="trade_zip3" type="text" />
       <div class="clear"></div>
        <label for="trade_phone3"  id="trade_phone3_l">Phone</label>
        <input id="trade_phone3" class="text requred" name="trade_phone3" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
        <label for="trade_fax3" class="mini" id="trade_fax3_l">Fax</label>
        <input id="trade_fax3" class="text requred" name="trade_fax3" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
    </div>
	<div class="trade col2">
        <h3>TRADE REFERENCES</h3>
        <label for="supplier4" id="supplier4_l">Supplier</label>
        <input id="supplier4" class="text requred" name="supplier4" type="text" />
        <div class="clear"></div>
        <label for="accaunt4" id="accaunt4_l">Account No</label>
        <input id="accaunt4" class="text requred" name="accaunt4" type="text" />
        <div class="clear"></div>
        <label for="contact_name4" id="contact_name4_l">Contact Name</label>
        <input id="contact_name4" class="text requred" name="contact_name4" type="text" />
        <div class="clear"></div>
        <label for="trade_adress4" id="strade_adress4_l">Address</label>
        <input id="trade_adress4" class="text requred" name="trade_adress4" type="text" />
        <div class="clear"></div>
        <label for="trade_city4" id="trade_city4_l">City</label>
        <input id="trade_city4" class="text requred" name="trade_city4" type="text" />
        <label for="trade_state4" class="mini" id="trade_state4_l">State</label>
        <input id="trade_state4" class="text requred" name="trade_state4" type="text" />
        <div class="clear"></div>
        <label for="trade_zip4" id="trade_zip4_l">Zip</label>
        <input id="trade_zip4" class="text requred" name="trade_zip4" type="text" />
        <div class="clear"></div>
        <label for="trade_phone4" id="trade_phone4_l">Phone</label>
        <input id="trade_phone4" class="text requred" name="trade_phone4" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
        <label for="trade_fax4" class="mini" id="trade_fax4_l">Fax</label>
        <input id="trade_fax4" class="text requred" name="trade_fax4" type="text" onkeyup="this.value = this.value.replace (/\D/, '')" />
    </div>
    <div class="clear"></div>
    <input id="but1" name="but1" class="btn" type="submit" value="Register"/>
</form>
</div>
<?php $this->load->view('footer')?>
