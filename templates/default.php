<?php
	$output .= "<form id=\"{$params['id']}\" action=\"{$shost}/mycart/add\" enctype='text/plain'>";

	
	$first_in_promotion = [];
	/* @var Promotion $promotion */
	foreach($promotions as $promotion){
		if($promotion->isValid() && $promotion->isAllEventsPresent($variants) && !in_array($promotion->getId(),[32,33]))
			$first_in_promotion[$promotion->getId()] = false;
		else
			$first_in_promotion[$promotion->getId()] = true;
	}
	$output .= "<h2><strong>Kalendarium szkoleń</strong></h2>";
	$output .= "<table><tr><th>Nazwa</th><th>Miasto</th><th>Dostępne do</th><th>Zostało</th><th>Cena</th><th>Ilość</th><th></th></tr>";
	/* @var Event $event */
	foreach($events as $event)
	{
		if($event->getArchetype() != 'event')
			continue;
		/* @var Promotion $promotion */
		foreach($promotions as $promotion){
			if(!$first_in_promotion[$promotion->getId()]){
				if(in_array($event->getMasterVariantId(), $promotion->getVariants())){
					$first_in_promotion[$promotion->getId()] = true;
					$cityName = $parser->getEventByVariant($promotion->getVariants()[0])->getCity();
					$dates = $parser->getEventDates($promotion->getVariants());
					sort($dates);
					$firstDate = $dates[0];
					$lastDate = end($dates);
					$ids = $parser->getIdsFromVariants($promotion->getVariants());
					$output .= "<tr class=\"packet\">
							<td><input type=\"checkbox\" id=\"checkbox_promotion_{$event->getId()}\" data-promotion='{$promotion->getId()}' class='promotion_checkbox'>{$promotion->getName()}</td>
							<td>$cityName</td>
							<td>$firstDate - $lastDate</td>
							<td>{$parser->getMinOnHand($promotion->getVariants())}</td>
							<td id='promotion_price_{$promotion->getId()}'></td>
							<td><input type=\"number\" class='promotion_input' id='promotion_input_{$promotion->getId()}' data-variants='{$promotion->getVariantsJson()}'></td>
						</tr>
						<script>jQuery(function(){
							calculate_packet_cost('#promotion_price_{$promotion->getId()}',".json_encode($ids).");
						});
						</script>";
				}
			}
		}

		$output .= "<tr class=\"{$event->getArchetype()}\"><td><input type=\"checkbox\" id=\"checkbox{$event->getId()}\" data-variant='{$event->getMasterVariantId()}' name=\"id[]\" value=\"{$event->getId()}\" class='{$event->getArchetype()}_checkbox'>{$event->getName()}</strong></td>";
		$output .= "<td>{$event->getCity()}</td>";
		$output .= "<td>{$event->getDate()}</td>";
		$output .= "<td>{$event->getOnHand()}</td>";
		$output .= "<td>{$event->getPrice()} PLN</td>";
		$output .= "<td><input type=\"number\" id=\"quantity{$event->getId()}\" data-eventid='{$event->getId()}' data-variant='{$event->getMasterVariantId()}'></td>";
		$output .= "<td>";
		if($event->getUrl())
			$output .= "<a href=\"{$event->getUrl()}\">Więcej</a>";
		$output .= "</td></tr>";
	}
	$output .= "</table>";

	$output .= "<h2><strong>Produkty</strong></h2>";
	$output .= "<table><tr><th>Nazwa</th><th>Zostało</th><th>Cena</th><th>Ilość</th></tr>";
	/* @var Event $event */
	foreach($events as $event)
	{
		if($event->getArchetype() != 'bonus')
			continue;

		$output .= "<tr class=\"{$event->getArchetype()}\"><td><input type=\"checkbox\" id=\"checkbox{$event->getId()}\" data-variant='{$event->getMasterVariantId()}' name=\"id[]\" value=\"{$event->getId()}\" class='{$event->getArchetype()}_checkbox'>{$event->getName()}</strong></td>";
		$output .= "<td>{$event->getOnHand()}</td>";
		$output .= "<td>{$event->getPrice()} PLN</td>";
		$output .= "<td><input type=\"number\" id=\"quantity{$event->getId()}\" data-eventid='{$event->getId()}' data-variant='{$event->getMasterVariantId()}'></td>";
		$output .= "<td>";
		$output .= "</td></tr>";
	}
	$output .= "</table>";

	$output .= "<h2>Kupon</h2>";
	$output .= "<input type=\"text\" name=\"coupon\" id=\"coupon\"/><br/>";
	$output .= "<p id='coupon_result'></p>";

	$output .= "<h2>Lista uczestników</h2>";
	$output .= "<table class='trainees-table'>";
	$output .= "<thead><tr><th>Imię</th><th>Nazwisko</th><th>Email</th><th>Telefon</th></tr></thead><tbody>";
	foreach($events as $event)
	{
		if($event->getArchetype() == 'event')
			$output .= "<tr class='trainees event-{$event->getId()}'><td colspan='4'>{$event->getName()}</td></tr>";
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

