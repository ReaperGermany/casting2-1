 function validate(mail)
   {	
		var email = document.getElementById(mail);
       var reg= new RegExp("[0-9a-z_]+@[0-9a-z_^.]+\\.[a-z]{2,3}", 'i')
       if (!reg.test(email.value)) {
      email.style.borderColor ="red";
      email.focus();
      return false;
       }
       else 
	   {
	   email.style.borderColor ="green";
	   return true;
	   }
   }
 
function valid()
   {	
    var flag = true;
	$('.requred').each(function(){
		if ($.trim($(this).val())=='') {
			$(this).css('border-color', 'red');
			$(this).focus();
			flag = false;
		}
		else 
		{
			$(this).css('border-color', 'green');
		}
	});	
	return flag;
   }

function registre()
{
	if ($('#pass_comfirm').val()!=$('#pass').val()) 
		{$('#pass').focus(); alert("You password diference."); 
		return false;}
	if ($.trim($('#login').val())=="") {$('#login').focus(); alert("Login can not be empty."+$('#login').val()); 
		return false;}
		if ($('#pass_comfirm').val()=='') {$('#pass').focus(); alert("Password can not be empty."); 
		return false;}
		
	if (!validate('email')) return false;
	if (!validate('billing_email')) return false;
	if (!validate('shipping_email')) return false;
	if (!valid()) return false;

	var date_registr = [];
	date_registr['login'] = $('#login').val();
	date_registr['pass'] = $('#pass').val();
	date_registr['name_bus'] = $('#name_bus').val();
	date_registr['name_cor'] = $('#name_cor').val();
	date_registr['aim'] = $('#aim').val();
	date_registr['msn'] = $('#msn').val();
	date_registr['skype'] = $('#skype').val();
	date_registr['email'] = $('#email').val();
	date_registr['cor_id'] = $('#cor_id').val();
	date_registr['fed_tex_id'] = $('#fed_tex_id').val();
	date_registr['data_open'] = $('#data_open').val();
	date_registr['state_reg'] = $('#state_reg').val();
	date_registr['type_cor'] = $('#type_cor').val();
	date_registr['type_bus'] = $('#type_bus').val();
	date_registr['type_premise'] = $('#type_premise').val();
	if ($('#wire_capab').val()=="Yes") date_registr['wire_capab'] = true
	else date_registr['wire_capab'] = false;

	date_registr['credit_limit'] = $('#credit_limit').val();
	
	if ($('#financil_avab').val()=="Yes") date_registr['financil_avab'] = true
	else date_registr['financil_avab'] = false;
	
	if ($('#pay_cc').attr('checked')) date_registr['pay_cc'] = true
	else date_registr['pay_cc'] = false;
	
	if ($('#pay_tras').attr('checked')) date_registr['pay_tras'] = true
	else date_registr['pay_tras'] = false;
	
	if ($('#pay_cert').attr('checked')) date_registr['pay_cert'] = true
	else date_registr['pay_cert'] = false;
	
	if ($('#pay_money').attr('checked')) date_registr['pay_money'] = true
	else date_registr['pay_money'] = false;
	
	// bank data
	date_registr['bank_name'] = $('#name').val();
	date_registr['bank_contact'] = $('#contact').val();
	date_registr['bank_adress'] = $('#bank_adress').val();
	date_registr['bank_city'] = $('#bank_city').val();
	date_registr['bank_state'] = $('#bank_state').val();
	date_registr['bank_zip'] = $('#bank_zip').val();
	date_registr['bank_phone'] = $('#bank_phone').val();
	date_registr['bank_fax'] = $('#bank_fax').val();
	date_registr['bank_check_acct'] = $('#check_acct').val();
	date_registr['bank_line_credit'] = $('#line_credit').val();
	
	// billing
	date_registr['billing_adress'] = $('#billink_adress').val();
	date_registr['billing_email'] = $('#billing_email').val();
	date_registr['billing_city'] = $('#billing_city').val();
	date_registr['billing_state'] = $('#billing_state').val();
	date_registr['billing_zip'] = $('#billing_zip').val();
	date_registr['billing_phone'] = $('#billing_phone').val();
	date_registr['billing_fax'] = $('#billing_fax').val();

	// shipping
	date_registr['shipping_adress'] = $('#shipping_adress').val();
	date_registr['shipping_email'] = $('#shipping_email').val();
	date_registr['shipping_city'] = $('#shipping_city').val();
	date_registr['shipping_state'] = $('#shipping_state').val();
	date_registr['shipping_zip'] = $('#shipping_zip').val();
	date_registr['shipping_phone'] = $('#shipping_phone').val();
	date_registr['shipping_fax'] = $('#shipping_fax').val();
	
	// trade
	date_registr['trade_supplier'] = $('#supplier').val();
	date_registr['trade_accaunt'] = $('#accaunt').val();
	date_registr['trade_contact_name'] = $('#contact_name').val();
	date_registr['trade_adress'] = $('#trade_adress').val();
	date_registr['trade_city'] = $('#trade_city').val();
	date_registr['trade_state'] = $('#trade_state').val();
	date_registr['trade_zip'] = $('#trade_zip').val();
	date_registr['trade_phone'] = $('#trade_phone').val();
	date_registr['trade_fax'] = $('#trade_fax').val();
	
	date_registr['reg'] = 'true';

    date_registr = $("#registration_form").serialize();
	$.post(base_url+'user/registration', date_registr, parceResp, 'json');
	
}


function parceResp(resp)
{
	if (resp.status=='OK') {
        //$('div.atr2').replaceWith(resp.content);
		alert("Thank you for registering, your application will be reviewed shortly.");
		location.replace(base_url);
    }
    if (resp.status=='ERROR') {
        alert(resp.message);
    }
}