<?php

require_once 'Category.php';

class Event
{
	private $id;
	private $isEnabled;
	private $date;
	private $name;
	private $description;
	private $price;
	private $onHand;
	private $url;
	private $address;
	private $categories;
	private $masterVariantId;
	private $archetype;

	public function __construct(array $event, $archetype) {
		$this->id = $event['id'];
		$this->isEnabled = $event['enabled'];
		$this->date = substr($event['available_until'],0,10);
		$this->name = $event['name'];
		$this->description = $event['description'];
		$this->price = $event['price']/100.0;
		$this->onHand = $event['on_hand'];
		$this->url = isset($event['url']) ? $event['url'] : null;
		$this->address = isset($event['address']) ? $event['address'] : null;
		$this->masterVariantId = $event['master_variant_id'];
		$this->archetype = $archetype;
		if(array_key_exists("categories", $event)){
			/* @var Category $category */
			foreach($event['categories'] as $category) {
				$c = new Category($category);
				$this->categories[] = $c;
			}
		}
	}

	public function getId(){
		return $this->id;
	}

	public function getArchetype(){
		return $this->archetype;
	}

	public function IsEnabled(){
		return $this->isEnabled;
	}

	public function getDate(){
		return $this->date;
	}

	public function getDateText (){
		$dzien = substr($this->date,8,2);
		$dzien_tyg = substr($this->date,0,10);
		$miesiac = substr($this->date,5,2);

		$miesiac_pl = array(
			'01' => 'stycznia',
			'02' => 'luty',
			'03' => 'marca',
			'04' => 'kwietnia',
			'05' => 'maja',
			'06' => 'czerwca',
			'07' => 'lipca',
			'08' => 'sierpnia',
			'09' => 'września',
			'10' => 'października',
			'11' => 'listopada',
			'12' => 'grudnia'
		);

		$dzien_tyg_pl = array(
			'Monday' => 'Poniedziałek',
			'Tuesday' => 'Wtorek',
			'Wednesday' => 'Środa',
			'Thursday' => 'Czwartek',
			'Friday' => 'Piątek',
			'Saturday' => 'Sobota',
			'Sunday' => 'Niedziela',
		);

		return '<span class="day">' . $dzien . '</span>' . '<span class="month">' . $miesiac_pl[$miesiac] . '</span>' . '<span class="day-num">'. $dzien_tyg_pl[strftime("%A", strtotime($dzien_tyg))] .'<span>';
	}

	public function getName(){
		return $this->name;
	}

	public function getDescription(){
		return $this->description;
	}

	public function getPrice(){
		return $this->price;
	}

	public function getOnHand(){
		return $this->onHand;
	}

	public function getUrl(){
		return $this->url;
	}

	public function getAddress(){
		return $this->address;
	}

	public function getCity(){
		/* @var Category $category */
		foreach($this->categories as $category){
			if($category->getParentCode() == 'city'){
				return $category->getName();
			}
		}
		return null;
	}

	/**
	 * @param $name
	 *
	 * @return Category|null
	 */
	public function getCategoryByName($name){
		/* @var Category $category */
		foreach ($this->categories as $category){
			if($category->getName() == $name)
				return $category;
		}
		return null;
	}

	/**
	 * @param $id
	 *
	 * @return Category|null
	 */
	public function getCategoryById($id){
		/* @var Category $category */
		foreach ($this->categories as $category){
			if($category->getId() == $id)
				return $category;
		}
		return null;
	}

	public function getCategories(){
		return $this->categories;
	}

	/**
	 * @param $parentName
	 *
	 * @return array
	 */
	public function getCategoriesByParentName($parentName){
		$categories = [];
		if($this->categories){
			/* @var Category $category */
			foreach ($this->categories as $category){
				if($category->getParentName() == $parentName){
					$categories[] = $category;
				}
			}
		}
		return $categories;
	}

	public function getMasterVariantId()
	{
		return $this->masterVariantId;
	}
}
