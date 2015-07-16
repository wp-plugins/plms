<?php
if (!defined('ABSPATH'))
    exit;
    
    
if (!class_exists('WP_List_Table'))
{
    require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class My_Property_List extends WP_List_Table
{


    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => __('Property', 'propertylot'), //singular name of the listed records
            'plural' => __('Properties', 'propertylot'), //plural name of the listed records
            'ajax' => false //does this table support ajax?

            ));
    }

    function no_items()
    {
        _e('No Property Found, dude.');
    }

    function column_default($item, $column_name)
    {

        switch ($column_name)
        {
            case 'ID':
            case 'pro_title':
            case 'pro_description':
            case 'pro_total_lots':
            case 'pro_cons_timeline':
            case 'pro_start_price':
            case 'pro_site_area':
                return $item[$column_name];
            case 'pro_added_date':
                return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($item[$column_name])); // getting date and time format from general settings
            case 'property_lots':
                return '<a href="admin.php?page=list_lot&pro_id=' . $item['ID'] . '&pro_title=' . $item['pro_title'] .
                    '"><button type="button">View Lots</button></a>';
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'ID' => array('ID', false),
            'pro_title' => array('pro_title', false),
            'pro_total_lots' => array('pro_total_lots', false),
            'pro_start_price' => array('pro_start_price', false),
            'pro_added_date' => array('pro_added_date', false));
        return $sortable_columns;
    }

    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'ID' => __('ID', 'propertylot'),
            'pro_title' => __('Title', 'propertylot'),
            'pro_description' => __('Description', 'propertylot'),
            'pro_total_lots' => __('Total Lots', 'propertylot'),
            'pro_cons_timeline' => __('Construction Timeline', 'propertylot'),
            'pro_start_price' => __('Start Price', 'propertylot'),
            'pro_site_area' => __('Site Area', 'propertylot'),
            'pro_added_date' => __('Registered Date', 'propertylot'),
            'property_lots' => __('Property Lots', 'propertylot'));
        return $columns;
    }

    function usort_reorder($a, $b)
    {
        // If no sort, default to title
        $orderby = (!empty($_GET['orderby'])) ? sanitize_text_field($_GET['orderby']) : 'ID';
        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? sanitize_text_field($_GET['order']) : 'asc';
        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);
        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

    function column_ID($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=%s&property=%s">Edit</a>', 'add_property', 'edit', $item['ID']),
            'delete' => sprintf('<a href="?page=%s&action=%s&property[]=%s">Delete</a>', sanitize_text_field($_REQUEST['page']), 'delete', $item['ID']),
            );

        return sprintf('%1$s %2$s', $item['ID'], $this->row_actions($actions));
    }

    function get_bulk_actions()
    {
        $actions = array('delete' => 'Delete');
        return $actions;
    }

    function process_bulk_action()
    {
        if (((isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'delete')) && isset($_GET['page']) && sanitize_text_field($_GET['page']) ==
            'list_property')
        {
            $redirect_url = add_query_arg(array('page' => 'list_property'), admin_url('admin.php'));
            if (isset($_GET['property']))
            {
                $action_on_id = $_GET['property'];
            } else
            {
                $action_on_id = array();
            }

            if (count($action_on_id) > 0)
            {
                foreach ($action_on_id as $auto_id)
                {
                    $args = array('ID' => $auto_id);
                    plms_delete_property($args);
                    plms_delete_lot_by_property($args);
                }
                $redirect_url = add_query_arg(array('message' => '3'), $redirect_url);
                wp_redirect($redirect_url);
                exit;
            } else
            {
                wp_redirect($redirect_url);
                exit;
            }
        }
    }


    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="property[]" value="%s" />', $item['ID']);
    }


    function plms_get_property_record()
    {
        $search_title = isset($_GET['s']) ? plms_escape_attr($_GET['s'] ): '';
        $global_search = array('search_title' => $search_title);

        $data = $this->plms_get_property($global_search);
        return $data;
    }

    function plms_get_property($args = array())
    {
        $filter_search = array();
        if (isset($_GET['field_name']) && !empty($_GET['field_name']))
        {
            if (isset($_GET['field_value']) && !empty($_GET['field_value']))
            {
                $filter_search = array('field_name' => sanitize_text_field($_GET['field_name']), 'field_value' => plms_escape_attr($_GET['field_value']));
            }
        }

        $result = plms_fetch_property($args, $filter_search);
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
            $html .= '<option value = ""> -- Select Any Field -- </option><option title="Property Title" value="pro_title" ' . ((isset
                ($_REQUEST['field_name']) && sanitize_text_field($_REQUEST['field_name']) == 'pro_title') ? $selected : '') . '> Property Title </option>';
            $html .= '<option title="Property Lot" value="pro_total_lots"  ' . ((isset($_REQUEST['field_name']) && sanitize_text_field($_REQUEST['field_name'] ==
                'pro_total_lots')) ? $selected : '') . '>Property Lot</option>
						   <option title="Property Start Price" value="pro_start_price"  ' . ((isset($_REQUEST['field_name']) && sanitize_text_field($_REQUEST['field_name']) =='pro_start_price') ? $selected : '') . '>Property Start Price</option>
						   <option title="Site Area" value="pro_site_area"  ' . ((isset($_REQUEST['field_name']) && sanitize_text_field($_REQUEST['field_name']) =='pro_site_area') ? $selected : '') . ' >Site Area</option>
						   <option title="Registered Date" value="pro_added_date"  ' . ((isset($_REQUEST['field_name']) && sanitize_text_field($_REQUEST['field_name']) =='pro_added_date') ? $selected : '') . '> Registered Date</option>';
            $html .= '</select>';
            $html .= '<span id="txtbox"></span>';
            $html .= '<input type="hidden" value="list_property" id="flag_page">';
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
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array(
            $columns,
            $hidden,
            $sortable);


        // $per_page = 5;
        $per_page = $this->get_items_per_page('users_per_page', 10);
        $current_page = $this->get_pagenum();
       
        $data = $this->plms_get_property_record();
        usort($data, array(&$this, 'usort_reorder'));
        $total_items = count($data);
        $this->process_bulk_action();

        $this->items = array_slice($data, (($current_page - 1) * $per_page), $per_page);

        $this->set_pagination_args(array('total_items' => $total_items, //WE have to calculate the total number of items
                'per_page' => $per_page //WE have to determine how many items to show on a page
                ));

    }

} //class
global $myListTable;

