<?php

require_once 'Event.php';
require_once 'Promotion.php';

/**
 * Class EventParser
 * @author Krzysztof Wędrowicz
 */
class EventParser
{
    private $raw_array = [];
    private $events_array = [];
	private $bonuses_array = [];
	private $promotions_array = [];
	private $events = [];
	private $bonuses = [];
	private $promotions = [];
    private $supportedLanguages = ['en_US', 'pl_PL'];
    private $host;

    /**
     * EventParser constructor.
     * Takes two parameters. First is the host address of SmartEvent backend server.
     * The second one is one of supported language.
     * @param $host
     * @param $language
     */
    public function __construct($host, $language)
    {
        $this->host = $host;
        $this->raw_array = json_decode($this->getEventsJson(), true);
	    $this->bonuses_array = json_decode($this->getBonusesJson(), true);
	    $this->promotions_array = json_decode($this->getPromotionsJson(), true);
        $this->setLanguage($language);
    }

    /**
     * Download events data from backendServer in json format
     * @return mixed
     */
    private function getEventsJson()
    {
        $ch = curl_init();
        $url = $this->host."/myapi/training.json";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        return $server_output;
    }

	private function getBonusesJson()
	{
		$ch = curl_init();
		$url = $this->host."/myapi/bonus.json";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		return $server_output;
	}

    public function getPromotionsJson()
    {
	    $ch = curl_init();
	    $url = $this->host."/myapi/promotions.json";
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $server_output = curl_exec($ch);
	    return $server_output;
    }

    /**
     * Set default language and trim other translations
     * @param $language
     *
     * @throws Exception
     */
    private function setLanguage($language){
        if(!in_array($language, $this->supportedLanguages))
            throw new Exception("Language '$language' is not supported");
        $this->events_array = $this->raw_array;
        /*for($i = 0; $i < count($this->events_array); $i++){
        	if(array_key_exists("categories", $this->events_array[$i])){
		        for ($j = 0; $j < count($this->events_array[$i]['categories']); $j++){
			        $this->events_array[$i]['categories'][$j]['name'] = $this->events_array[$i]['categories'][$j]['translations'][$language];
			        unset($this->events_array[$i]['categories'][$j]['translations']);
			        $this->events_array[$i]['categories'][$j]['parent']['name'] = $this->events_array[$i]['categories'][$j]['parent']['translations'][$language];
			        unset($this->events_array[$i]['categories'][$j]['parent']['translations']);
		        }
	        }
            $translations_params = ['name', 'description'];
            foreach ($translations_params as $param){
                $this->events_array[$i][$param] = $this->events_array[$i]['translations'][$language][$param];
            }
            unset($this->events_array[$i]['translations']);
        }*/
        $this->simplifyTranslations($this->events_array, $language);
	    $this->simplifyTranslations($this->bonuses_array, $language);
        foreach($this->events_array as $event)
        	$this->events[] = new Event($event, 'event');
	    foreach($this->bonuses_array as $bonus)
	    	$this->events[] = new Event($bonus, 'bonus');
	    foreach($this->promotions_array as $promotion)
	    	$this->promotions[] = new Promotion($promotion);
    }

    /**
     * Get array of all events
     * @return array
     */
    public function getEvents(){
    	return $this->events;
    }

    public function getBonuses(){
	    return $this->bonuses;
    }

    public function getVariants(){
    	$variants = [];
    	/* @var Event $event */
    	foreach($this->events as $event){
    		$variants[] = $event->getMasterVariantId();
	    }
	    return $variants;
    }

    public function getPromotions(){
    	return $this->promotions;
    }

    public function getCities(){
    	$cities = [];
	    /* @var Event $event */
	    foreach($this->events as $event){
	    	$cities[] = $event->getCity();
	    }
	    return array_unique($cities);
    }

