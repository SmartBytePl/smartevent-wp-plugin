<?php

class Category
{
	private $id;
	private $name;
	private $code;
	private $parentName;
	private $parentCode;
	private $parentId;

	public function __construct($category) {
		if(!is_array($category))
			throw new Exception('Not an array');
		$this->id = $category['id'];
		$this->name = $category['name'];
		$this->code = $category['code'];
		if($category['parent']){
			$this->parentId = $category['parent']['id'];
			$this->parentName = $category['parent']['name'];
			$this->parentCode = $category['parent']['code'];
		}
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function getCode(){
		return $this->code;
	}

	public function getParentId(){
		return $this->parentId;
	}

	public function getParentName(){
		return $this->parentName;
	}

	public function getParentCode(){
		return $this->parentCode;
	}
}
