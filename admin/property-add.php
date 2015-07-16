<div class="wrap">
<h2><?php

if (sanitize_text_field($_GET['action']) == 'edit' && !empty($_GET['property']))
{
    _e('Update Property ', 'propertylot');
}
else
{
    echo _e('Add New Property ', 'propertylot');
}

?></h2>
	<?php

$errors = array();
$error_flag = 0;

if ((isset($_POST['add_property']) && sanitize_text_field($_POST['add_property']) == 'Add New Property') || (isset($_POST['update_property']) && sanitize_text_field($_POST['update_property']) == 'Update Property'))
{

    $pro_title = isset($_POST['pro_title']) ? sanitize_text_field($_POST['pro_title']) : '';
    $pro_total_lots = isset($_POST['pro_total_lots']) ? sanitize_text_field($_POST['pro_total_lots']) : '';
    $pro_cons_timeline = isset($_POST['pro_cons_timeline']) ? sanitize_text_field($_POST['pro_cons_timeline']) : '';
    $pro_start_price = isset($_POST['pro_start_price']) ? sanitize_text_field($_POST['pro_start_price']) : '';
    $pro_rental_app = isset($_POST['pro_rental_app']) ? sanitize_text_field($_POST['pro_rental_app']) : '';
    $pro_site_area = isset($_POST['pro_site_area']) ? sanitize_text_field($_POST['pro_site_area']) : '';
    $pro_gallery_id = isset($_POST['pro_gallery_id']) ? sanitize_text_field($_POST['pro_gallery_id']) : '';
    $pro_description = isset($_POST['pro_description']) ? sanitize_text_field($_POST['pro_description']) : '';
    $pro_country = isset($_POST['pro_country']) ? sanitize_text_field($_POST['pro_country']) : '';
    $pro_state = isset($_POST['pro_state']) ? sanitize_text_field($_POST['pro_state']) : '';
    $pro_city = isset($_POST['pro_city']) ? sanitize_text_field($_POST['pro_city']) : '';

    if (empty($pro_title))
    {
        $errors['pro_title'] = __('<strong>ERROR</strong>: Please enter property name.');
        $error_flag = 1;
    }
    if (empty($pro_total_lots))
    {
        $errors['pro_total_lots'] = __('<strong>ERROR</strong>: Please enter property lots.');
        $error_flag = 1;
    }

    if (empty($pro_cons_timeline))
    {
        $errors['pro_cons_timeline'] = __('<strong>ERROR</strong>: Please enter property construction timeline.');
        $error_flag = 1;
    }

    if (empty($pro_start_price))
    {
        $errors['pro_start_price'] = __('<strong>ERROR</strong>: Please enter property starting price.');
        $error_flag = 1;
    }

    if (empty($pro_rental_app))
    {
        $errors['pro_rental_app'] = __('<strong>ERROR</strong>: Please enter property rental appraisal.');
        $error_flag = 1;
    }

    if (empty($pro_site_area))
    {
        $errors['pro_site_area'] = __('<strong>ERROR</strong>: Please enter property sie area.');
        $error_flag = 1;
    }

    if (empty($pro_country))
    {
        $errors['pro_country'] = __('<strong>ERROR</strong>: Please enter country');
        $error_flag = 1;
    }

    if (empty($pro_state))
    {
        $errors['pro_state'] = __('<strong>ERROR</strong>: Please enter state.');
        $error_flag = 1;
    }

    if (empty($pro_city))
    {
        $errors['pro_city'] = __('<strong>ERROR</strong>: Please enter city.');
        $error_flag = 1;
    }

    if ($error_flag == 1)
    {
        echo '<div class="error below-h2">';

        foreach ($errors as $get_error)
        {
            echo "<p>" . $get_error . "</p>";
        }
        echo '</div>';
    }
    else
    {
        global $wpdb;
        $data = array(
            'pro_title' => $pro_title,
            'pro_country' => $pro_country,
            'pro_state' => $pro_state,
            'pro_city' => $pro_city,
            'pro_gallery_id' => $pro_gallery_id,
            'pro_description' => $pro_description,
            'pro_total_lots' => $pro_total_lots,
            'pro_cons_timeline' => $pro_cons_timeline,
            'pro_start_price' => $pro_start_price,
            'pro_rental_app' => $pro_rental_app,
            'pro_site_area' => $pro_site_area,
            'pro_added_date' => date('Y-m-d h:i:s'));


        if (isset($_POST['add_property']) && sanitize_text_field($_POST['add_property']) == 'Add New Property')
        {
            $wpdb->insert(PLMS_PROPERTY_TABLE, plms_escape_slashes_deep($data));
            $id = $wpdb->insert_id;
        }
        else
        {
            $where = array('ID' => sanitize_text_field($_REQUEST['prop_id']) );
            $wpdb->update(PLMS_PROPERTY_TABLE, plms_escape_slashes_deep($data), $where);
            $id = $_REQUEST['prop_id'];
        }
        //************************ Update Resources For Property ***********************************//
        if (isset($_FILES['resources']['name'][0]) && !empty($_FILES['resources']['name'][0]))
        {
            $output_dir = "../wp-content/uploads/resource/";
            if (!is_dir($output_dir))
            {
                mkdir($output_dir, 0777, true);
            }
            $error = $_FILES["resources"]["error"];

            $fileCount = count($_FILES["resources"]["name"]);
            $file_default_path = "wp-content/uploads/resource/";
            for ($i = 0; $i < $fileCount; $i++)
            {
                $fileName = $_FILES["resources"]["name"][$i];
                $file_title = sanitize_text_field($_POST["res_file_title"][$i]);
                $filePath = $output_dir . $fileName;
                if (file_exists($filePath))
                {
                    $fileName = time() . $fileName;
                }

                move_uploaded_file($_FILES["resources"]["tmp_name"][$i], $output_dir . $fileName);
                $resources = array(
                    'pro_id' => $id,
                    'file_title' => $file_title,
                    'file_path' => $file_default_path . $fileName);

                $wpdb->insert(PLMS_PROPERTY_RESOURCES_TABLE, $resources);
            }
        }

        if (isset($_POST['add_property']) && sanitize_text_field($_POST['add_property']) == 'Add New Property')
        {
            $redirect_url = add_query_arg(array('page' => 'list_property'), admin_url('admin.php'));
            $redirect_url = add_query_arg(array('message' => '4'), $redirect_url);
        }
        else
        {
            $redirect_url = add_query_arg(array('page' => 'list_property'), admin_url('admin.php'));
            $redirect_url = add_query_arg(array('message' => '2'), $redirect_url);
        }

        wp_redirect($redirect_url);
        exit;

    }
}
if (isset($_GET['action']) && isset($_GET['property']) && (sanitize_text_field($_GET['action']) == 'edit') && !empty($_GET['property']))
{
    $args = array('field_name' => ID, 'field_value' => sanitize_text_field($_GET['property']));
    $get_results = plms_fetch_property('', $args);
    $get_property = $get_results[0];
    $pro_title = isset($get_property['pro_title']) ? plms_escape_slashes_deep($get_property['pro_title']) : '';
    $pro_total_lots = isset($get_property['pro_total_lots']) ? plms_escape_slashes_deep($get_property['pro_total_lots']) : '';
    $pro_cons_timeline = isset($get_property['pro_cons_timeline']) ? plms_escape_slashes_deep($get_property['pro_cons_timeline']) : '';
    $pro_start_price = isset($get_property['pro_start_price']) ? plms_escape_slashes_deep($get_property['pro_start_price']) : '';
    $pro_rental_app = isset($get_property['pro_rental_app']) ? plms_escape_slashes_deep($get_property['pro_rental_app']) : '';
    $pro_site_area = isset($get_property['pro_site_area']) ? plms_escape_slashes_deep($get_property['pro_site_area']) : '';
    $pro_gallery_id = isset($get_property['pro_gallery_id']) ? plms_escape_slashes_deep($get_property['pro_gallery_id']) : '';
    $pro_description = isset($get_property['pro_description']) ? plms_escape_slashes_deep($get_property['pro_description']) :  '';
    $pro_country = isset($get_property['pro_country']) ? plms_escape_slashes_deep($get_property['pro_country']) : '';
    $pro_state = isset($get_property['pro_state']) ? plms_escape_slashes_deep($get_property['pro_state']) : '';
    $pro_city = isset($get_property['pro_city']) ? plms_escape_slashes_deep($get_property['pro_city']) : '';
    $resources = plms_fetch_property_resources_by_id($_GET['property']);
}

