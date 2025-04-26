<?php

namespace App\Support\Data\Import;

class ImportAttribute {
    private $name;
    private $columnNames;

    private $resolvedName;

	private $defaultValue;

	private $required;

    private $index = -1;

    public function __construct(string $name, array $aliases, bool $required = false, $defaultValue = NULL) {
        $this->name;
        $this->columnNames = $aliases;
		$this->required = $required;
		$this->defaultValue = $defaultValue;
    }

    public static function simple($name): ImportAttribute {
        return new ImportAttribute($name, [$name]);
    }

    public function matches(string $columnName) {
        return in_array($columnName, $this->columnNames);
    }

	public function isFound() {
		return $this->index > -1;
	}
    


	/**
	 * @return mixed
	 */
	public function getIndex() {
		return $this->index;
	}
	
	/**
	 * @param mixed $index 
	 * @return self
	 */
	public function setIndex($index): self {
		$this->index = $index;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getResolvedName() {
		return $this->resolvedName;
	}
	
	/**
	 * @param mixed $resolvedName 
	 * @return self
	 */
	public function setResolvedName($resolvedName): self {
		$this->resolvedName = $resolvedName;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue() {
		return $this->defaultValue;
	}

	/**
	 * @return mixed
	 */
	public function isRequired() {
		return $this->required;
	}
}