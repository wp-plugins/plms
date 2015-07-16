<?php

/**
 * Uninstall
 *
 * Does delete the created tables and all the plugin options
 * when uninstalling the plugin
  */

if ( !defined( 'ABSPATH' ) ) exit;
// check if the plugin really gets uninstalled 
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit();

	global $wpdb;
	
	$wpdb->query( "DROP TABLE IF EXISTS `".$wpdb->prefix.'property'."`" );
    $wpdb->query( "DROP TABLE IF EXISTS `".$wpdb->prefix.'lot'."`" );
    $wpdb->query( "DROP TABLE IF EXISTS `".$wpdb->prefix.'lot_inquiry'."`" );
    $wpdb->query( "DROP TABLE IF EXISTS `".$wpdb->prefix.'property_resources'."`" );
    $wpdb->query( "DROP TABLE IF EXISTS `".$wpdb->prefix.'lot_resources'."`" );

?>