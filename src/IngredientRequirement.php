<?php
declare(strict_types=1);

namespace Ctm;

class IngredientRequirement {

	public function __construct(
		private string $ingredient_class,
		private int|float $min,
		private int|float|null $max = null,
		private ?IngredientRequirement $replacementIngredient = null
	){}

	public function getIngredientClass(): string
	{
		return $this->ingredient_class;
	}

	public function getMin(): float|int
	{
		return $this->min;
	}

	public function getMax(): float|int|null
	{
		return $this->max;
	}

	public function getReplacementIngredient(): ?IngredientRequirement
	{
		return $this->replacementIngredient;
	}

}