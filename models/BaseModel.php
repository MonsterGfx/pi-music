<?php

/**
 * A base class for models (to force them to implement toArray)
 */
abstract class BaseModel extends Model {

	/**
	 * Return this model's values as an array
	 * @return array
	 */
	abstract public function toArray();
}