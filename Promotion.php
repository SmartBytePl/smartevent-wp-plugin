<?php

class Promotion {

	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var array
	 */
	private $variants;

	/**
	 * @var boolean
	 */
	private $valid = false;

	public function __construct($promotion) {
		if(!is_array($promotion))
			throw new Exception('Not an array');
		$this->id = $promotion['id'];
		$this->name = $promotion['name'];
		$this->variants = [];
		foreach($promotion['rules'] as $rule) {
			if($rule['type'] == 'contains_product')
				$this->variants[] = $rule['configuration']['variant'];
		}
		if(count($promotion['rules']) == count($this->variants))
			$this->valid = true;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @return array
	 */
	public function getVariants() {
		return $this->variants;
	}

	/**
	 * @param array $variants
	 */
	public function setVariants( $variants ) {
		$this->variants = $variants;
	}

	public function getVariantsJson(){
		return json_encode($this->variants);
	}

	/**
	 * @return boolean
	 */
	public function isValid() {
		return $this->valid;
	}

	/**
	 * @param boolean $valid
	 */
	public function setValid( $valid ) {
		$this->valid = $valid;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	public function isAllEventsPresent($events)
	{
		foreach($this->variants as $variant)
		{
			if(!in_array($variant, $events))
				return false;
		}
		return true;
	}

}
