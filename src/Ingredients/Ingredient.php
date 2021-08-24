<?php
declare(strict_types=1);

namespace Ctm\Ingredients;

abstract class Ingredient {

	final public function getName(): string
	{
		return \implode(" ", preg_split('/(?=[A-Z])/', (new \ReflectionClass($this))->getShortName(), -1, PREG_SPLIT_NO_EMPTY));
	}

	abstract public function getAmount(): int|float;

}