<?php
/*
Plugin Name: Event Parser Plugin
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Krzysztof Wędrowicz
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

require_once 'EventParser.php';

function events_shortcode( $atts ) {

	$output = '';

	$params = shortcode_atts( array(
		'categories' => null,
		'method' => 'OR',
		'template' => 'default',
		'id' => 'form1',
		'sort' => 'ASC'
	), $atts );

	$shost = get_option('eventparser_shost');
	$parser = new EventParser($shost, 'pl_PL');

	$categories_name_array = array_filter(explode(',', $params['categories']));


	if(!empty($categories_name_array)){
		$events = $parser->findByCategoryName($categories_name_array, $params['method']);
	}
	else{
		$events = $parser->getEvents();
	}


	if($params['sort'] == 'ASC')
		usort($events, "cmp");
	else
		usort($events, "rcmp");


	include("templates/{$params['template']}.php");

	$output .= "<script>
					window.backend_host =\"".$shost."\";
					jQuery(function(){
						quantity_change('".$params['id']."');
						calculate_cost('".$params['id']."');
						before_submit('".$params['id']."');
					});
				</script>";
	return $output;

}

function event_date_shortcode( $atts ) {

	$output = '';

	$params = shortcode_atts( array(
		'categories' => null,
		'method' => 'OR',
		'sort' => 'ASC'
	), $atts );

	$shost = get_option('eventparser_shost');
	$parser = new EventParser($shost, 'pl_PL');

	$categories_name_array = array_filter(explode(',', $params['categories']));


	if(!empty($categories_name_array)){
		$events = $parser->findByCategoryName($categories_name_array, $params['method']);
	}
	else{
		$events = $parser->getEvents();
	}

	if($params['sort'] == 'ASC')
		usort($events, "cmp");
	else
		usort($events, "rcmp");

	if(count($events) < 1)
		return null;
	else
		return $events[0]->getDate();
}

function event_range_date_shortcode($atts){

	$params = shortcode_atts( array(
		'categories' => null,
		'which' => 'first',
		'method' => 'OR',
	), $atts );

	$shost = get_option('eventparser_shost');
	$parser = new EventParser($shost, 'pl_PL');

	$categories_name_array = array_filter(explode(',', $params['categories']));


	if(!empty($categories_name_array)){
		$parser->findByCategoryName($categories_name_array, $params['method']);
	}
	else{
		$parser->getEvents();
	}

	return $parser->getFirstAndLastDate(new DateTime(), $params['which']);
}



add_shortcode( 'events', 'events_shortcode' );
add_shortcode( 'event-date', 'event_date_shortcode');
add_shortcode( 'events-daterange', 'event_range_date_shortcode');

function eventparser_admin() {
	include('eventparser_import_admin.php');
}

function eventparser_admin_actions() {
	add_options_page("Event Parser", "Event Parser", 1, "Event Parser", "eventparser_admin");
}

add_action('admin_menu', 'eventparser_admin_actions');

function wpdocs_theme_name_scripts() {
	wp_enqueue_style( 'eventparser_main', plugin_dir_url( __FILE__ ).'css/eventparser_main.css');
	wp_enqueue_script( 'before_submit', plugin_dir_url( __FILE__ ). '/js/before_submit.js');
	wp_enqueue_script( 'quantity_change', plugin_dir_url( __FILE__ ). '/js/quantity_change.js');
	wp_enqueue_script( 'calculate_cost', plugin_dir_url( __FILE__ ). '/js/calculate_cost.js');
	wp_enqueue_script( 'coupon_result', plugin_dir_url( __FILE__ ). '/js/coupon_result.js');
	wp_enqueue_script( 'js.cookie', plugin_dir_url( __FILE__ ). '/js/js.cookie.js');
}

function cmp(Event $a, Event $b)
{
	return $a->getDate() < $b->getDate();
}

function rcmp(Event $a, Event $b)
{
	return $a->getDate() > $b->getDate();
}

add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );
