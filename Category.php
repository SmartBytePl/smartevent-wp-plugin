<?php

class Category
{
	private $id;
	private $name;
	private $parentName;

	public function __construct($id, $name, $parentName) {
		$this->id = $id;
		$this->name = $name;
		$this->parentName = $parentName;
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function getParentName(){
		return $this->parentName;
	}
}