?>
	
    
  <form id="add_property" class="validate"  name="add_property" method="post" action="" enctype="multipart/form-data">
  	<input type="hidden" name="prop_id" value="<?php if (isset($_GET['property']) && sanitize_text_field($_GET['action']) == 'edit'){ echo sanitize_text_field($_GET['property']);}?>" />
    <table class="form-table">
     <tbody>
          <tr class="form-required">
            <th scope="row"><label><b><?php _e('Property Name', 'propertylot'); ?>: </b></label><span style="color:red;">*</span></th>
            <td><input type="text" name="pro_title" value="<?php echo $pro_title; ?>"  placeholder="Enter Property Name"></td>
          </tr>
          
          <tr class="form-required">
            <th scope="row"><label><b><?php _e('Total Lots', 'propertylot'); ?>: </b></label><span style="color:red;">*</span></th>
            <td><input type="text" name="pro_total_lots" value="<?php echo $pro_total_lots; ?>"  placeholder="Enter Total Lots"></td>
          </tr>
           
          <tr class="form-required">
            <th scope="row"><label><b><?php _e('Construction Timeline', 'propertylot'); ?>:</b></label><span style="color:red;">*</span></th>
            <td><input type="text" id="construction_time" name="pro_cons_timeline" value="<?php echo $pro_cons_timeline; ?>"  placeholder="Enter Construction Timeline"></td>
          </tr>
          
          <tr class="form-required">
            <th scope="row"><label><b><?php _e('Project Starting Price', 'propertylot'); ?>: </b></label><span style="color:red;">*</span></th>
            <td><input type="text" name="pro_start_price" value="<?php echo $pro_start_price; ?>" placeholder="Enter Project Starting Price"></td>
          </tr>
          
           <tr class="form-required">
            <th scope="row"><label><b><?php _e('Rental Appraisal ', 'propertylot'); ?>: </b></label><span style="color:red;">*</span></th>
            <td><input type="text" name="pro_rental_app" value="<?php echo $pro_rental_app; ?>" placeholder="Enter Rental Appraisal"></td>
          </tr>
          
          <tr class="form-required">
            <th scope="row"><label><b><?php _e('Site Area ', 'propertylot'); ?>:</b></label><span style="color:red;">*</span></th>
            <td><input type="text" name="pro_site_area" value="<?php echo $pro_site_area; ?>"  placeholder="Enter Site Area"></td>
          </tr>  
          
          <tr>
           <th scope="row"><label><b><?php _e('Add Gallery', 'propertylot'); ?>: </b></label></th>
            <?php $result = plms_fetch_gallary(); ?>
            <td>
          		  <select class="category-select" name="pro_gallery_id">
               		 <option value="">Select Gallery</option>
					<?php
                    for ($i = 0; $i < count($result); $i++)
                    {
                    ?>
                        <option value="<?php   echo $result[$i]['id']; ?>"<?php  if ($result[$i]['id'] == $pro_gallery_id) { echo "selected"; }?>><?php  echo $result[$i]['name'];  ?></option>
                    	<?php
                    
                    }

