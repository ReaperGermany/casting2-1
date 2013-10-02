$(function(){
    $('#comp_email').autocomplete({
        minLength: 0,
        source: emails?emails:[],
        search: function(){
            $('#company_id').val('');
            $('#staff_list').css('display','none');
            $('#add_company').attr('disabled', false);
            clear_staff_data();
        },
        select: function(event, ui) {
            if(ui.item) {
				$('#company_id').val(ui.item.id);
				$('#company').val(ui.item.name);
                $('#add_company').attr('disabled', true);
				//$('#staff_id').val(ui.item.col.id);
				//$('#staff').val(ui.item.col.appeal);
				$.post(base_url+'admin/ajax/getStaff', {pk_id: ui.item.staff_id}, create_staf_autocomplete, 'json');
		     
            }
        }
    });
	// Автодополнение для поля компаний
    $('#company').autocomplete({
        minLength: 0,
        source: companies?companies:[],
        search: function(){
            $('#company_id').val('');
            $('#staff_list').css('display','none');
            $('#add_company').attr('disabled', false);
            clear_staff_data();
        },
        select: function(event, ui) {
            if(ui.item) {
				$('#comp_email').val('');
                $('#company_id').val(ui.item.id);
                $('#add_company').attr('disabled', true);
                $.post(base_url+'admin/ajax/getStaffList', {company: ui.item.id}, create_staf_autocomplete, 'json')
            }
        }
    });

    $('#add_company').click(function(){
        $( "#company_form" ).dialog( "open" );
    });

    $( "#company_form" ).dialog({
			autoOpen: false,
			height: 350,
			width: 350,
			modal: true,
			buttons: {
				"Save": function() {
                                        $.post(base_url+'admin/ajax/addCompany',{
                                            name: $('#company_name').val()
                                        },function(resp){
                                            $('#company_id').val(resp.id);
                                            $('#company').val(resp.name);
                                            $( this ).dialog( "close" );
                                            $('#staff_list').css('display','block');
                                            $('#staff_form').dialog( "open" );
                                        },'json');
                                        $( this ).dialog( "close" );
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
			}
	});


    $('#company').focus(function(){
        $('#add_company').attr('disabled', false);
    });

    $( "#staff_form" ).dialog({
			autoOpen: false,
			height: 530,
			width: 310,
			modal: true,
			buttons: {
				"Save": function() {
                                        if ($('#email').val()!='' && !validmail('email')) {alert('Wrong email.');}
										else
										{
										$.post(base_url+'admin/ajax/saveStaff',{
                                            appeal: $('#appeal').val(),
                                            email: $('#email').val(),
                                            skype: $('#skype').val(),
                                            msn: $('#msn').val(),
                                            jahoo: $('#jahoo').val(),
                                            phone: $('#phone').val(),
                                            cell: $('#cell').val(),
                                            bbm: $('#bbm').val(),
                                            aim: $('#aim').val(),
                                            icq: $('#icq').val(),
                                            staff_id: $('#staff_id').val(),
                                            company_id: $('#company_id').val()
                                        },function(resp){
                                            if (resp.id){
                                                $('#staff_id').val(resp.id);
                                            }
                                            $('#staff').val($('#appeal').val());
                                            $('#manufacturer').focus();
                                        },'json');
                                        $( this ).dialog( "close" );
										}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
			}
	});

    $( "#edit_staff" ).click(function() {
            $('#appeal').val($('#staff').val());
            $( "#staff_form" ).dialog( "open" );
    });

    $('#apply_offer').click(function(){
        if (!validate()) return;
		if (!$('#staff_id').val())
		{
			$.post(base_url+'admin/ajax/Offeror', {offeror:$('#comp_email').val()}, function(resp){
            if (resp.status=='OK') 
			{$('#staff_id').val(resp.staff_id);
			$.post(base_url+'admin/ajax/saveOffer', prepare_data(), function(resp){
            if (resp.status=='OK' && resp.duble==0) {
                clear_offer_form();
				if (type=='offer') {alert('Offer added successfully');
				location.href = base_url+'admin/offers/add/offer';}
				else {alert('Request added successfully');}
            }
			if (resp.duble>0) {alert(resp.message);}
        },'json');
			
			}
			else {alert('Error Add New Offeror'); return false;}
            
        },'json');
		return;
		}
        $.post(base_url+'admin/ajax/saveOffer', prepare_data(), function(resp){
            if (resp.status=='OK' && resp.duble==0) {
                clear_offer_form();
				if (type=='offer') {alert('Offer added successfully');}
				else {alert('Request added successfully');}
            }
			if (resp.duble>0) {alert(resp.message);}
        },'json');
    });

    $('#add_offer').click(function(){
		if (!validate()) return;
        $.post(base_url+'admin/ajax/saveOffer', prepare_data(), function(resp){
            if (resp.status=='OK') {
                location.href = base_url+'admin/offers';
            }
        },'json');
    });

$('input','#staff_form').keypress(
    function(e)
    {
        var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);
        if (key == 13) {
            $('#staff_form form').submit();
        }
        return key;
    });

$('input','#add_offers_table').keypress(
    function(e)
    {
        //var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);
        var key = e.keyCode;
        //console.log(e);
        if ((key == 10 || key == 13) && e.ctrlKey) {
            $('#apply_offer').click();
        }
        return key;
    });

$('textarea','#add_offers_table').keypress(
    function(e)
    {
        //var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);
        var key = e.keyCode;
        //console.log(e);
        if ((key == 10 || key == 13) && e.ctrlKey) {
            $('#apply_offer').click();
        }
        return key;
    });

$('#company').focus();
});
////////////////////////////

function add_attr(postfix)
{
	if ($("#attr_code"+postfix).val()=='') {alert('Select the attribute');}
	else
	{
	    $.post(base_url+'admin/attributes/ajaxAttributeFastSave', {
	        id:'',
	        value: $("#attr_value"+postfix).val(),
	        code: $("#attr_code"+postfix).val()
	        }, parceResp,'json');
		$("#attr_value"+postfix).val('');
	}
}

function parceResp(resp)
{
    if (resp.status=='OK') {
        $("#attribute_edit_container").dialog( "close" );
		attrs = resp.attr;
    }
    if (resp.status=='ERROR') {
        alert(resp.message);
    }
}

function validmail(mail)
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