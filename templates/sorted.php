<?php
	$output .= "<form id=\"{$params['id']}\" action=\"{$shost}/mycart/add\" enctype='text/plain'>";

	$output .= "<h2><strong>Promocje</strong></h2>";
	$output .= "<table><tr><th>Nazwa</th><th></th></tr>";
	/* @var Promotion $promotion */
	foreach($promotions as $promotion){
	if($promotion->isValid() && $promotion->isAllEventsPresent($variants))
		$output .= "<tr><td>{$promotion->getName()}</td><td><a href='#' class='promotion_button' id='promotion_button_{$promotion->getId()}' data-variants='{$promotion->getVariantsJson()}'>KUP</a></td></tr>";
	}

	$output .= "<h2><strong>Kalendarium szkoleń</strong></h2>";
	$output .= "<table><tr><th>Nazwa</th><th>Miasto</th><th>Dostępne do</th><th>Zostało</th><th>Cena</th><th>Ilość</th><th></th></tr>";

	$cities = $parser->getCities();
	$cityEvents = [];
	foreach($cities as $city)
		$cityEvents[$city] = [];
	/* @var Event $event */
	foreach($events as $event){
		$cityEvents[$event->getCity()][] = $event;
	}
	foreach($cities as $city)
		usort($cityEvents[$event->getCity()], 'cmp');
	uasort($cityEvents, 'cityCmp');

	foreach($cityEvents as $city => $events){
		if(count($events) > 0){
			$first_date = $events[0]->getDate();
			$last_date = end($events)->getDate();
			$output .= "<tr><td colspan='7'>{$city} ({$first_date} - {$last_date})</td></tr>";
		}
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
		}
	}
	$output .= "</table>";
	$output .= "<h2>Kupon</h2>";
	$output .= "<input type=\"text\" id=\"coupon\"/><br/>";
	$output .= "<p id='coupon_result'></p>";

	$output .= "<h2>Lista uczestników</h2>";
	$output .= "<table class='trainees-table'>";
	$output .= "<thead><tr><th>Imię</th><th>Nazwisko</th><th>Email</th><th>Telefon</th></tr></thead><tbody>";
	foreach($cityEvents as $city => $events) {
		foreach ( $events as $event ) {
			$output .= "<tr class='trainees event-{$event->getId()}'><td colspan='4'>{$event->getName()}</td></tr>";
		}
	}
	$output .= "</tbody></table>";

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