?>
              </select>
            </td><td><b>Note :- </b>First you have to create gallery using photo-gallery plugin,<br>after that you can select gallery from this dropdown.</td>
          </tr>
          
          <tr class="form-required">
            <th scope="row"><label><b><?php _e('Description ', 'propertylot'); ?>:</b></label></th>
            <td colspan="5" width="100px" style="max-width:150px">
       <?php $settings = array('textarea_rows' => 2);
            wp_editor($pro_description, 'pro_description', $settings);
        ?>
            </td>
          </tr>    
          
         
          <tr class="form-required">
           <th scope="row"><label><b> <?php _e('Country', 'propertylot'); ?>: </b></label><span style="color:red;">*</span></th>
            <td>
            
            <input type="text" name="pro_country" id="pro_country" value="<?php echo $pro_country; ?>"  placeholder="Enter Country Name">
             
            </td>
          </tr>
          
          <tr class="form-required">
            <th scope="row"><label><b><?php _e('State', 'propertylot'); ?>: </b></label><span style="color:red;">*</span></th>
            <td colspan="2">
             <input type="text"  id="state_dropdown" name="pro_state" value="<?php echo $pro_state; ?>"  placeholder="Enter State Name">
            </td>
          </tr>
          
          <tr class="form-required">
           <th scope="row"><label><b> <?php _e('City', 'propertylot'); ?> : </b></label><span style="color:red;">*</span></th>
            <td>
             <input type="text"  id="city_dropdown" name="pro_city" value="<?php echo $pro_city; ?>"  placeholder="Enter City Name">
            </td>
          </tr>
          
     	 <tr>
			<th scope="row"><label><b> <?php _e('Resources Uploads', 'propertylot'); ?>: </b></label></th>
            	<td colspan="3">
                 <div class="form-horizontal">   
                         <div class="form-group">     
                  		  <label class="col-sm-2 control-label" for="txtbox1">Resource Uploads  <span class="label-numbers">1</span></label>
                    	 	<div class="col-sm-10">                     
                            <label class="col-sm-2 control-label" style="margin-right:5px;" for="txtbox1">File Title :</label>
                            <input class="form-control" type="text" name="res_file_title[]" id="txtbox1"/> <br/>
                            <label class="col-sm-2 control-label" style="margin:0px 5px 0px 5px" for="txtbox1">File Upload :</label>
                            <input class="form-control" type="file" name="resources[]" id="txtbox1"/>
                             <a href="#" class="add-txt">Add More</a>
              			 </div>
                 		</div> 
                        <div class="span4">
                            <?php
                
                            if (sanitize_text_field($_GET['action']) == 'edit' && !empty($_GET['property']))
                            {
                                $i = 0;
                                foreach ($resources as $res)
                                {
                                ?>
                                    <div class="first <?php echo $res['ID'] ?>">
                                        <a href="<?php echo site_url() . '/' . $res['file_path'];?>"  target="_blank" title="Click to Download">
                                            <img src="<?php echo PLMS_PLUGIN_DIR;?>/images/doc.png" />
                                            <span> <?php echo (empty($res['file_title'])) ? "Resource" : $res['file_title']; ?> </span>
                                        </a> 
                                        <div class="second">
                                            <img class="property" id="<?php echo $res['ID'];?>" src="<?php echo PLMS_PLUGIN_DIR; ?>/images/delete.gif" />
                                        </div>
                                    </div>                       
                                     <?php
                                }
                            }
                
                        ?>
                    </div>
                 </div>
			  </td>
		  </tr>
     </tbody>
    </table>
      <?php

if (sanitize_text_field($_GET['action']) == 'edit' && !empty($_GET['property']))
{
    submit_button(__('Update Property'), 'primary', 'update_property', true, array('id' => 'update_property'));
}
else
{
    submit_button(__('Add New Property'), 'primary', 'add_property', true, array('id' => 'add_property'));
}

?>
   </form>
</div>
<div class="clear"></div>