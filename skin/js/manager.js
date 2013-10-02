var blocker = false;
$(function(){
    var requires = {};
    prepareRows();  
});

function prepareRows()
{
    $('.item_id').each(function(){
        $(this).dblclick(function(){ blocker = true})
        var id = $(this).val();

        $(this).parents('tr').dblclick(function(){
            if (!blocker) {
                $.post(base_url+'admin/manager/ajaxLoadInfo',{id:id},showUserEditForm,'json');
            }
            blocker = false;
        });
    });
}

function showUserEditForm(resp)
{
    if (resp.status=='OK') {
        $('div.info').replaceWith(resp.content);
		$('div.info_block').css({'display' : 'block', 'left': '100px', 'z-index' : '100', 'top' : '50px'});
    }
    if (resp.status=='ERROR') {
        alert(resp.message);
    }
}

function cancel()
{
	$('#pass_change').css({'display' : 'none', 'left': '-2000px'})
}

function change_pass(id)
{
	$('#pass_change').css({'display' : 'block', 'left': '400px', 'z-index' : '100', 'top' : '300px'})
	$('#pk_id2').val(id);
}

function pass()
{
	if ($('#pk_id2').val()!="" && $('#pass2').val()==$('#pass_comfirm2').val() && $('#pass2').val()!='') {
	$.post(base_url+'admin/manager/ajaxChangePass', {id:$('#pk_id2').val(), pass:$('#pass2').val()}, parce, 'json');
	}
	else 
	{
		alert('Password can not be empty and different from Password confirm.');
	}
}

function approv(id)
{
	if (id!="") {
	$.post(base_url+'admin/manager/ajaxApprove', {id:id}, parce, 'json');
	}
}

function parce(resp)
{
    if (resp.status=='OK') {
        //alert('User approved.');
		$('div.info_block').css({'display' : 'none', 'left': '-2000px'});
		$('#pass_change').css({'display' : 'none', 'left': '-2000px'});
		if (resp.message!='') alert(resp.message);
		location.href = base_url+'admin/manager';
    }
    if (resp.status=='ERROR') {
        alert(resp.message);
    }
}

function decline(id)
{
	if (id!="") {
	$.post(base_url+'admin/manager/ajaxDecline', {id:id}, parceD, 'json');
	}
}

function parceD(resp)
{
    if (resp.status=='OK') {
        //alert('User declined.');
		$('div.info_block').css({'display' : 'none', 'left': '-2000px'});
		location.href = base_url+'admin/manager';
    }
    if (resp.status=='ERROR') {
        alert(resp.message);
    }
}

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

function editing()
{
	
	if (!validate('email')) return false;
	if (!validate('billing_email')) return false;
	if (!validate('shipping_email')) return false;
	date_registr = $("#registration_form").serialize();
	$.post(base_url+'admin/manager/ajaxEditing', date_registr, parceR, 'json');

}

function parceR(resp)
{
    if (resp.status=='OK') {
       // alert('Changes are saved.');
		//viewUser();
		$('div.info_block').css({'display' : 'none', 'left': '-2000px'});
		location.href=base_url+'admin/manager';
    }
    if (resp.status=='ERROR') {
        alert(resp.message);
    }
}

function filterOffers_user(el)
{
    var container = $(el).parents('thead');
    var post_data = {};
//    $('input', container).each(function(){
//        post_data[$(this).attr('name')] = $(this).val();
//    });
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });

    $.post(base_url+'admin/manager/filter',post_data,function(){location.href=base_url+'admin/manager'});
}

function resetFilter_user(el)
{
    var container = $(el).parents('th');
    var post_data = {};
//    $('input', container).each(function(){
//        post_data[$(this).attr('name')] = $(this).val();
//    });
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });
    $.post(base_url+'admin/manager/reset',post_data, function(){location.href=base_url+'admin/manager'});
}

function deleteUser(el)
{
    var parent = $(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    if (data.length) {
        $.post(base_url+'admin/ajax/deleteVisible',{
                ids:data,
                type: $('.item_type',parent).val()
            },function(resp){
            if (resp.status=="OK"){
                //$(parent).parents('.offers-table').replaceWith(resp.content);
				location.href = base_url+'admin/visible';
            }
        },'json');
    }
}

function sortBy_User(type, attr, dir)
{
	var post_data = {};
	post_data['type'] = type;
	post_data['attr'] = attr;
	post_data['dir'] = dir;
	$.post(base_url+'admin/manager/setUsers',post_data,function(){location.href=base_url+'admin/manager'});
}

