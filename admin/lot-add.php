<div class="wrap">


<h2><?php echo _e('Add New Lot ', 'propertylot'); ?></h2>
	<?php
	
		$errors =array();
		$error_flag = 0;
	
		
		if( (isset($_POST['add_lot']) && sanitize_text_field($_POST['add_lot'])== 'Add New Lot') )									
		{				
			$pro_id =  isset( $_POST['pro_id'] ) ? sanitize_text_field($_POST['pro_id'])  : '';
			$lot_title = isset( $_POST['lot_title'] ) ? sanitize_text_field($_POST['lot_title']) : '';
			$lot_no = isset( $_POST['lot_no'] ) ?  sanitize_text_field($_POST['lot_no'])  : '';
			$lot_area = isset( $_POST['lot_area'] ) ?  sanitize_text_field($_POST['lot_area']) : '';
			$lot_status =isset( $_POST['lot_status'] ) ?  sanitize_text_field($_POST['lot_status'])  : '';
			if( !empty($_POST['lot_status']) &&  sanitize_text_field($_POST['lot_status'])=='Expression of Interest')
			{
				$lot_eoi_user=isset($_POST['lot_eoi_user']) ? sanitize_text_field($_POST['lot_eoi_user']) : '';	
				
				$user = get_user_by( 'login', trim($lot_eoi_user));
				if($user)
				{  
				
					$lot_eoi_user = $user->ID;	
				}
				else
				{ 
					$lot_eoi_user = "Incorrect User"; 
				}				
			}
			$lot_price = isset( $_POST['lot_price'] ) ?  sanitize_text_field($_POST['lot_price'])  : '';
			$lot_house_size = isset( $_POST['lot_house_size'] ) ?  sanitize_text_field($_POST['lot_house_size'])  : '';
			$lot_build_price = isset( $_POST['lot_build_price'] ) ?  sanitize_text_field($_POST['lot_build_price'])  : '';
			$lot_house_design = isset( $_POST['lot_house_design'] ) ?  sanitize_text_field($_POST['lot_house_design'])  : '';
			$lot_exc_builder = isset( $_POST['lot_exc_builder'] ) ?  sanitize_text_field($_POST['lot_exc_builder'])  : '';
			$lot_package_price = isset( $_POST['lot_package_price'] ) ?  sanitize_text_field($_POST['lot_package_price'])  : '';
			$lot_th_hl =isset( $_POST['lot_th_hl'] ) ?  sanitize_text_field($_POST['lot_th_hl'])  : '';
			$lot_type = isset( $_POST['lot_type'] ) ?  sanitize_text_field($_POST['lot_type'])  : '';
			$lot_legal_rep = isset( $_POST['lot_legal_rep'] ) ?  sanitize_text_field($_POST['lot_legal_rep'])  : '';
			$lot_description = isset( $_POST['lot_description'] ) ?  sanitize_text_field($_POST['lot_description'])  : '';
			$lot_assign_to = isset( $_POST['lot_assign_to'] ) ?  sanitize_text_field($_POST['lot_assign_to'])  : '';
			
		
			if ( empty( $pro_id ) )
			{
				$errors['pro_id'] = __('<strong>ERROR</strong>: Please enter property.' );
				$error_flag = 1;
			}
			
			if ( empty( $lot_title) )
			{
				$errors['lot_title'] = __('<strong>ERROR</strong>: Please enter lot title.' );
				$error_flag = 1;
			}
			
			if( empty( $lot_no ) )
			{
				$errors['lot_no'] = __('<strong>ERROR</strong>: Please enter lot No.' );
				$error_flag = 1;
			}
			
			if( empty( $lot_area) )
			{
				$errors['lot_area'] = __('<strong>ERROR</strong>: Please enter lot area.' );
				$error_flag = 1;
			}
			
			if( empty( $lot_status ) )
			{
				$errors['lot_status'] = __('<strong>ERROR</strong>: Please enter lot status.' );
				$error_flag = 1;
			}
			if( !empty($lot_status) &&  $lot_status == 'Expression of Interest' )
		    {
				if(	empty($lot_eoi_user) )
				 {
					  $errors['lot_eoi_user'] = __('<strong>ERROR</strong>: Please Select User.' );
					  $error_flag = 1;
				 }
				 else if($lot_eoi_user == "Incorrect User")
				 {
					  $errors['lot_eoi_user'] = __('<strong>ERROR</strong>: Please Type Valid Username.' );
					  $error_flag = 1;
				 }
		    }
						
			if( empty( $lot_price ) )
			{
				$errors['lot_price'] = __('<strong>ERROR</strong>: Please enter lot price.' );
				$error_flag = 1;
			}
			
			
			if( empty( $lot_package_price) )
			{
				$errors['lot_package_price'] = __('<strong>ERROR</strong>: Please enter lot package price.' );
				$error_flag = 1;
			}
			
			
			if( empty( $lot_type ) )
			{
				$errors['lot_type'] = __('<strong>ERROR</strong>: Please enter lot type.' );
				$error_flag = 1;
			}
			
			if( empty( $lot_assign_to ) )
			{
				$errors['lot_assign_to'] = __('<strong>ERROR</strong>: Please select lot assignto.' );
				$error_flag = 1;
			}
			
		
			if($error_flag == 1)
			{
				echo '<div class="error below-h2">';
						foreach($errors as $get_error)
						{
							echo "<p>".$get_error."</p>";
						}	
				echo '</div>';
			}
			else
			{
				global $wpdb;
				
				$faced_path = '';
				$floor_path = '';
			   /** Start Upload Faced Image ***/  
				 $output_dir1 = "../wp-content/uploads/lot/facade/";
				 if (!is_dir($output_dir1))   
				 {
					 mkdir($output_dir1,0777,true);
				 }	
				 
				 $output_dir2 = "../wp-content/uploads/lot/floor/";
				 if (!is_dir($output_dir2))   
				 {
					 mkdir($output_dir2,0777,true);
				 }	
				  $file_default_path1="wp-content/uploads/lot/facade/";
				  $file_default_path2="wp-content/uploads/lot/floor/";
				  
				  if( isset( $_FILES['lot_facade_image']["name"] ) && !empty( $_FILES['lot_facade_image']["name"] ) ) 
				  {
					  $fileName1 = $_FILES["lot_facade_image"]["name"];
					  $filePath1 = $output_dir1. $fileName1;
					  if (file_exists($filePath1)) 
					  {
							$fileName1=time().$fileName1;			
					  }
					   move_uploaded_file($_FILES["lot_facade_image"]["tmp_name"],$output_dir1.$fileName1);
						 $faced_path = $file_default_path1.$fileName1;
				  }else{ $faced_path = '';}
				  
				  if( isset( $_FILES['lot_floor_image']["name"] ) && !empty( $_FILES['lot_floor_image']["name"] ) ) 
				  {
					  $fileName2 = $_FILES["lot_floor_image"]["name"];
					  $filePath2 = $output_dir2. $fileName2;
					  if (file_exists($filePath2)) 
					  {
						$fileName2=time().$fileName2;			
					  }
					   move_uploaded_file($_FILES["lot_floor_image"]["tmp_name"],$output_dir2.$fileName2);
					  $floor_path = $file_default_path2.$fileName2;
				  
				  }else{ $floor_path = '';}
				  
				 /** End Upload Floor Image***/
                $args =	array(
                			'pro_id'=> $pro_id,
                			'lot_title'=>$lot_title,
                			'lot_no'=>$lot_no,
                			'lot_area'=>$lot_area,
                			'lot_status'=>$lot_status,
                			'lot_eoi_user'=> $lot_eoi_user,
                			'lot_price'=>$lot_price,
                			'lot_house_size'=>$lot_house_size,
                			'lot_build_price'=>$lot_build_price,
                			'lot_house_design'=>$lot_house_design,
                			'lot_exc_builder'=>$lot_exc_builder,
                			'lot_package_price'=>$lot_package_price,
                			'lot_th_hl'=>$lot_th_hl,
                			'lot_type'=>$lot_type,
                			'lot_legal_rep'=>$lot_legal_rep,
                			'lot_description'=>$lot_description,						
                			'lot_facade_image'=>$faced_path,
                			'lot_floor_image'=>$floor_path,
                			'lot_assign_to'=>$lot_assign_to,
                			'lot_added_date'=>date('Y-m-d h:i:s')
                		);
				
					$fg= $wpdb->insert(PLMS_LOT_TABLE, plms_escape_slashes_deep($args));
                    			   
				   		$id = $wpdb->insert_id;
						
						if( !empty($lot_status) &&  $lot_status == 'Expression of Interest')
						{
						   $args2 =   array(
											'customer_user_id'=> $lot_eoi_user,
											'salesman_user_id'=> $lot_assign_to,
											'prop_id'=> $pro_id,
											'lot_id'=>$id,
											'eoi_added_date'=> date('Y-m-d h:i:s'),
											'eoi_expire_date'=> date('Y-m-d h:i:s',strtotime("+7 days"))
											);
							$wpdb->insert(EOI_TABLE, plms_escape_slashes_deep($args2));
						}
				
					
					//************************ Update Lots Documents  ***********************************//
					if( isset( $_FILES['lot_documents']['name']) && !empty( $_FILES['lot_documents']['name'])) 
					{	
						if($_FILES['lot_documents']['name'][0] !="")
						 {	
							$output_dir = "../wp-content/uploads/lot/documents/";
							if (!is_dir($output_dir))   
							{
								 mkdir($output_dir,0777,true);
							}	
							$error =$_FILES["lot_documents"]["error"];
							
								  $fileCount = count($_FILES["lot_documents"]["name"]);
								  $file_default_path="wp-content/uploads/lot/documents/";
								  for($i=0; $i < $fileCount; $i++)
								  {
									$fileName = $_FILES["lot_documents"]["name"][$i];
									$file_title=sanitize_text_field($_POST["lot_file_title"][$i]);
									$filePath = $output_dir. $fileName;
									if (file_exists($filePath)) 
									{
										$fileName=time().$fileName;			
									}
									move_uploaded_file($_FILES["lot_documents"]["tmp_name"][$i],$output_dir.$fileName);
										$resources=array(
													'lot_id'=>$id,
													'pro_id'=> $pro_id,
													'file_title'=>$file_title,
													'file_path'=>$file_default_path.$fileName
													);
										$wpdb->insert( PLMS_LOT_RESOURCES_TABLE, plms_escape_slashes_deep($resources));
									  }
							 }
						}
					///////////////********************* Update Lots Documents  ****************************///////////
								$redirect_url = add_query_arg( array( 'page' => 'list_lot' ), admin_url( 'admin.php' ) );
								$redirect_url = add_query_arg( array( 'message' => '4' ), $redirect_url );
								wp_redirect( $redirect_url ); 
								exit;
					
			}
	
	  }
		
	?>
	
    
  <form id="add_lot" class="validate"  name="add_lot" method="post" action="" enctype="multipart/form-data">
  
       <table class="form-table">
   		  <tbody>
          
          	<tr>
                   <th scope="row"><label><?php _e('Select Property', 'propertylot'); ?> : </label></th>
                    <td>
                     		<select  name="pro_id">
                         	 <option value="">Select Property</option>
								<?php
								 $fetch_property = plms_fetch_property('','');
                            	  
                               for( $i=0; $i < count($fetch_property); $i++)
                               {?>
                                      <option value="<?php echo $fetch_property[$i]['ID']?>" <?php  if( $fetch_property[$i]['ID'] == $pro_id ){ echo "selected=selected";} ?>><?php echo $fetch_property[$i]['pro_title']?></option>
                                       <?php
                               }?>                      
                        </select>
                    </td>
             </tr>
              
             <tr class="form-required">
                   <th scope="row"><label><?php _e('Lot Title', 'propertylot'); ?>: </label><span style="color:red;">*</span></th>
                    <td><input name="lot_title" type="text" class="title-input input-text" value="<?php echo esc_attr($lot_title); ?>" ></td>
              </tr>
             
     
           
               <tr class="form-required">
                   <th scope="row"><label><?php _e('Lot No', 'propertylot'); ?>: </label><span style="color:red;">*</span></th>
                    <td> <input type="text" name="lot_no" class="lotno-input input-text" value="<?php echo esc_attr($lot_no); ?>"></td>
                 </tr>
              	 <tr  class="form-required">
                      <th scope="row"><label><?php _e('Lot Area', 'propertylot'); ?>:</label><span style="color:red;">*</span></th>
                    <td><input type="text" name="lot_area" class="lotarea-input input-text" value="<?php echo esc_attr($lot_area); ?>"></td>
                 </tr>
                
            
                <tr>
                   <th scope="row"><label><?php _e('Lot Status', 'propertylot'); ?> : </label></th>
                    <td>
                     <select class="lotstatus-input input-text" name="lot_status" id="lot_status" onchange="plms_show_customer_details(this.value)">
                          <option value="Available" <?php  if( $lot_status == 'Available' ){ echo "selected";} ?>>Available</option>                    
                          <option value="Expression of Interest" <?php  if( $lot_status == 'Expression of Interest' ){ echo "selected";} ?>>Expression of Interest</option>
                          <option value="Deposit Paid" <?php  if( $lot_status == 'Deposit Paid' ){ echo "selected";} ?>>Deposit Paid</option>
                          <option value="Settled" <?php  if( $lot_status == 'Settled' ){ echo "selected";} ?>>Settled</option>                      
                        </select>
                         
                    </td>
                </tr>
                
                <tr id="customer_details" style="display:none" >
                	
                   <th>  <label> Select User : </label></th>
                   <td colspan="2">
                     <input type="text"  name="lot_eoi_user" id="lot_eoi_user" placeholder="Type User Name" value="<?php echo $lot_eoi_user; ?>"> OR 
                     &nbsp;&nbsp;&nbsp; &nbsp;
                       <input type="hidden" name="home_desi_url" id="home_desi_url"  />        <a  target="_blank" class="button button-primary" onclick="plms_show_form();" >Add New User</a> 
             
                    </td>
                </tr>
                
                 <tr id="user_form" style="display:none;">
					<td colspan="10">
                        <table  class="form-table" style="border:1px solid #000; border-radius:5px; padding:15px; width:740px;">
                      
                         
                        
                            <tr>
                              <td><label for="fname" class="sr-only"><b>First Name</b></label></td>
                               <td><input type="text" name="fname" id="fname" value="" placeholder="First Name" class="form-control" /> </td>
                               <td><label for="lname" class="sr-only"><b>Last Name</b></label></td>
                               <td><input type="text" name="lname" id="lname" value="" placeholder="Last Name" class="form-control" /> </td>
                            </tr>
                        	
                           
                            <tr>
                              <td> <label for="nick" class="sr-only"><b>Your Nickname</b></label></td>
                               <td><input type="text" name="nick" id="nick" value="" placeholder="Your Nickname" class="form-control" /> </td>
                                <td><label><b>Address</b> </label></td>
                            	<td> <textarea name="address" id="address"> </textarea> </td>
                            </tr>
                        
                        	<tr>
                             	<td><label for="phone_no"><b>Phone Number</b> </label></td>
                            	<td> <input type="text"  name="phone_no" id="phone_no" value=""  class="lotprice-input input-text"></td>
                                 <td><label for="email" class="sr-only"><b>Your Email</b></label></td>
                               <td><input type="email" name="email" id="email" value="" placeholder="Your Email" class="form-control" /> </td>
                        	</tr>
                            
                         
                            <tr>
                             <td width="130"> <label for="username" class="sr-only"><b>Choose Username</b></label></td>
                               <td><input type="text" name="username" id="username" value="" placeholder="Choose Username" class="form-control" /></td>
                              	<td width="125"> <label for="pass" class="sr-only"><b>Choose Password</b></label></td>
                               <td><input type="password" name="pass" id="pass" value="" placeholder="Choose Password" class="form-control" />
                            </td>
                            </tr>
                        
                            <?php wp_nonce_field('wp_new_user','wp_new_user_nonce', true, true ); ?>
                         <tr  class="form-required">
                         <td>
                            <input type="button" class="btn button-primary" id="btn-new-user" value="Register" />
                            </td>
                             </tr>
                        
                        
                        <tr>
                        	<td colspan="3"><div class="indicator" style="display:none;color:black">Please wait...</div>
                            <div class="alert result-message"></div></td>
                        </tr>
                                 
                </table>
                   
                   </td>
                </tr>
         
                
                <tr class="form-required">
                     <th scope="row"><label><?php _e('Lot Price', 'propertylot'); ?>: </label><span style="color:red;">*</span></th>
                    <td> <input type="text"  name="lot_price" class="lotprice-input input-text" value="<?php echo esc_attr($lot_price); ?>"></td>
                </tr>
                
                <tr>
                 	<th scope="row"> <label><?php _e('House Size', 'propertylot'); ?>:</label></th>
                    <td> <input type="text" name="lot_house_size" class="lot_house_size-input input-text" value="<?php echo esc_attr($lot_house_size); ?>"></td>
                </tr>
              	<tr>
                     <th scope="row"><label><?php _e('Build Price', 'propertylot'); ?>:</label></th>
                    <td> <input type="text" name="lot_build_price" class="lot_build_price-input input-text" value="<?php echo esc_attr($lot_build_price); ?>"></td>
                </tr>
                
                 <tr>
                 	  <th scope="row"><label><?php _e('House Design', 'propertylot'); ?>:</label></th>
                    <td> <input type="text" name="lot_house_design" class="lot_house_design-input input-text" value="<?php echo esc_attr($lot_house_design); ?>"></td>
                 </tr>
              	 <tr>
                      <th scope="row"><label><?php _e('Exclusive Builder', 'propertylot'); ?>:</label></th>
                    <td> <input type="text" name="lot_exc_builder" class="lot_exc_builder-input input-text" value="<?php echo esc_attr($lot_exc_builder); ?>"></td>
                </tr>
                
                 <tr class="form-required">
                	  <th scope="row"><label><?php _e('Package Price', 'propertylot'); ?>:</label><span style="color:red;">*</span></th>
                    <td> <input type="text" name="lot_package_price" class="packageprice-input input-text" value="<?php echo esc_attr($lot_package_price); ?>"></td>
                 </tr>
              	 <tr>
                      <th scope="row"><label><?php _e('TH or H&L', 'propertylot'); ?>:</label></th>
                    <td> <input type="text" name="lot_th_hl" class="thorhl-input input-text" value="<?php echo esc_attr($lot_th_hl); ?>"></td>
                 </tr>
           
                <tr class="form-required">
                	 <th scope="row"><label><?php _e('Type', 'propertylot'); ?>:</label><span style="color:red;">*</span></th>
                    <td> <input type="text" name="lot_type" class="lottype-input input-text" value="<?php echo esc_attr($lot_type); ?>"></td>
                </tr>
                <tr>
                     <th scope="row"><label><?php _e('Legal Rep', 'propertylot'); ?>:</label></th>
                    <td> <input type="text" name="lot_legal_rep" class="legalrep-input input-text" value="<?php echo esc_attr($lot_legal_rep); ?>"></td>
                </tr>
            
              	
              
             	 <tr>
                     <th scope="row"><label><?php _e('Description', 'propertylot'); ?> :</label></th>
                    <td colspan="5">
                      <?php 
					  $settings = array( 'drag_drop_upload' => true,'textarea_rows' => 2 );
					  wp_editor($lot_description, 'lot_description', $settings);
          				?>
                    </td>
                  </tr>
                  
                
                <tr>
                 	  <th scope="row"><label><?php _e('Facade Image', 'propertylot'); ?>: </label></th>
                      <td> <input type="file" name="lot_facade_image" ><br />
                      	<?php
							if( !empty($lot_facade_image) )
							{?>
								<img width="160px" height="120px" src="<?php echo site_url(). "/".$lot_facade_image; ?>"  />
							<?php }
						 ?>
                      </td>
                </tr>
                <tr>
                	 <th><label><?php _e('Floor Plan', 'propertymap'); ?>:</label></th>
                	<td><input type="file" name="lot_floor_image"><br />
                    	<?php
							if( !empty($lot_floor_image) )
							{?>
								<img width="160px" height="120px" src="<?php echo site_url(). "/".$lot_floor_image; ?>"  />
							<?php }
						 ?> 
                        </td>   
                </tr>
              	
                 
                <tr>
                   <th scope="row"><label> <?php _e('Document Uploads', 'propertylot'); ?>: </label></th>
                  <td colspan="2">   
                
                    <div class="lot_form-horizontal">			
                        <div class="lot_form-group">					
                            <label class="col-sm-2 control-label" for="lot_txtbox1">Document Uploads  <span class="label-numbers">1</span></label>
                         
                           <div class="col-sm-10">                     
                                <label class="col-sm-2 control-label" style="margin-right:5px;" for="lot_txtbox1">File Title :</label>
                                <input class="lot_form-control" type="text" name="lot_file_title[]" id="lot_txtbox1"/>
                                   <br/>
                                <label class="col-sm-2 control-label" style="margin:0px 5px 0px 5px" for="lot_txtbox1">File Upload :</label>
                                <input class="lot_form-control" type="file" name="lot_documents[]" id="lot_txtbox1"/>
                                <a href="#" class="lot_add-txt">Add More</a>
                           </div>
                           
                        </div>	
                        
                       
                        
                    </div>
                </td>
               </tr>  
               
                <tr>
                	   <th scope="row"><label><?php _e('Lot Assignto Salesman', 'propertylot'); ?> </label></th>
                       <td>
							 <?php
                             
							
						$users = array_merge( get_users('role=sales_man') );
                             ?>
                             <div>
                                <select class="lot_assign_to-input input-text" name="lot_assign_to">
                       				<option value="0">Select User</option>
								 <?php
                                   for($i=0;$i<count($users);$i++)
                                   {?>
										  <option value="<?php echo $users[$i]->data->ID;?>"  <?php  if(  $users[$i]->data->ID == $lot_assign_to ){ echo "selected=selected";} ?>><?php echo ucfirst($users[$i]->data->user_login);?></option>
										   <?php
                                   }?>
                        
                        </select>
                            </div>
                    	</td>
                 </tr>
          
              </tbody>
        </table>	  
            
        <?php   submit_button( __( 'Add New Lot'), 'primary', 'add_lot', true, array( 'id' => 'add_lot' ) );  ?>
   </form>

</div>
<div class="clear"></div>