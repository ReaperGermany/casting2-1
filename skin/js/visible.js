var blocker = false;
$(function(){
    var requires = {};
    $('.filter').keypress(function(e){
	var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);
        if (key == 13) {
            filterOffers(this);
            e.stopImmediatePropagation();
        }
        return key;
    });
    $('.autocomplete').each(function(){
        el = this;
        $(this).autocomplete({
            minLength: 0,
            source:  attrs[$(el).attr('name')]
        });
    });

    prepareRows();

    $( "#offer_data" ).dialog({
			autoOpen: false,
			modal: true,
			width: 380,
			buttons: {
				"Save": function() {
                                        if (!validateOffer()) return;
                                        $.post(base_url+'admin/ajax/saveOffer', prepare_data(), function(resp){
                                            if (resp.status=='OK') {
                                                location.href = base_url+'admin/visible';
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

    $( "#contact_data" ).dialog({
			autoOpen: false,
			modal: true
    });
});

function prepareRows()
{
    $('.item_id').each(function(){
        $(this).click(function(){ blocker = true})
        var id = $(this).val();

        $(this).parents('tr').click(function(){
            if (!blocker) {
                $.post(base_url+'admin/ajax/getEditOfferForm',{id:id},showOfferEditForm,'json');
            }
            blocker = false;
        });
        $('.company_name',$(this).parents('tr'))
            .click(function(){
                blocker = true;
            })
            .dblclick(function(){
                $.post(base_url+'admin/ajax/getContactData',{id:id},showContactData,'json');
        });
    });
}

function validateOffer()
{
    var has_errors = false;
    $('.error').removeClass('error');
    $('.required').each(function(){
        if (! $(this).val()) {
            parent = $(this).parents('td');
            $('input',parent).addClass('error');
            $('textarea',parent).addClass('error');
            has_errors = true;
        }
    });
    return !has_errors;
}

function showOfferEditForm(resp)
{
    $('#offer_data').html(resp.content);
    requires = resp.attrs;

    $('.attribute').each(function(){
        var id = $(this).attr('id');
        if (!requires[id]) return;
        $(this).autocomplete({
           minLength: 0,
           source:  requires[id],
           search: function(){
               $('#'+id+'_id').hval('');
           },
           select: function(event, ui){
               $('#'+id+'_id').hval(ui.item.id);
               event.stopImmediatePropagation();
           }
        });

        var field = this;
        $('#'+requires[id]['require']+"_id").hchange(function(){
            if ($('#'+requires[id]['require']+"_id").val()=='') {
                $(field).val('');
                $('#'+id+'_id').hval('');
            }
            else {
                $(field).focus();
            }
        });
    });

    $('.attribute').focus(function(){
                var id = $(this).attr('id');
                var source = [];
                if (!requires[id]) return;
                if (!requires[id]['require']) source = requires[id]['data'];
                else {
                    data_id = $('#'+requires[id]['require']+'_id').val();
                    if (data_id && requires[id]['data'][data_id]) source = requires[id]['data'][data_id];
                }

                if(source.length == 1) {
                    $(this).val(source[0]['label']);
                    $('#'+id+'_id').hval(source[0]['id']);
                }

                $(this).autocomplete({source:  source});
            });

    $('#offer_form').submit(function(){
        $('#offer_data').dialog('option','buttons').Save();
        $('#offer_data').dialog('close');
        return false;
    });

    $('input','#offer_form').keypress(
    function(e)
    {
        var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);
        if (key == 13) {
            $('#offer_form').submit();
        }
        return key;
    });

    $('#offer_data').dialog("open");
}
