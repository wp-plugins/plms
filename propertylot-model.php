<?php
	if ( !defined( 'ABSPATH' ) ) exit;
 	 	
	function __construct()
	{		
	}
	
    		
	/**
	 * Escape Tags & Slashes
	 */
	function plms_escape_attr($data)
    {
		return esc_attr(stripslashes($data));
	}
	
	/**
	 * Strip Slashes From Array
	 */
	function plms_escape_slashes_deep($data = array(), $flag=false, $limited = false)
    {
		if( $flag != true )
        {
			
			$data = plms_nohtml_kses($data);
			
		} else {
			
			if( $limited == true ) {
				$data = wp_kses_post( $data );
			}
			
		}
		$data = stripslashes_deep($data);
		return $data;
	}
	
	
	/**
	 * Strip Html Tags 
	 */
	function plms_nohtml_kses($data = array())
    {
		if ( is_array($data) )
        {
			
			$data = array_map('plms_nohtml_kses', $data);
			
		}
        elseif ( is_string( $data ) )
        {
			
			$data = wp_filter_nohtml_kses($data);
		}
		
		return $data;
	}
	
    
  function plms_PROPERTY_LOT_current_user()
  {
   		global $current_user;
		return $current_user;
   }
	
	function plms_delete_lot_by_property($args = array())
	{
		global $wpdb;
		if(isset($args['ID']) && !empty($args['ID']))
		{
			$sql='DELETE FROM '.PLMS_LOT_TABLE.' WHERE pro_id = "'.$args['ID'].'"';
			$wpdb->query( $sql );
		}
	}
	function plms_fetch_gallary()
	{ 
   		global $wpdb;
		$result = $wpdb->get_results("select id,name from ".$wpdb->prefix."bwg_gallery",ARRAY_A);
		return $result; 
	}
	
	
	function plms_fetch_property( $args=array(), $filter_search)
	{
		 global $wpdb;
		 
		 
		$sql = "select * from ".PLMS_PROPERTY_TABLE."  WHERE 1=1";
		
		if(isset($args['search_title']) && !empty($args['search_title']))
		{
			$sql .= " AND ID like '%" . $args['search_title'] . "%' OR pro_title like '%" . $args['search_title'] . "%' OR pro_description like '%" . $args['search_title'] . "%' OR pro_total_lots like '%" . $args['search_title'] . "%'  OR pro_cons_timeline like '%" . $args['search_title'] . "%'  OR 	pro_start_price like '%" . $args['search_title'] . "%' OR 	pro_site_area like '%" . $args['search_title'] . "%'";
		}
		else if(isset($filter_search['field_value']) && !empty($filter_search['field_value']))
		{
			
				$sql .= " AND ".$filter_search['field_name']." = '" . $filter_search['field_value']. "'";
		}
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		return $result;
	}
	
	function plms_fetch_lots( $args=array(), $filter_search)
	{
		global $wpdb;
	
		$sql = "SELECT * FROM ".PLMS_LOT_TABLE." WHERE 1=1";
				
		if(isset($args['search_title']) && !empty($args['search_title']))
		{
			$sql .= " AND lot_title like '%" . $args['search_title'] . "%' OR lot_no like '%" . $args['search_title'] . "%' OR lot_description like '%" . $args['search_title'] . "%' OR lot_area like '%" . $args['search_title'] . "%' OR lot_status like '%" . $args['search_title'] . "%' OR lot_price like '%" . $args['search_title'] . "%'  OR lot_type like '%" . $args['search_title'] . "%'  OR lot_package_price like '%" . $args['search_title'] . "%'  ";
		}
		
		else if(isset($filter_search['field_value']) && !empty($filter_search['field_value']))
		{
			
				$sql .= " AND ".$filter_search['field_name']." = '" . $filter_search['field_value']. "'";
		}
   
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		return $result;
	}
	
	function plms_fetch_property_by_filter( $filter )
	{
		global $wpdb;
		if(isset($filter) && !empty($filter))
		{
			$sql = "SELECT DISTINCT(".$filter.") FROM ".PLMS_PROPERTY_TABLE." WHERE 1=1";
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
			return $result;
		}
				
	}
	
	function plms_fetch_lots_by_filter( $filter, $pro_id )
	{
		global $wpdb;
		if(isset($filter) && !empty($filter))
		{
			$sql = "SELECT DISTINCT(".$filter.") FROM ".PLMS_LOT_TABLE." WHERE 1=1";
			if( isset($pro_id) && !empty($pro_id) )
			{
				$sql .= " AND pro_id=$pro_id";
			}
		
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
			return $result;
		}
	}
	
	function plms_delete_property($args = array())
	{
		global $wpdb;
		if(isset($args['ID']) && !empty($args['ID']))
		{
			$sql='DELETE FROM '.PLMS_PROPERTY_TABLE.' WHERE ID = "'.$args['ID'].'"';
			$wpdb->query( $sql );
		}
	}
	
	function plms_delete_lots( $args = array() )
	{ 
   		global $wpdb;
		if(isset($args['ID']) && !empty($args['ID']))
		{
			$sql='DELETE FROM '.PLMS_LOT_TABLE.' WHERE ID = "'.$args['ID'].'"';
			$wpdb->query( $sql );
		}
	}
	
	
	
	function plms_fetch_property_resources_by_id( $id )
	{
		 global $wpdb;
		 $result = $wpdb->get_results("select * from ". PLMS_PROPERTY_RESOURCES_TABLE." where pro_id=$id",ARRAY_A);
		 return $result; 
	}
	function plms_fetch_lot_resources_by_id( $id )
	{
		 global $wpdb;
		 $result = $wpdb->get_results("select * from ". PLMS_LOT_RESOURCES_TABLE." where lot_id=$id",ARRAY_A);
		 return $result; 
	}
	
	
	function plms_get_lot_facade_image_bylotid( $id )
	{
		 global $wpdb;
		 $result = $wpdb->get_row("select * from ". PLMS_LOT_TABLE." where ID=$id",ARRAY_A);
		 return $result; 
	}
	
	function plms_get_lot_floor_image_bylotid($id)
	{
		 global $wpdb;
		 $result = $wpdb->get_row("select * from ". PLMS_LOT_TABLE." where ID=$id",ARRAY_A);
		 return $result; 
	}
	
	
	function plms_get_username_byid($id)
	{
		 global $wpdb;
		 $table=$wpdb->prefix.'users';
		 $result = $wpdb->get_row("select user_login from $table where ID=$id",ARRAY_A);
		 return ucfirst($result['user_login']); 
	}
	
	
	function plms_get_userid_from_lotid($lotid)
	{
		 global $wpdb;
		 $result = $wpdb->get_row("select lot_assign_to from ". PLMS_LOT_TABLE." where ID=$lotid",ARRAY_A);
		 return $result['lot_assign_to']; 
	}
?>