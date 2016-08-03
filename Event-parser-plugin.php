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
		'city' => null,
		'coach' => null,
		'type' => null,
		'group' => null,
		'template' => 'default'
	), $atts );



	$shost = get_option('eventparser_shost');
	$parser = new EventParser($shost, 'pl_PL');

	$keys = [];
	if($params['city'])
		$keys[] = array_search( $params['city'], $parser->getCities() );
	if($params['coach'])
		$keys[] = array_search($params['coach'], $parser->getTrainers());
	if($params['type'])
		$keys[] = array_search($params['type'], $parser->getEventTypes());
	if($params['group'])
		$keys[] = array_search($params['group'], $parser->getEventGroups());

	$array = [];
	foreach($keys as $key)
		if($key > 0) $array[] = $key;

	if(count($array) > 0)
		$events = $parser->getByCategories($array);
	else
		$events = $parser->getEvents();
	usort($events, "cmp");

	include("templates/{$params['template']}.php");

	$output .= "<script>window.backend_host =\"".$shost."\";</script>";
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/js.cookie.js"></script>';
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/invite_cookie.js"></script>';
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/before_submit.js"></script>';
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/quantity_change.js"></script>';
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/calculate_cost.js"></script>';
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/coupon_result.js"></script>';
	return $output;

}

function event_date_shortcode( $atts ) {

	$output = '';

	$params = shortcode_atts( array(
		'city' => null,
		'coach' => null,
		'type' => null,
		'group' => null,
		'sort' => 'asc',

	), $atts );



	$shost = get_option('eventparser_shost');
	$parser = new EventParser($shost, 'pl_PL');

	$keys = [];
	if($params['city'])
		$keys[] = array_search( $params['city'], $parser->getCities() );
	if($params['coach'])
		$keys[] = array_search($params['coach'], $parser->getTrainers());
	if($params['type'])
		$keys[] = array_search($params['type'], $parser->getEventTypes());
	if($params['group'])
		$keys[] = array_search($params['group'], $parser->getEventGroups());

	$array = [];
	foreach($keys as $key)
		if($key > 0) $array[] = $key;

	if(count($array) > 0)
		$events = $parser->getByCategories($array);
	else
		$events = $parser->getEvents();
	if($params['sort'] == 'asc')
		usort($events, "cmp");
	else
		usort($events, "rcmp");

	if(count($events) < 1)
		return "";
	else
		return $parser->getDate($events[0]);
}

add_shortcode( 'events', 'events_shortcode' );
add_shortcode( 'event-date', 'event_date_shortcode');

function eventparser_admin() {
	include('eventparser_import_admin.php');
}

function eventparser_admin_actions() {
	add_options_page("Event Parser", "Event Parser", 1, "Event Parser", "eventparser_admin");
}

add_action('admin_menu', 'eventparser_admin_actions');

function wpdocs_theme_name_scripts() {
	wp_enqueue_style( 'eventparser_main', plugin_dir_url( __FILE__ ).'css/eventparser_main.css');
	//wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}

function cmp($a, $b)
{
	return $a['available_until'] < $b['available_until'];
}

function rcmp($a, $b)
{
	return $a['available_until'] > $b['available_until'];
}

add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );
