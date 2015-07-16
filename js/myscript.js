jQuery(document).ready(function($){


	////////////////////////// Expression of Interest
	var lot_status=jQuery('#lot_status').val();
	if(lot_status == 'Expression of Interest')
	{
		jQuery('#customer_details').show();	
	}
	
	jQuery( "#lot_eoi_user" ).autocomplete({
	source: function(request, response) { 
				jQuery.ajax({
				type: "POST",
				url:  ajaxurl,
				data: {
							'action'	  :'plms_ajax_get_user_request',
							'lot_eoi_user' : jQuery('#lot_eoi_user').val()							
				  	  },
				success: function(data)
						{
							if (data != null) {
							response(data.split(';'));
							}
						},	
				error: function(result) {
						alert("Error");
					}		
			});
			},
			minlength : 1
	});
	//////////////// Expression of Interest


jQuery('#construction_time').datepicker({
				dateFormat : 'dd/mm/yy',
				yearRange: '2014:2040',
				changeMonth: true,
				changeYear: true,
			});

	////////////////////////// Remove Lot Resources	
jQuery('.second img').click(function(){
	var id = jQuery(this).attr('id');		
	var class_name = $(this).attr('class');

	var result = confirm(" Want to delete?");
	if (result && id != '' )
	 {
		jQuery.ajax({
			type: "POST",
			url:  ajaxurl,
			data: {
						'action'	  :'plms_ajax_resource_remove_request',
						'resorce_id' 	  : id,		
						'type': class_name				
				  },
			success: function(response)
					{
						if(response == 'true')
						{ 
							jQuery("."+id).fadeOut("slow");
						}
					},			
		});
	}
});
//////////////// edn of Remove Property Resources

    
	//Add More Button
	$(".lot_add-txt").click(function(){
		var no = $(".form-group").length + 1;
		if( 15 < no ) {
			alert('Stop it!');
			return false;
		}
		var more_textbox = $('<div class="lot_form-group">' +	
		'<label class="col-sm-2 control-label" for="lot_txtbox' + no + '">Document Uploads <span class="label-numbers">' + no + '</span></label>' +	
		'<div class="col-sm-10"><label class="col-sm-2 control-label" style="margin-right:5px;" for="lot_txtbox' + no + '">File title </label><input class="lot_form-control" type="text" name="lot_file_title[]" id="lot_txtbox' + no + '" required="required" /><br/><label class="col-sm-2 control-label" style="margin:0px 5px 0px 5px;" for="lot_txtbox' + no + '">File Upload </label><input class="lot_form-control" type="file" name="lot_documents[]" id="lot_txtbox' + no + '" required="required" />' +
		'<a href="#" class="lot_remove-txt">Remove</a>' +
		'</div></div>');
		more_textbox.hide();
		$(".lot_form-group:last").after(more_textbox);
		more_textbox.fadeIn("slow");
		return false;
	});
	
	//Remove Button
	$('.lot_form-horizontal').on('click', '.lot_remove-txt', function(){
		$(this).parent().parent().css( 'background-color', '#CCC' ); /*  #FF6C6C */
		$(this).parent().parent().fadeOut("slow", function() {
			$(this).remove();
			$('.label-numbers').each(function( index ){
				$(this).text( index + 1 );
			});
		}); 
		return false;
	});
    
    
    
    //Add More Button
     $(".add-txt").click(function(){
    	 
      var no = $(".form-group").length + 1;
      if( 15 < no ) {
       alert('Stop it!');
       return false;
      }
      var more_textbox = $('<div class="form-group">' + 
      '<label class="col-sm-2 control-label" for="txtbox' + no + '">Resource Uploads <span class="label-numbers">' + no + '</span></label>' + 
      '<div class="col-sm-10"><label class="col-sm-2 control-label" style="margin-right:5px;" for="txtbox' + no + '">File title </label><input class="form-control" type="text" name="res_file_title[]" id="txtbox' + no + '" required="required" /><br/><label class="col-sm-2 control-label" style="margin:0px 5px 0px 5px;" for="txtbox' + no + '">File Upload </label><input class="form-control" type="file" name="resources[]" id="txtbox' + no + '" required="required" />' +
      '<a href="#" class="remove-txt">Remove</a>' +
      '</div></div>');
      more_textbox.hide();
      $(".form-group:last").after(more_textbox);
      more_textbox.fadeIn("slow");
      return false;
      
     });
     
     //Remove Button
     $('.form-horizontal').on('click', '.remove-txt', function(){
    	 
      $(this).parent().parent().css( 'background-color', '#CCC' ); /  #FF6C6C /
      $(this).parent().parent().fadeOut("slow", function() {
       $(this).remove();
       $('.label-numbers').each(function( index ){
        $(this).text( index + 1 );
       });
      }); 
      return false;
     });
     
     
        var pro_id =  jQuery('#plms_pro_id').val();
        var seleected_type =  jQuery('#seleected_type').val();
        var selected_value =  jQuery('#selected_value').val();
        
               
        var flag_page =  jQuery('#flag_page').val();
        
        var txtBoxValue1 = jQuery('#field_name').children(":selected").attr("title");
        var txtBoxid1 = jQuery('#field_name').children(":selected").val();
        plms_dropdowns(txtBoxid1,txtBoxValue1,seleected_type,selected_value);
		
		jQuery('#field_name').change(function()
		{
			var txtBoxValue = jQuery(this).children(":selected").attr("title");
			var txtBoxid = jQuery(this).children(":selected").val();
			plms_dropdowns(txtBoxid,txtBoxValue,seleected_type,'');
		});
		
		function plms_dropdowns(a,b,c,d)
		{
			jQuery.ajax({
				type: "POST",
				url:  ajaxurl,
				data: {					
							'action'	  :'plms_my_ajax_filter_request',
							'txtid' 	  : a,
							'txtvalue'	  : b,
							'seltxtid'	  : c,
							'seltxtvalue' : d,
							'pro_id' :	pro_id,
							'flag_page'   : flag_page
				  	  },
				success: function(response)
						{
							jQuery('#txtbox').html(response);
							console.log( response );
						},
				error: function(errorThrown)
						{
           			 		console.log(errorThrown);
       			 		}
			});
		}
     	
});

function plms_show_form()
{
	jQuery('#user_form').show();	
}

function plms_show_customer_details(val)
{
	if(val == 'Expression of Interest')
	{
		jQuery('#customer_details').show();	
		jQuery('.chosen-container-single').css('width','180px');	
	}
	else
	{
		jQuery('#customer_details').hide();
		jQuery('#user_form').hide();
	}
}