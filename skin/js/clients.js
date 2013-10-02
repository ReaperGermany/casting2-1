var blocker = false;

if (blocker) {
        blocker = false;
        return;
    }
    $('#staff_list_container').html("");
    $('#staff_company_id').val(company_id);
    $.post(base_url+'admin/clients/ajaxStaffList', {company_id:1}, 
        function(resp)
        {
            if (resp.status=="OK"){
                $('#staff_data').html("");
                $('#staff_list_container').replaceWith(resp.content);
            }
        }, 'json'
    );



function add_company()
{
    clear_company_form();
    $("#company_edit_container").dialog( "open");
}

function clear_company_form()
{
    $('#company_id').val('');
    $('#company_name').val('');
}

function delete_company(company_id)
{
    blocker = true;
    $.post(base_url+'admin/clients/ajaxCompanyDelete', {
            id: company_id
        }, function(resp){
            if (resp.status=="OK"){
                $('#staff_data').html("");
                //$('#staff_list_container').html('');
                $('#left_nav').replaceWith(resp.content);
            }
        },'json'
    );
}

function edit_company(company_id, data)
{
    blocker = true;
    $('#company_id').val(company_id);
    $('#company_name').val(data['name']);
    $("#company_edit_container").dialog( "open");
}

function load_staff_list(el, company_id)
{
    if (blocker) {
        blocker = false;
        return;
    }
    $('ul#left_nav li.active').removeClass('active');
    $(el).addClass('active');
    $('#staff_list_container').html("");
    $('#staff_company_id').val(company_id);
    $.post(base_url+'admin/clients/ajaxStaffList', {company_id:company_id}, 
        function(resp)
        {
            if (resp.status=="OK"){
                $('#staff_data').html("");
                $('#staff_list_container').replaceWith(resp.content);
            }
        }, 'json'
    );
}

function load_staff_data(el, staff_id)
{
    parent = $(el).parents('ul');
    $('li.active', parent).removeClass('active');
    $(el).addClass('active');
    $('#staff_data').html("");
    $.post(base_url+'admin/clients/ajaxStaffData', {staff_id:staff_id},
        function(resp)
        {
            if (resp.status=="OK"){
                $('#staff_data').html("");
                $('#staff_data').replaceWith(resp.content);
            }
        }, 'json'
    );
}

function staff_add()
{
    clear_staff_form();
    $("#staff_edit_container").dialog( "open" );
}

function staff_edit(id, data)
{
    $('.clearable').each(function(){
        el = $(this).attr('name');
        if (data[el]){
            $(this).val(data[el]);
        }
        else {
            $(this).val("");
        }
        $('#staff_id').val(id);
    });
    $("#staff_edit_container").dialog( "open" );
}

function staff_delete(id)
{
    $.post(base_url+'admin/clients/ajaxStaffDelete', {
            id: id
        }, function(resp){
            if (resp.status=="OK"){
                $('#staff_data').html("");
                $('#staff_list_container').replaceWith(resp.content);
            }
        },'json'
    );
}

function clear_staff_form()
{
    $('.clearable').val('');
}

$("#company_edit_container").dialog({
    autoOpen: false,
    height: 220,
    width: 250,
    modal: true,
    buttons: {
        "Save": function() {
            $.post(base_url+'admin/clients/ajaxCompanySave', {
                    name: $("#company_name").val(),
                    id: $("#company_id").val()
                }, function(resp){
                    if (resp.status=="OK") {
                        $('#left_nav').replaceWith(resp.content);
                        $( "#company_edit_container" ).dialog( "close" );
                        if (resp.staffs==0) {
                            $("li#company_"+resp.id).click();
                            clear_staff_form();
                            $("#staff_edit_container").dialog("open");
                        }
                    }
                },'json'
            );
        },
        Cancel: function() {
            $( this ).dialog( "close" );
        }
    },
    close: function() {
    }
});


$("#staff_edit_container").dialog({
    autoOpen: false,
    //height: 410,
    width: 320,
    modal: true,
    buttons: {
        "Save": function() {
            $.post(base_url+'admin/clients/ajaxStaffSave',{
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
                company_id: $('#staff_company_id').val()
            },function(resp){
                if (resp.status=="OK"){
                    $('#staff_data').html("");
                    $('#staff_list_container').replaceWith(resp.content);
                }
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

$('input','#staff_edit_container').keypress(
    function(e)
    {
        var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);
        if (key == 13) {
            $('#staff_edit_container form').submit();
        }
        return key;
    });

