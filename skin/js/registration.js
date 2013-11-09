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
	if (!valid()) return false;

	var date_registr = [];	
	//date_registr['bank_name'] = $('#name').val();
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