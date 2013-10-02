var blocker = false;
$(function(){
    var requires = {};
    prepareRows();
});

function prepareRows()
{
    $('.man_id').each(function(){
        $(this).click(function(){ blocker = true})
        var id = $(this).val();

        $(this).parents('tr').click(function(){
            if (!blocker) {
                var container = $('#head_table').parents('thead');
			    var post_data = {};
				post_data['id_man'] = id;
			    $('.filter', container).each(function(){
			        post_data[$(this).attr('name')] = $(this).val();
			    });

			    $.post(base_url+'account/filter2',post_data,function(){
				location.href=base_url+'account'
				});
            }
            blocker = false;
        });
        
    });
}

function search_model()
{
    var container = $('#head_table').parents('thead');
	var post_data = {};
	
	$('.filter', container).each(function(){
	    post_data[$(this).attr('name')] = $(this).val();
	});
	post_data['model'] = $('#model_value').val();
	
	$.post(base_url+'account/filter',post_data,function(){
	location.href=base_url+'account'
	});
            
 
}
function key(event) {return ('which' in event) ? event.which : event.keyCode;}

