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
	$output .= "<table><tr><th>Nazwa</th><th>Miasto</th><th>Dostępne do</th><th>Cena</th><th>Ilość</th></tr>";
	foreach($parser->getEvents() as $event)
	{
		/*$output .= "<div class='event'><input type=\"checkbox\" id=\"checkbox{$event['id']}\" name=\"id[]\" value=\"{$event['id']}\" class='event_checkbox'><strong>{$event['name']} (".$parser->getCity($event).",".$parser->getDate($event).")</strong> ";
		$output .= "Ilość: <input type=\"number\" id=\"quantity{$event['id']}\" data-eventid='{$event['id']}'><br/>";
		$output .= "Zostało: {$event['on_hand']} ";
		$price = $event['price']/100;
		$output .= "Cena: $price PLN</div>";*/
		$output .= "<tr><td><input type=\"checkbox\" id=\"checkbox{$event['id']}\" name=\"id[]\" value=\"{$event['id']}\" class='event_checkbox'>{$event['name']}</strong></td>";
		$output .= "<td>{$parser->getCity($event)}</td>";
		$output .= "<td>{$parser->getDate($event)}</td>";
		$price = $event['price']/100;
		$output .= "<td>$price PLN</td>";
		$output .= "<td><input type=\"number\" id=\"quantity{$event['id']}\" data-eventid='{$event['id']}'></td></tr>";
	}
	$output .= "</table>";
	$output .= "<h2>Kupon</h2>";
	$output .= "<input type=\"text\" id=\"coupon\"/><br/>";
	$output .= "<p id='coupon_result'></p>";
	$output .= "<h2><strong>Formularz rejestracyjny<strong></h2>";
	$output .= "<div class='customer-data'><label for='customer_name'>Imię</label><input type='text' name='customer_name' required><br>";
	$output .= "<label for='customer_surname'>Nazwisko</label><input type='text' name='customer_surname' required><br>";
	$output .= "<label for='customer_phone'>Telefon</label><input type='text' name='customer_phone' required><br>";
	$output .= "<label for='customer_email'>Email</label><input type='text' name='customer_email' required></div> ";
	$output .= "<input type=\"checkbox\" required/>Akceptuję regulamin<br/>";
	$output .= "<p style='font-size: 20px;'>Kwota na fakturze <span id=\"invoice_cost\">0</span> zł z VAT<br/>";
	$output .= "<div class=\"errors\"></div><br/>";
	$output .= "<input type=\"submit\" id=\"submit_button\" value=\"Zarejestruj się i zapłać\" class='et_pb_button  et_pb_button_0 et_pb_module et_pb_bg_layout_light'>";
	$output .= "</form>";
	$output .= "<script>window.backend_host =\"".$shost."\";</script>";
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/js.cookie.js"></script>';
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/invite_cookie.js"></script>';
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/before_submit.js"></script>';
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/quantity_change.js"></script>';
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/calculate_cost.js"></script>';
	$output .= '<script src="'.plugin_dir_url( __FILE__ ).'js/coupon_result.js"></script>';
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
