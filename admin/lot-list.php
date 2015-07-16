<?php

if (!defined('ABSPATH'))
    exit;

global $role_name2;
$user_data2 = plms_PROPERTY_LOT_current_user();
$userRole2 = $user_data2->roles;
if (isset($userRole2[0]))
    $role_name2 = $userRole2[0];
else
    $role_name2 = '';


if (!class_exists('WP_List_Table'))
{
    require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Lots_List_Table extends WP_List_Table
{
    function __construct()
    {
        global $page;

        
        parent::__construct(array(
            'singular' => 'lot',
            'plural' => 'lots',
            'ajax' => false));
    }
   

    function no_items()
    {
        if (isset($_GET['pro_id']) && !empty($_GET['pro_id']) && !empty($_GET['pro_title']))
        {
            _e('No Lot found of ' . sanitize_text_field($_GET['pro_title']) . " property", 'propertylot');
        } else
        {
            _e('No Lot found', 'propertylot');
        }
    }
    function column_default($item, $column_name)
    {

        switch ($column_name)
        {
            case 'ID':
            case 'lot_title':
            case 'lot_no':
            case 'lot_price':
            case 'lot_area':
            case 'lot_type':
            case 'lot_package_price':
            case 'lot_th_hl':
            case 'lot_legal_rep':
            case 'lot_status':
                return $item[$column_name];
            case 'lot_assign_to':
                return plms_get_username_byid($item[$column_name]);
            case 'lot_added_date':
                return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($item[$column_name])); 
            default:
                return print_r($item, true);

        }
    }
    function get_sortable_columns()
    {

        $sortable_columns = array(
            'ID' => array('ID', true),
            'lot_title' => array('lot_title', true),
            'lot_price' => array('lot_price', true),
            'lot_area' => array('lot_area', true),
            'lot_package_price' => array('lot_package_price', true),
            'lot_assign_to' => array('lot_assign_to', true),
            'lot_status' => array('lot_status', true),
            'lot_added_date' => array('lot_added_date', true));

        return $sortable_columns;
    }
    function get_columns()
    {
        global $role_name2;
        $sales_heade = '';
        if ($role_name2 != 'sales_manager' && $role_name2 != 'sales_man')
        {
            $sales_heade = '<input type="checkbox" />';
        }


        $columns = array(
            'cb' => $sales_heade,
            'ID' => __('ID', 'propertylot'),
            'lot_title' => __('Lot Title', 'propertylot'),
            'lot_no' => __('Lot No', 'propertylot'),
            'lot_price' => __('LotPrice', 'propertylot'),
            'lot_area' => __('Lot Area', 'propertylot'),
            'lot_type' => __('Lot Type', 'propertylot'),
            'lot_package_price' => __('Package Price', 'propertylot'),
            'lot_th_hl' => __('TH or H&L', 'propertylot'),
            'lot_legal_rep' => __('Legal Rep', 'propertylot'),
            'lot_assign_to' => __('Lot Assign To', 'propertylot'),
            'lot_status' => __('Lot Status', 'propertylot'),
            'lot_added_date' => __('Date', 'propertylot'));

        return $columns;
    }
    
    function usort_reorder($a, $b)
    {
        $orderby = (!empty($_REQUEST['orderby'])) ? sanitize_text_field($_REQUEST['orderby']) : 'ID'; //If no sort, default to Id
        $order = (!empty($_REQUEST['order'])) ? sanitize_text_field($_REQUEST['order']) : 'desc'; //If no order, default to desc
        $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
        return ($order === 'asc') ? $result : -$result; //Send final sort direction to usort
    }   
    
    function column_ID($item)
    {
        global $role_name2;
        if ($role_name2 == 'sales_manager')
        {
            $actions = array('edit' => sprintf('<a href="?page=%s&action=%s&lot=%s">' . __('Edit Lot', 'propertylot') . '</a>',
                    'edit_lot', 'edit', $item['ID']), );
        }
        elseif ($role_name2 == 'sales_man')
        {
            $user_ID = get_current_user_id();
            if ($item['lot_assign_to'] == $user_ID)
            {
                $actions = array('edit' => sprintf('<a href="?page=%s&action=%s&lot=%s">' . __('Edit Lot', 'propertylot') . '</a>','edit_lot', 'edit', $item['ID']), );
            }
        }
        else
        {
            $actions = array(
                'edit' => sprintf('<a href="?page=%s&action=%s&lot=%s">' . __('Edit', 'propertylot') . '</a>', 'edit_lot', 'edit', $item['ID']),
                'delete' => sprintf('<a href="?page=%s&action=%s&lot[]=%s">' . __('Delete', 'propertylot') . '</a>', sanitize_text_field($_REQUEST['page']),'delete', $item['ID']),
                );
        }
        return sprintf('%1$s %2$s', $item['ID'], $this->row_actions($actions));
    }
    
     function get_bulk_actions()
    {
        global $role_name2;
        $actions = array();
        if ($role_name2 != 'sales_manager' && $role_name2 != 'sales_man')
        {
            $actions = array('delete' => 'Delete');
        }
        return $actions;
    }


    function process_bulk_action()
    {      
       	 if( ((isset( $_GET['action'] ) && sanitize_text_field($_GET['action']) == 'delete' )) && isset($_GET['page']) && sanitize_text_field($_GET['page']) == 'list_lot' )
        {
          
            $redirect_url = add_query_arg(array('page' => 'list_lot'), admin_url('admin.php'));
            if (isset($_GET['lot']))
            {
                $action_on_id = sanitize_text_field($_GET['lot']);
            }
            else
            {
                $action_on_id = array();
            }

            if (count($action_on_id) > 0)
            {
                foreach ($action_on_id as $auto_id)
                {

                    $lot_assign_to_userid = plms_get_userid_from_lotid($auto_id);
                    $user_ID = get_current_user_id();
                    if ($lot_assign_to_userid == $user_ID || $user_ID == 1)
                    {
                        $args = array('ID' => $auto_id);
                        plms_delete_lots($args);
                    } else
                    {
                        $message = 6;
                    }
                
                }

                $redirect_url = add_query_arg(array('message' => '3'), $redirect_url);
                if ($message == 6)
                {
                    $redirect_url = add_query_arg(array('message' => '6'), $redirect_url);
                }
                wp_redirect($redirect_url);
                exit;
            }
            else
            {
                wp_redirect($redirect_url);
                exit;
            }

        }

    }
    
    function column_cb($item)
    {
        global $role_name2;
        if ($role_name2 != 'sales_manager' && $role_name2 != 'sales_man')
        {
            return sprintf('<input type="checkbox" name="lot[]" value="%s" />', $item['ID']);
        }
    }


   
    function plms_display_lots()
    {
        $search_title = isset($_GET['s']) ? plms_escape_attr($_GET['s']) : '';
        $global_search = array('search_title' => $search_title);

        $data = $this->plms_get_lots($global_search);
        return $data;
    }
    function plms_get_lots($args = array())
    {
      
        $filter_search = array();
        if (isset($_GET['pro_id']) && !empty($_GET['pro_id']))
        {
            $filter_search = array('field_name' => 'pro_id', 'field_value' => plms_escape_attr($_GET['pro_id']));
        }
        elseif (isset($_GET['field_name']) && !empty($_GET['field_name']))
        {
            if (isset($_GET['field_value']) && !empty($_GET['field_value']))
            {
                $filter_search = array('field_name' => sanitize_text_field($_GET['field_name']), 'field_value' => plms_escape_attr($_GET['field_value']));
            }
        }
        $result = plms_fetch_lots($args, $filter_search);
        return $result;
    }

    function extra_tablenav($which)
    {
        if ($which == "top")
        {
            $selected = 'selected="selected"';

            $html = '';
            $html .= '<div class="alignleft actions">';
            $html .= '<select name="field_name" id="field_name">';
            $html .= '<option value = ""> -- Select Any Field -- </option><option title="Lot Name" value="lot_title" ' . ((isset($_REQUEST['field_name']) &&
                sanitize_text_field($_REQUEST['field_name']) == 'lot_title') ? $selected : '') . '> Lot Title </option>';
            $html .= '<option title="Lot Number" value="lot_no"  ' . ((isset($_REQUEST['field_name']) && sanitize_text_field($_REQUEST['field_name']) ==
                'lot_no') ? $selected : '') . '>Lot Number</option>
						   <option title="Lot Type" value="lot_type"  ' . ((isset($_REQUEST['field_name']) && sanitize_text_field($_REQUEST['field_name']) ==
                'lot_type') ? $selected : '') . '>Lot Type</option>
						   <option title="Lot Status" value="lot_status"  ' . ((isset($_REQUEST['field_name']) && sanitize_text_field($_REQUEST['field_name']) ==
                'lot_status') ? $selected : '') . ' > Lot Status</option>
						     <option title="Lot Assignto" value="lot_assign_to"  ' . ((isset($_REQUEST['field_name']) && sanitize_text_field($_REQUEST['field_name']) ==
                'lot_assign_to') ? $selected : '') . ' > Lot Assignto</option>
						   <option title="Date" value="lot_added_date"  ' . ((isset($_REQUEST['field_name']) && sanitize_text_field($_REQUEST['field_name']) ==
                'lot_added_date') ? $selected : '') . '> Date</option>';
            $html .= '</select>';
            if (isset($_GET['pro_id']) && !empty($_GET['pro_id']) && !empty($_GET['pro_title']))
            {
                $html .= '<input type="hidden" name="pro_id" value="' . sanitize_text_field($_GET['pro_id']) . '">';
                $html .= '<input type="hidden" name="pro_title" value="' . sanitize_text_field($_GET['pro_title']) . '">';
            }
            $html .= '<span id="txtbox"></span>';
            $html .= '<input type="hidden" value="list_lots" id="flag_page">';
            $html .= '<input type="hidden"  id="plms_pro_id" ' . ((isset($_GET['pro_id']) && sanitize_text_field($_GET['pro_id']) != '') ? 'value="' . sanitize_text_field($_GET['pro_id']) .
                '"' : '') . '>';
            $html .= '<input type="hidden"  id="seleected_type" ' . ((isset($_GET['field_name']) && sanitize_text_field($_GET['field_name']) != '') ?
                'value="' . sanitize_text_field($_GET['field_name']) . '"' : '') . '>';
            $html .= '<input type="hidden"  id="selected_value" ' . ((isset($_GET['field_value']) && sanitize_text_field($_GET['field_value']) != '') ?
                'value="' . sanitize_text_field($_GET['field_value']) . '"' : '') . '>';
            $html .= '	<input type="submit" value="' . __('Filter', 'propertylot') .
                '" class="button" id="post-query-submit" name="filter">';
            $html .= '</div>';

            echo $html;
        }
    }

    function plms_prepare_items()
    {
        global $role_name2;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array(
            $columns,
            $hidden,
            $sortable);

        $per_page = $this->get_items_per_page('users_per_page', 20);
        $current_page = $this->get_pagenum();
        
        $data = $this->plms_display_lots();

        usort($data,  array(&$this, 'usort_reorder'));
        $total_items = count($data);
        if ($role_name2 != 'sales_manager' && $role_name2 != 'sales_man')
        {
            $this->process_bulk_action();
        }

        
        $this->items = array_slice($data, (($current_page - 1) * $per_page), $per_page);
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)));
        
    }
}
global $LotsListTable;

$LotsListTable = new Lots_List_Table;
$LotsListTable->plms_prepare_items();

?>
<div class="wrap">
    
    <h2>
	   <?php

        if (isset($_GET['pro_id']) && !empty($_GET['pro_id']) && !empty($_GET['pro_title']))
        {
            _e('Lots of ' . sanitize_text_field($_GET['pro_title']), 'propertylot');
        }
        else
        {
            _e('Lots Listing', 'propertylot');
        }
        
        ?>
      </h2>
   	<?php

    $html = '';
    if (isset($_GET['message']) && !empty($_GET['message']))
    {
    
        if (sanitize_text_field($_GET['message']) == '1')
        {
            $html .= '<div class="updated settings-error" id="setting-error-settings_updated">
    							<p><strong>' . __("Lot saved successfully.", 'propertylot') . '</strong></p>
    						</div>';
        }
        elseif (sanitize_text_field($_GET['message']) == '2')
        {
            $html .= '<div class="updated" id="message">
    						<p><strong>' . __("Lot changed successfully.", 'propertylot') . '</strong></p>
    					</div>';
        }
        elseif (sanitize_text_field($_GET['message']) == '3')
        {
            $html .= '<div class="updated" id="message">
    					<p><strong>' . __("Lot deleted successfully.", 'propertylot') . '</strong></p>
    				</div>';
        }
        elseif (sanitize_text_field($_GET['message']) == '4')
        {
            $html .= '<div class="updated" id="message">
    				<p><strong>' . __("Lot added successfully.", 'propertylot') . '</strong></p>
    			</div>';
        } 
        elseif (sanitize_text_field($_GET['message']) == '5')
        {
            $html .= '<div class="updated" id="message">
    			<p><strong>' . __("You don't have access to edit this lots.", 'propertylot') . '</strong></p>
    		</div>';
        }
        elseif (sanitize_text_field($_GET['message']) == '6')
        {
            $html .= '<div class="updated" id="message">
    		<p><strong>' . __("You don't have access to delete this lots.", 'propertylot') . '</strong></p>
    	</div>';
        }
    }
    echo $html;
   
    ?>
  
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="lot-filter" method="get" >
    	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo sanitize_text_field($_REQUEST['page']); ?>" />
         
        <!-- Search Title -->
        <?php $LotsListTable->search_box('search', 'search_id'); ?>
        
        <!-- Now we can render the completed list table -->
         <?php $LotsListTable->display(); ?>
        
    </form>
	        
</div>