$myListTable = new My_Property_List();

$myListTable->plms_prepare_items();

?>
<div class="wrap">
    
       <h2><?php _e('Property Listing', 'propertylot');?></h2>

        <?php
        
        $html = '';
        if (isset($_GET['message']) && !empty($_GET['message']))
        {
            if (sanitize_text_field($_GET['message']) == '1')
            {
                $html .= '<div class="updated settings-error" id="setting-error-settings_updated">
        							<p><strong>' . __("Property saved successfully.", 'propertylot') . '</strong></p>
        						</div>';
            }
            elseif (sanitize_text_field($_GET['message']) == '2')
            {
                $html .= '<div class="updated" id="message">
        						<p><strong>' . __("Property changed successfully.", 'propertylot') . '</strong></p>
        					</div>';
            } 
            elseif (sanitize_text_field($_GET['message']) == '3')
            {
                $html .= '<div class="updated" id="message">
        					<p><strong>' . __("Property deleted successfully.", 'propertylot') . '</strong></p>
        				</div>';
            }
            elseif (sanitize_text_field($_GET['message']) == '4')
            {
                $html .= '<div class="updated" id="message">
        				<p><strong>' . __("Property added successfully.", 'propertylot') . '</strong></p>
        			</div>';
            }
        }
        echo $html;
        
        ?>

    <form id="property-filter" method="get">
        
            <input type="hidden" name="page" value="<?php echo sanitize_text_field($_REQUEST['page']); ?>" />
		
        <!-- Search Title -->
        <?php $myListTable->search_box('search', 'search_id'); ?>
        
        <!-- Now we can render the completed list table -->
         <?php $myListTable->display(); ?>
        
    </form>
    
</div>