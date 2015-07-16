<?php
/*
 * Plugin Name:Property Lot Management System
 * Plugin URI:  http://wordpress.org/extend/plugins/
 * Description: A commercial plugin for creating custom lot using property. 
 * Version: 1.0
 * Author: Myriad Solutionz
 * Author URI:  http://myriadsolutionz.com/
 */

if ( !defined( 'ABSPATH' ) ) exit;
 
global $wpdb;
if( !defined( 'PLMS_PLUGIN_DIR' ) )
{
	define( 'PLMS_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
}

if( !defined( 'PLMS_PROPERTY_TABLE' ))
{
	define( 'PLMS_PROPERTY_TABLE', $wpdb->prefix.'property'); 
}

if( !defined( 'PLMS_LOT_TABLE' ))
{
	define( 'PLMS_LOT_TABLE', $wpdb->prefix.'lot' ); 
}

if( !defined( 'PLMS_LOT_INQUIRY_TABLE' ))
{
	define( 'PLMS_LOT_INQUIRY_TABLE', $wpdb->prefix.'lot_inquiry' ); 
}

if( !defined( 'PLMS_PROPERTY_RESOURCES_TABLE' ))
{
	define( 'PLMS_PROPERTY_RESOURCES_TABLE', $wpdb->prefix.'property_resources' ); 
}

if( !defined( 'PLMS_LOT_RESOURCES_TABLE' ))
{
	define( 'PLMS_LOT_RESOURCES_TABLE', $wpdb->prefix.'lot_resources' ); 
}


register_activation_hook(__FILE__, 'plms_custommap_install');


function plms_custommap_install()
{
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	$sql1 = "CREATE TABLE IF NOT EXISTS `".PLMS_PROPERTY_TABLE."` (
		  `ID` int(11) NOT NULL AUTO_INCREMENT,
		  `pro_title` text NOT NULL,		 
		  `pro_status` tinyint DEFAULT '1' NOT NULL,		  
		  `pro_country` varchar(500) NOT NULL,
		  `pro_state` varchar(500) NOT NULL,
		  `pro_city` varchar(50) NOT NULL,
		  `pro_gallery_id` int(11) NOT NULL,
		  `pro_description` Varchar(500) NOT NULL,
		  `pro_total_lots` varchar(500) NOT NULL,
		  `pro_cons_timeline` varchar(500) NOT NULL,
		  `pro_start_price` varchar(500) NOT NULL,
		  `pro_rental_app` varchar(500) NOT NULL,
		  `pro_site_area` varchar(500) NOT NULL,		  
		  `pro_added_date` datetime NOT NULL,
		  `pro_last_modified_date` datetime NOT NULL,
		  PRIMARY KEY (`ID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
        		
	dbDelta($sql1);
	
	
	$sql2 = "CREATE TABLE IF NOT EXISTS `".PLMS_LOT_TABLE."` (
			  `ID` int(11) NOT NULL AUTO_INCREMENT,
			  `pro_id` int(11) NOT NULL,
			  `lot_user_id` int(11) NOT NULL,
			  `lot_user_role` varchar(500) NOT NULL,
			  `lot_assign_to` varchar(500) NOT NULL,
			  `lot_title` varchar(500) NOT NULL,
			  `lot_description` varchar(500) NOT NULL,
			  `lot_no` varchar(500) NOT NULL,
			  `lot_area` varchar(500) NOT NULL,
			  `lot_status` varchar(500) NOT NULL,
              `lot_eoi_user` varchar(200) NOT NULL,
			  `lot_price` bigint(20) NOT NULL,
			  `lot_facade_image` text NOT NULL,
			  `lot_floor_image` text NOT NULL,
			  `lot_house_size` varchar(500) NOT NULL,
			  `lot_build_price` varchar(500) NOT NULL,
			  `lot_house_design` varchar(500) NOT NULL,
			  `lot_exc_builder` varchar(500) NOT NULL,
			  `lot_package_price` bigint(20) NOT NULL,
			  `lot_th_hl` varchar(500) NOT NULL,
			  `lot_type` varchar(500) NOT NULL,
			  `lot_legal_rep` varchar(500) NOT NULL,
			  `lot_added_date` datetime NOT NULL,
			  `lot_modified_date` datetime NOT NULL,
			  PRIMARY KEY (`ID`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	
	dbDelta($sql2);
	
	
	$sql3 = "CREATE TABLE IF NOT EXISTS `".PLMS_LOT_INQUIRY_TABLE."` (
		  `ID` int(11) NOT NULL AUTO_INCREMENT,
		  `pro_id` int(11) NOT NULL,
		  `lot_id` int(11) NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `first_name` varchar(255) NOT NULL,
		  `last_name` varchar(255) NOT NULL,
		  `phone_number` varchar(50) NOT NULL,
		  `address` text NOT NULL,
		  `inquiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (`ID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
		
	dbDelta($sql3);


	$sql4 = "CREATE TABLE IF NOT EXISTS `".PLMS_PROPERTY_RESOURCES_TABLE."` (
		  `ID` bigint(20) NOT NULL AUTO_INCREMENT,		  
		  `pro_id` int(11) NOT NULL,
		  `file_title` text NOT NULL,
		  `file_path` text NOT NULL,		  
		   PRIMARY KEY (`ID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
	dbDelta($sql4);

	$sql5 = "CREATE TABLE IF NOT EXISTS `".PLMS_LOT_RESOURCES_TABLE."` (
		  `ID` bigint(20) NOT NULL AUTO_INCREMENT,		  
		  `pro_id` int(11) NOT NULL,
		  `lot_id` int(11) NOT NULL,
		  `file_title` text NOT NULL,
		  `file_path` text NOT NULL,		  
		   PRIMARY KEY (`ID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
	
	dbDelta($sql5);
        
      add_role( 'sales_man', 'Salesman', array( 'read' => true, 'level_0' => true ) );
      add_role( 'sales_manager', 'Salesmanager', array( 'read' => true, 'level_0' => true ) );

}


class menu_script
{
	
		function __construct()
		{
			global $wpdb;
		}
	
		function plms_property_menu()
		{
		
		$pagehook = add_menu_page('Property', 'Property', 'edit_theme_options', 'list_property', array($this , 'plms_list_property_function'), 'dashicons-location-alt', '25.1002');
		add_action('load-' . $pagehook, array($this ,'plms_property_add_options' ) ); // to call property screen option
		$pagehook = add_submenu_page('list_property', 'Property','Property','edit_theme_options', 'list_property', array($this ,'plms_list_property_function') );
		$pagehook = add_submenu_page('list_property', 'Add Property','Add Property','edit_theme_options', 'add_property', array($this , 'plms_add_property_function') );
			
			
		$user_data2 = plms_PROPERTY_LOT_current_user();
		$userRole2 = $user_data2->roles;
	
		if(isset($userRole2[0]))
			$role_name2 = $userRole2[0];
		else
			$role_name2 = '';
			
		if($role_name2 == 'sales_manager' || $role_name2 == 'administrator' || $role_name2 == 'sales_man')
		{
			$pagehook = add_menu_page('Lot', 'Lot', 'level_0', 'list_lot', array($this ,'plms_list_lot_function'), 'dashicons-location-alt', '26.1002');
			add_action('load-' . $pagehook, array($this ,'plms_lot_add_options' ) ); // to call lot screen option
			$pagehook = add_submenu_page('list_lot', 'Lot','Lot','level_0', 'list_lot', array($this ,'plms_list_lot_function'));
			$pagehook = add_submenu_page('list_lot', 'Add Lot','Add Lot','edit_theme_options', 'add_lot', array($this ,'plms_add_lot_function'));
			$pagehook = add_submenu_page(null, 'Edit Lot','Edit Lot','level_0', 'edit_lot', array($this ,'plms_edit_lot_function'));
		
		}
			add_action('admin_head-'.$pagehook, array($this , 'plms_toggle_class') );
			
		}
		
		
	
		function plms_list_property_function()
		{
			include('admin/property-list.php');
		}
		function plms_add_property_function()
		{	
			include('admin/property-add.php');
		}
		
		
		function plms_list_lot_function()
		{
			include('admin/lot-list.php');
		}
		function plms_add_lot_function()
		{	
			include('admin/lot-add.php');
		}
		
		function plms_edit_lot_function()
		{
			include('admin/lot-edit.php');
		}
		
		function plms_toggle_class()
		{
			/*echo '<script type="text/javascript">
	
				//<![CDATA[
			
				jQuery(document).ready( function($) {
			
				 $(".if-js-closed").removeClass("if-js-closed").addClass("closed");
				 
				 postboxes.add_postbox_toggles( "wp-list-table-custom-table_page_wwwp_ltable_add_coupon" );
				 
				});
			
				//]]>
			
			   </script>';*/
		}
		
		function plms_property_add_options() // for property Screen Option
		{
			  $option = 'per_page';
			  $args = array( 'label' => 'Property', 'default' => 10, 'option' => 'users_per_page');
			  add_screen_option( $option, $args );
		}

		function plms_lot_add_options() // for Lots Screen Option
		{
			  $option = 'per_page';
			  $args = array( 'label' => 'Lots', 'default' => 10, 'option' => 'users_per_page');
			  add_screen_option( $option, $args );
		}
		
		
		function plms_property_enqueue_admin($hook)
		{
                
				wp_register_style( 'plms', PLMS_PLUGIN_DIR . 'css/plms.css' );
				wp_enqueue_style( 'plms' );
                
                wp_register_style( 'css-ui', PLMS_PLUGIN_DIR . 'css/jquery-ui.css' );
				wp_enqueue_style( 'css-ui' );
                
               
                wp_register_script( 'myscript', PLMS_PLUGIN_DIR . 'js/myscript.js' );
				wp_enqueue_script( 'myscript' );
                

               wp_enqueue_script('jquery-ui-autocomplete'); 
               wp_enqueue_script('jquery-ui-datepicker');
				
						
                wp_register_script('new_user_reg_script', PLMS_PLUGIN_DIR . 'js/ajax-registration.js', array('jquery'), null, false);
				wp_enqueue_script('new_user_reg_script');
				wp_localize_script( 'new_user_reg_script', 'wp_reg_vars', array('wp_ajax_url' => admin_url( 'admin-ajax.php' )) );
				//$hookarray = array('property_page_add_property','lot_page_add_lot','admin_page_edit_lot');
			}
			
		
		
		
		function plms_add_hooks()
		{
			add_action('admin_menu', array($this , 'plms_property_menu'));
			add_action('admin_enqueue_scripts', array($this ,'plms_property_enqueue_admin'));
		}
}

$obj_menu_Script = new menu_script();
$obj_menu_Script->plms_add_hooks();


include('propertylot-model.php');
include('required_plugin.php');


////************************ WP AJAX FUNCTION **********************************///////////////////
	
add_action('wp_ajax_nopriv_plms_my_ajax_filter_request', 'plms_my_ajax_filter_request');
add_action('wp_ajax_plms_my_ajax_filter_request', 'plms_my_ajax_filter_request');
	
function plms_my_ajax_filter_request()
{
	global $wpdb;	
	if($_POST['txtid']!='' && sanitize_text_field($_POST['flag_page'])=='list_lots')
	{
		$pro_id = sanitize_text_field($_POST['pro_id']);
		$outcomes = plms_fetch_lots_by_filter(sanitize_text_field($_POST['txtid']), $pro_id);
		$mydropdown = '<select id="field_value" name="field_value">';
		$mydropdown .= '<option value="">--Select--</option>';
		foreach($outcomes as $outcomes_data)
		{
          
			 if( isset( $_POST["seltxtvalue"]) &&  ( plms_escape_attr($_POST["seltxtvalue"]) == $outcomes_data[plms_escape_attr($_POST['txtid'])] ) )
            
				$selectedr = 'selected="selected"';
			 else
             
				$selectedr = '';
				
			 if(sanitize_text_field($_POST['txtid']) == 'lot_assign_to')
			 {	
				  $user_info = get_userdata($outcomes_data[sanitize_text_field($_POST['txtid'])]);
				  $username=$user_info->user_login;
				
				  $mydropdown .= '<option value="'.$outcomes_data[sanitize_text_field($_POST['txtid'])].'" '.$selectedr.'">'.$username.'</option>'; 
			 }
			 else
			 {
			 	 $mydropdown .= '<option value="'.$outcomes_data[sanitize_text_field($_POST['txtid'])].'" '.$selectedr.'">'.$outcomes_data[sanitize_text_field($_POST['txtid'])].'</option>';
			 }
		
		}
		$mydropdown .= '</select>';
		
		echo $mydropdown;	
		
	}
	else if($_POST['txtid']!='' && sanitize_text_field($_POST['flag_page'])=='list_property')
	{	
		$outcomes = plms_fetch_property_by_filter(sanitize_text_field($_POST['txtid']));
		$mydropdown = '<select id="field_value" name="field_value">';
		$mydropdown .= '<option value="">--Select--</option>';
		foreach($outcomes as $outcomes_data)
		{         
			 if( isset( $_POST["seltxtvalue"]) &&  plms_escape_attr($_POST["seltxtvalue"]) == $outcomes_data[plms_escape_attr($_POST['txtid'])] )
				$selectedr = 'selected="selected"';
			 else
				$selectedr = '';
						
			 $mydropdown .= '<option value="'.$outcomes_data[sanitize_text_field($_POST['txtid'])].'" '.$selectedr.'">'.$outcomes_data[sanitize_text_field($_POST['txtid'])].'</option>';
		}
		$mydropdown .= '</select>';
		echo $mydropdown;	
	}
	die();
}


add_action('wp_ajax_nopriv_plms_ajax_get_user_request', 'plms_ajax_get_user_request');
add_action('wp_ajax_plms_ajax_get_user_request', 'plms_ajax_get_user_request');
function plms_ajax_get_user_request()
{
    
	if (isset($_POST['lot_eoi_user']))
	{
		$lot_eoi_user = $_POST['lot_eoi_user'];
		global $wp_roles,$wpdb;
		$name = array();
		$this_role = "'[[:<:]]subscriber[[:>:]]'";
	
		$query = "SELECT * FROM $wpdb->users WHERE ID = ANY (SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '" . $wpdb->prefix . "capabilities' AND meta_value RLIKE $this_role AND user_login LIKE '%" . $lot_eoi_user . "%' )";
		$users_of_this_role = $wpdb->get_results($query);
	
		if ($users_of_this_role)
		{
			foreach ($users_of_this_role as $user)
			{
				$curuser = get_userdata($user->ID);
				$name[$i] = $curuser->user_login;
				$i++;
			}
          
			$tags = implode("; ", $name);
			echo $tags;
            exit;
		}
		else
		{
			echo "No User Found";
            exit;
		}
	
	}
    
}


	
add_action('wp_ajax_nopriv_plms_ajax_resource_remove_request', 'plms_ajax_resource_remove_request');
add_action('wp_ajax_plms_ajax_resource_remove_request', 'plms_ajax_resource_remove_request');
function plms_ajax_resource_remove_request()
{
	if(sanitize_text_field($_POST['type']) =='property')
	{
		if(sanitize_text_field($_POST['resorce_id'])!='')
		{
			$resource_id=sanitize_text_field($_POST['resorce_id']);
			global $wpdb;
			$res=$wpdb->query("delete from ".PLMS_PROPERTY_RESOURCES_TABLE." where ID=$resource_id");
			if($res)
			{
				echo "true";
			}
		}
	}
	
	else if(sanitize_text_field($_POST['type']) =='lot')
	{
		if(sanitize_text_field($_POST['resorce_id'])!='')
		{
			$resorce_id=sanitize_text_field($_POST['resorce_id']);
			global $wpdb;
			$res=$wpdb->query("delete from ".PLMS_LOT_RESOURCES_TABLE." where ID=$resorce_id");
			if($res)
			{
				echo "true";
			}
		}
	}
	
	die();
}

 
function plms_wp_reg_new_user()
{
 
 
 
  if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'wp_new_user' ) )
	die( 'Ooops, something went wrong, please try again later.' );
	
 
  if( sanitize_text_field($_POST['mail']) == '' )
  {
	    die( 'Please Enter Email Address.' );
  }
  if( !filter_var((sanitize_text_field($_POST['mail'])), FILTER_VALIDATE_EMAIL) )
  {
	    die( 'Please Enter Valid Email.' );
  }

  if( sanitize_text_field($_POST['user']) == '' )
  {
	  die( 'Please Enter User Name.' );
  }
  if( sanitize_text_field($_POST['pass']) =='' )
  {
	    die( 'Please Enter Password.' );
  }
	
	$username = sanitize_text_field($_POST['user']);
	$password = sanitize_text_field($_POST['pass']);
	$email    = sanitize_text_field($_POST['mail']);
	$fname     = sanitize_text_field($_POST['fname']);
	$lname     = sanitize_text_field($_POST['lname']);
	$address   = sanitize_text_field($_POST['address']);
	$phone_no  = sanitize_text_field($_POST['phone_no']);
	$nick     = sanitize_text_field($_POST['nick']);
  
	$userdata = array(
		'user_login' => $username,
		'user_pass'  => $password,
		'user_email' => $email,
		'first_name' => $fname,
		'last_name' => $lname,
		'nickname'   => $nick,
	);

	$user_id = wp_insert_user( $userdata ) ;
	
	if( !is_wp_error($user_id) )
	{
		update_user_meta( $user_id, 'address', $address );
		update_user_meta( $user_id, 'phone_no', $phone_no);
		echo '1';
	}
	else
	{
		echo $user_id->get_error_message();
	} 
  die();
}
add_action('wp_ajax_register_user', 'plms_wp_reg_new_user');
add_action('wp_ajax_nopriv_register_user', 'plms_wp_reg_new_user');



///////////************** Adding  Fields in user Registration ************************//////////////



add_action( 'user_new_form', 'plms_extra_user_profile_fields' );
add_action( 'show_user_profile', 'plms_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'plms_extra_user_profile_fields' );

function plms_extra_user_profile_fields( $user ) { ?>
<h3><?php _e("Extra profile information", "blank"); ?></h3>

<table class="form-table">
<tr>
<th><label for="address"><?php _e("Address"); ?></label></th>
<td> 
<textarea cols="37" rows="5" id="address" name="address"><?php echo esc_attr( get_the_author_meta( 'address', $user->ID ) ); ?></textarea>
<br />
<!--<span class="description"><?php _e("Please enter your address."); ?></span>-->
</td>
</tr>

<tr>
<th><label for="phoneno"><?php _e("Phone No"); ?></label></th>
<td>
<input type="text" name="phone_no" id="phone_no" value="<?php echo esc_attr( get_the_author_meta( 'phone_no', $user->ID ) ); ?>" class="regular-text" /><br />
<!--<span class="description"><?php _e("Please enter your Phone No."); ?></span>-->
</td>
</tr>
</table>
<?php }

add_action( 'personal_options_update', 'plms_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'plms_save_extra_user_profile_fields' );

function plms_save_extra_user_profile_fields( $user_id )
{
    if ( !current_user_can( 'edit_user', $user_id ) )
    { 
        return false; 
    }
    
    update_user_meta( $user_id, 'address', sanitize_text_field($_POST['address']) );
    update_user_meta( $user_id, 'phone_no', sanitize_text_field($_POST['phone_no']) );

}

////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action('init', 'plms_add_ob_start');

function plms_add_ob_start()
{
	ob_start("plms_callback");
}

function plms_callback($buffer)
{
 	return $buffer;
}
?>