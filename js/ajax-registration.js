jQuery(document).ready(function($) {

  $('#btn-new-user').click( function() {

    $('.indicator').show();
    $('.result-message').hide();

   
    var reg_nonce = $('#wp_new_user_nonce').val();
    var reg_user  = $('#username').val();
    var reg_pass  = $('#pass').val();
    var reg_mail  = $('#email').val();
    var reg_fname  = $('#fname').val();
	var reg_lname  = $('#lname').val();
	var reg_address  = $('#address').val();
	var reg_phone_no  = $('#phone_no').val();
    var reg_nick  = $('#nick').val();

    
    var ajax_url = wp_reg_vars.wp_ajax_url;


    data = {
      action: 'register_user',
      nonce: reg_nonce,
      user: reg_user,
      pass: reg_pass,
      mail: reg_mail,
      fname: reg_fname,
	  lname: reg_lname,
	  phone_no: reg_phone_no,
	  address: reg_address,
      nick: reg_nick,
    };

    $.post( ajax_url, data, function(response) {
      if( response ) {
        $('.indicator').hide();

        if( response === '1' )
		{
          $('.result-message').html('Your registration is completed.');
          $('.result-message').addClass('alert-success');
		  $('.result-message').css("color","green");
          $('.result-message').show();		  
		  $('#lot_eoi_user').val(reg_user);
        }
		else
		{
          $('.result-message').html( response );
          $('.result-message').addClass('alert-danger'); 
		  $('.result-message').css("color","red");
          $('.result-message').show(); 
        }
      }
    });
    
  });
});

