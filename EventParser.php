<?php

class EventParser
{
    private $raw_array = [];
    private $events = [];
    private $supportedLanguages = ['en_US', 'pl_PL'];
    private $host;

    public function __construct($host)
    {
        $this->host = $host;
        $this->raw_array = json_decode($this->getEventsJson(), true);
    }

    private function getEventsJson()
    {
        $ch = curl_init();
        $url = $this->host."/myapi/events.json";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        return $server_output;
    }
    
    public function setLanguage($language){
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

    public function getEvents(){
        /*$array = [];
        foreach($this->events as $event){
            if($event['archetype'] == '')
        }*/
        return $this->events;
    }

    public function getCities(){
        $cities = [];
        foreach ($this->events as $event)
        {
            foreach ($event['categories'] as $category) {
                if($category['parent']['code'] == 'city')
                    $cities[$category['id']] = $category['name'];
            }
        }
        return $cities;
    }

    public function getCity($event){
        foreach ($event['categories'] as $category) {
            if($category['parent']['code'] == 'city')
                return $category['name'];
        }
    }

    public function getTrainers(){
        $trainers = [];
        foreach ($this->events as $event)
        {
            foreach ($event['categories'] as $category) {
                if($category['parent']['code'] == 'coach')
                    $trainers[$category['id']] = $category['name'];
            }
        }
        return $trainers;
    }
    
    public function getEventTypes(){
        $types = [];
        foreach ($this->events as $event)
        {
            foreach ($event['categories'] as $category) {
                if($category['parent']['code'] == 'event_type')
                    $types[$category['id']] = $category['name'];
            }
        }
        return $types;
    }

    public function getEventGroups(){
        $groups = [];
        foreach ($this->events as $event)
        {
            foreach ($event['categories'] as $category) {
                if($category['parent']['code'] == 'event_group')
                    $groups[$category['id']] = $category['name'];
            }
        }
        return $groups;
    }

    public function getEventDates(){
        $dates = [];
        foreach ($this->events as $event)
        {
            $dates[] = substr($event['available_until'],0,10);
        }
        return array_values(array_unique($dates));
    }

    public function getDate($event){
        return substr($event['available_until'],0,10);
    }

    public function getByDate($date){
        $events = [];
        foreach ($this->events as $event)
        {
            if(substr($event['available_until'],0,10) == $date)
                $events[] = $date;
        }
        return $events;
    }

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
}
