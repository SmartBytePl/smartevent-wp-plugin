<?php

	$output .= "<h2><strong>Kalendarium szkoleń</strong></h2>";
	$output .= "<form id=\"{$params['id']}\" action=\"{$shost}/mycart/add\" enctype='text/plain'>";
	$output .= "<table><tr><th>Nazwa</th><th>Miasto</th><th>Dostępne do</th><th>Zostało</th><th>Cena</th><th>Ilość</th><th></th></tr>";
	/* @var Event $event */
	foreach($events as $event)
	{
		$output .= "<tr class=\"event\"><td><input type=\"checkbox\" id=\"checkbox{$event->getId()}\" name=\"id[]\" value=\"{$event->getId()}\" class='event_checkbox'>{$event->getName()}</strong></td>";
		$output .= "<td>{$event->getCity()}</td>";
		$output .= "<td>{$event->getDate()}</td>";
		$output .= "<td>{$event->getOnHand()}</td>";
		$output .= "<td>{$event->getPrice()} PLN</td>";
		$output .= "<td><input type=\"number\" id=\"quantity{$event->getId()}\" data-eventid='{$event->getId()}'></td>";
		$output .= "<td>";
		if($event->getUrl())
			$output .= "<a href=\"{$event->getUrl()}\">Więcej</a>";
		$output .= "</td></tr>";
		/*$output .= "<tr class='trainee'><td></td>
					<td><input type='text' name='name[]' placeholder='Imię'></td>
					<td><input type='text' name='surname[]' placeholder='Nazwisko'></td>
					<td><input type='text' name='phone[]' placeholder='Telefon'></td>
					<td><input type='text' name='email[]' placeholder='Email'></td><td></td><td></td></tr>";
		*/
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
	$output .= "<label for='nip'>Nip</label><input type='text' name='nip'><br>";
	$output .= "<label for='street'>Ulica</label><input type='text' name='street'><br>";
	$output .= "<label for='postcode'>Kod pocztowy</label><input type='text' name='postcode'><br>";
	$output .= "<label for='city'>Miasto</label><input type='text' name='city'></div>";

	$output .= "<label for='additional_info'>Dodatkowe informacje</label><textarea name='additional_info' ></textarea><br/>";
	$output .= "<input type=\"checkbox\" required/>Akceptuję regulamin<br/>";
	$output .= "<p style='font-size: 20px;'>Kwota na fakturze <span id=\"invoice_cost\">0</span> zł z VAT<br/>";
	$output .= "<div class=\"errors\"></div><br/>";
	$output .= "<input type=\"submit\" id=\"submit_button\" value=\"Zarejestruj się i zapłać\" class='et_pb_button  et_pb_button_0 et_pb_module et_pb_bg_layout_light'>";
	$output .= "</form>";

