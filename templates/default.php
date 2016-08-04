<?php

$output .= "<h2><strong>Kalendarium szkoleń</strong></h2>";
$output .= "<form id=\"{$params['id']}\" action=\"{$shost}/mycart/add\" enctype='text/plain'>";
	$output .= "<table><tr><th>Nazwa</th><th>Miasto</th><th>Dostępne do</th><th>Zostało</th><th>Cena</th><th>Ilość</th><th></th></tr>";
		foreach($events as $event)
		{
		$output .= "<tr><td><input type=\"checkbox\" id=\"checkbox{$event['id']}\" name=\"id[]\" value=\"{$event['id']}\" class='event_checkbox'>{$event['name']}</strong></td>";
			$output .= "<td>{$parser->getCity($event)}</td>";
			$output .= "<td>{$parser->getDate($event)}</td>";
			$output .= "<td>{$event['on_hand']}</td>";
			$price = $event['price']/100;
			$output .= "<td>$price PLN</td>";
			$output .= "<td><input type=\"number\" id=\"quantity{$event['id']}\" data-eventid='{$event['id']}'></td></tr>";
		$output .= "<td>";
			if($event['url'])
			$output .= "<a href=\"{$event['url']}\">Więcej</a>";
			$output .= "</td>";
		}
		$output .= "</table>";
	$output .= "<h2>Kupon</h2>";
	$output .= "<input type=\"text\" id=\"coupon\"/><br/>";
	$output .= "<p id='coupon_result'></p>";
	$output .= "<h2><strong>Formularz rejestracyjny<strong></h2>";
	$output .= "<div class='customer-data'><label for='customer_name'>Imię</label><input type='text' name='customer_name' required><br>";
	$output .= "<label for='customer_surname'>Nazwisko</label><input type='text' name='customer_surname' required><br>";
	$output .= "<label for='customer_phone'>Telefon</label><input type='text' name='customer_phone' required><br>";
	$output .= "<label for='customer_email'>Email</label><input type='text' name='customer_email' required><br>";

	$output .= "<label for='company'>Firma</label><input type='text' name='company'><br>";
	$output .= "<label for='street'>Ulica</label><input type='text' name='street'><br>";
	$output .= "<label for='postcode'>Kod pocztowy</label><input type='text' name='postcode'><br>";
	$output .= "<label for='city'>Miasto</label><input type='text' name='city'></div>";

	$output .= "<label for='additional_info'>Dodatkowe informacje</label><textarea name='additional_info' ></textarea><br/>";
	$output .= "<input type=\"checkbox\" required/>Akceptuję regulamin<br/>";
	$output .= "<p style='font-size: 20px;'>Kwota na fakturze <span id=\"invoice_cost\">0</span> zł z VAT<br/>";
		$output .= "<div class=\"errors\"></div><br/>";
	$output .= "<input type=\"submit\" id=\"submit_button\" value=\"Zarejestruj się i zapłać\" class='et_pb_button  et_pb_button_0 et_pb_module et_pb_bg_layout_light'>";
	$output .= "</form>";

?>
