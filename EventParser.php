<?php

/**
 * Class EventParser
 * @author Krzysztof Wędrowicz
 */
class EventParser
{
    private $raw_array = [];
    private $events = [];
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
        $this->setLanguage($language);
    }

    /**
     * Download events data from backendServer in json format
     * @return mixed
     */
    private function getEventsJson()
    {
        $ch = curl_init();
        $url = $this->host."/myapi/events.json";
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
        $this->events = $this->raw_array;
        for($i = 0; $i < count($this->events); $i++){
            for ($j = 0; $j < count($this->events[$i]['categories']); $j++){
                $this->events[$i]['categories'][$j]['name'] = $this->events[$i]['categories'][$j]['translations'][$language];
                unset($this->events[$i]['categories'][$j]['translations']);
                $this->events[$i]['categories'][$j]['parent']['name'] = $this->events[$i]['categories'][$j]['parent']['translations'][$language];
                unset($this->events[$i]['categories'][$j]['parent']['translations']);
            }
            $translations_params = ['name', 'description'];
            foreach ($translations_params as $param){
                $this->events[$i][$param] = $this->events[$i]['translations'][$language][$param];
            }
            unset($this->events[$i]['translations']);
        }
    }

    /**
     * Get array of all events
     * @return array
     */
    public function getEvents(){
        return $this->events;
    }

    /**
     * Get associative array of all cities assigned to any event
     * @return array
     */
    public function getCities(){
        return $this->matchCategoryItems('city');
    }

    /**
     * Get name of all events
     * @param $event
     *
     * @return mixed
     */
    public function getCity($event){
        foreach ($event['categories'] as $category) {
            if($category['parent']['code'] == 'city')
                return $category['name'];
        }
    }

    /**
     * Get associative array of all trainers assigned to any event
     * @return array
     */
    public function getTrainers(){
        return $this->matchCategoryItems('coach');
    }

    /**
     * Get associative array of all event type assigned to any event
     * @return array
     */
    public function getEventTypes(){
        return $this->matchCategoryItems('event_type');
    }

    /**
     * Get associative array of all event groups assigned to any event
     * @return array
     */
    public function getEventGroups(){
        return $this->matchCategoryItems('event_group');
    }

    /**
     * Get array of dates when events take place
     * @return array
     */
    public function getEventDates(){
        $dates = [];
        foreach ($this->events as $event)
        {
            $dates[] = substr($event['available_until'],0,10);
        }
        return array_values(array_unique($dates));
    }

    /**
     * Get date of an event
     * @param $event
     *
     * @return string
     */
    public function getDate($event){
        return substr($event['available_until'],0,10);
    }

    /**
     * Get all events from particular date
     * @param $date
     *
     * @return array
     */
    public function getByDate($date){
        $events = [];
        foreach ($this->events as $event)
        {
            if(substr($event['available_until'],0,10) == $date)
                $events[] = $date;
        }
        return $events;
    }

    /**
     * Universal method for getting all events matching category id (category is city, event type,
     * group, trainer)
     * @param $category_id
     *
     * @return array
     */
    public function getByCategory($category_id){
        $events_array = [];
        foreach ($this->events as $event)
        {
            foreach ($event['categories'] as $category){
                if($category['id'] == $category_id){
                    $events_array[] = $event;
                    break;
                }
            }
        }
        return $events_array;
    }

    /**
     * Return all items matching parent category code
     * @param $parent_code
     *
     * @return array
     */
    private function matchCategoryItems($parent_code){
        $items = [];
        foreach ($this->events as $event)
        {
            foreach ($event['categories'] as $category) {
                if($category['parent']['code'] == $parent_code)
                    $items[$category['id']] = $category['name'];
            }
        }
        return $items;
    }
}
