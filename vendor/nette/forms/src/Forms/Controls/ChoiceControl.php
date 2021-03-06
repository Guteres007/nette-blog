<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Nette\Forms\Controls;

use Nette;


/**
 * Choice control that allows single item selection.
 *
 * @property   array $items
 * @property-read mixed $selectedItem
 */
abstract class ChoiceControl extends BaseControl
{
	/** @var bool */
	public $checkAllowedValues = TRUE;

	/** @var array */
	private $items = array();


	public function __construct($label = NULL, array $items = NULL)
	{
		parent::__construct($label);
		if ($items !== NULL) {
			$this->setItems($items);
		}
	}


	/**
	 * Loads HTTP data.
	 * @return void
	 */
	public function loadHttpData()
	{
		$this->value = $this->getHttpData(Nette\Forms\Form::DATA_TEXT);
		if ($this->value !== NULL) {
			if (is_array($this->disabled) && isset($this->disabled[$this->value])) {
				$this->value = NULL;
			} else {
				$this->value = key(array($this->value => NULL));
			}
		}
	}


	/**
	 * Sets selected item (by key).
	 * @param  string|int
	 * @return static
	 * @internal
	 */
	public function setValue($value)
	{
		if ($this->checkAllowedValues && $value !== NULL && !array_key_exists((string) $value, $this->items)) {
			$set = Nette\Utils\Strings::truncate(implode(', ', array_map(function ($s) { return var_export($s, TRUE); }, array_keys($this->items))), 70, '...');
			throw new Nette\InvalidArgumentException("Value '$value' is out of allowed set [$set] in field '{$this->name}'.");
		}
		$this->value = $value === NULL ? NULL : key(array((string) $value => NULL));
		return $this;
	}


	/**
	 * Returns selected key.
	 * @return string|int
	 */
	public function getValue()
	{
		return array_key_exists($this->value, $this->items) ? $this->value : NULL;
	}


	/**
	 * Returns selected key (not checked).
	 * @return string|int
	 */
	public function getRawValue()
	{
		return $this->value;
	}


	/**
	 * Is any item selected?
	 * @return bool
	 */
	public function isFilled()
	{
		return $this->getValue() !== NULL;
	}


	/**
	 * Sets items from which to choose.
	 * @param  array
	 * @param  bool
	 * @return static
	 */
	public function setItems(array $items, $useKeys = TRUE)
	{
		$this->items = $useKeys ? $items : array_combine($items, $items);
		return $this;
	}


	/**
	 * Returns items from which to choose.
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}


	/**
	 * Returns selected value.
	 * @return mixed
	 */
	public function getSelectedItem()
	{
		$value = $this->getValue();
		return $value === NULL ? NULL : $this->items[$value];
	}


	/**
	 * Disables or enables control or items.
	 * @param  bool|array
	 * @return static
	 */
	public function setDisabled($value = TRUE)
	{
		if (!is_array($value)) {
			return parent::setDisabled($value);
		}

		parent::setDisabled(FALSE);
		$this->disabled = array_fill_keys($value, TRUE);
		if (isset($this->disabled[$this->value])) {
			$this->value = NULL;
		}
		return $this;
	}

}
