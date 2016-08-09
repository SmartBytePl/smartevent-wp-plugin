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

	public function __construct(array $event) {
		$this->id = $event['id'];
		$this->isEnabled = $event['enabled'];
		$this->date = substr($event['available_until'],0,10);
		$this->name = $event['name'];
		$this->description = $event['description'];
		$this->price = $event['price']/100.0;
		$this->onHand = $event['on_hand'];
		$this->url = $event['url'];
		$this->address = $event['address'];
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

	public function IsEnabled(){
		return $this->isEnabled;
	}

	public function getDate(){
		return $this->date;
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
}