	/**
	 * Get array of dates when events take place
	 *
	 * @param null $variants
	 *
	 * @return array
	 */
    public function getEventDates($variants = null){
        $dates = [];
	    if($variants){
		    foreach ($variants as $variant)
		    {
			    $dates[] = $this->getEventByVariant($variant)->getDate();
		    }
	    }
	    else{
		    /* @var Event $event */
		    foreach ($this->events as $event)
		    {
			    $dates[] = $event->getDate();
		    }
	    }
	    $dates = array_values(array_unique($dates));
	    sort($dates);
        return $dates;
    }

    public function getFirstAndLastDate($which = 'first')
    {
	    $dates = $this->getEventDates();
	    $passed = [];
	    $first_date = null;
	    $last_date = null;
	    foreach($dates as $d)
	    {
	    	$date = new \DateTime($d);
	    	if(!$passed){
	    		$first_date = $date;
	    		$last_date = clone $first_date;
	    		$last_date->modify('+30 days');
		    }
		    if($date <= $last_date)
		        $passed[] = $date;
	    }
	    $last = count($passed) > 0 ? $passed[count($passed) - 1] : null;
	    if($which == 'first')
	    	return $first_date;
	    else
	    	return $last;
    }

    /**
     * Get all events from particular date
     * @param $date
     *
     * @return array
     */
    public function findByDate($date){
        $events = [];
	    /* @var Event $event */
	    foreach ($this->events as $event)
        {
            if($event->getDate() == $date)
                $events[] = $event;
        }
        return $events;
    }

    public function findByCategoryName(array $categoryNames, $method){
    	$events = [];
	    /* @var Event $event */
        foreach ($this->events as $event)
        {
        	$count = 0;
        	/* @var Category $category */
            foreach ($event->getCategories() as $category){
                if(in_array($category->getName(), $categoryNames))
                	$count++;
            }
            if($method == 'OR'){
            	if($count > 0)
            		$events[] = $event;
            }
            else{
            	if($count == count($categoryNames))
            		$events[] = $event;
            }
        }
        $this->events = $events;
        return $events;
    }

    public function exclude(array $excludeArray){
	    for($i = 0; $i < count($this->events); $i++){
		    if(in_array($this->events[$i]->getId(), $excludeArray))
				unset($this->events[$i]);
	    }
	    $this->events = array_values($this->events);
	    return $this->getEvents();
    }

    public function getEventById($id){
	    /* @var Event $event */
	    foreach ($this->events as $event){
		    if($event->getId() == $id){
			    return $event;
		    }
	    }
	    return null;
    }

	public function getEventByVariant($variant){
		/* @var Event $event */
		foreach ($this->events as $event){
			if($event->getMasterVariantId() == $variant){
				return $event;
			}
		}
		return null;
	}

	public function getMinOnHand($variants){
		$onHand = 10000;
		foreach ($variants as $variant){
			$onHand = min($this->getEventByVariant($variant)->getOnHand(), $onHand);
		}
		return $onHand;
	}

	public function getIdsFromVariants($variants){
		$ids = [];
		foreach($variants as $variant){
			$ids[] = $this->getEventByVariant($variant)->getId();
		}
		return $ids;
	}
    
    private function simplifyTranslations(&$array, $language){
	    for($i = 0; $i < count($array); $i++){
		    if(array_key_exists("categories", $array[$i])){
			    for ($j = 0; $j < count($array[$i]['categories']); $j++){
				    $array[$i]['categories'][$j]['name'] = $array[$i]['categories'][$j]['translations'][$language];
				    unset($array[$i]['categories'][$j]['translations']);
				    $array[$i]['categories'][$j]['parent']['name'] = $array[$i]['categories'][$j]['parent']['translations'][$language];
				    unset($array[$i]['categories'][$j]['parent']['translations']);
			    }
		    }
		    $translations_params = ['name', 'description'];
		    foreach ($translations_params as $param){
			    $array[$i][$param] = $array[$i]['translations'][$language][$param];
		    }
		    unset($array[$i]['translations']);
	    }
    }
}
