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

	/*$pull_quote_atts = shortcode_atts( array(
		'quote' => 'My Quote',
		'attribution' => 'Author',
	), $atts );*/



	$shost = get_option('eventparser_shost');
	$parser = new EventParser($shost, 'pl_PL');

	$output .= "<h2><strong>Kalendarium szkoleń</strong></h2>";
	$output .= "<form id='target' action=\"{$shost}/mycart/add\" enctype='text/plain'>";
	foreach($parser->getEvents() as $event)
	{
		$output .= "<div class='event'><input type=\"checkbox\" id=\"checkbox{$event['id']}\" name=\"id[]\" value=\"{$event['id']}\"><strong>{$event['name']} (".$parser->getCity($event).",".$parser->getDate($event).")</strong> ";
		$output .= "Ilość: <input type=\"number\" id=\"quantity{$event['id']}\"><br/>";
		$output .= "Zostało: {$event['on_hand']} ";
		$price = $event['price']/100;
		$output .= "Cena: $price PLN</div>";
	}
	//$output .= "<h3>Kwota na fakturze: 0</h3>";
	$output .= "<h2><strong>Formularz rejestracyjny<strong></h2>";
	$output .= "<div class='customer-data'><label for='customer_name'>Imię</label><input type='text' name='customer_name'><br>";
	$output .= "<label for='customer_surname'>Nazwisko</label><input type='text' name='customer_surname'><br>";
	$output .= "<label for='customer_phone'>Telefon</label><input type='text' name='customer_phone'><br>";
	$output .= "<label for='customer_email'>Email</label><input type='text' name='customer_email'></div> ";
	$output .= "<input type=\"submit\" value=\"Zarejestruj się i zapłać\" class='et_pb_button  et_pb_button_0 et_pb_module et_pb_bg_layout_light'>";
	$output .= "</form>";
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/before_submit.js"></script>';

	return $output;

}

add_shortcode( 'events', 'events_shortcode' );

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

add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );
