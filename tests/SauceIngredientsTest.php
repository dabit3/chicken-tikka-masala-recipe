<?php
declare(strict_types=1);

namespace Test;

use Ctm\Exception\AmountTooHighException;
use Ctm\Exception\AmountTooLowException;
use Ctm\Exception\MissingIngredientException;
use Ctm\Ingredients\BonelessAndSkinlessChickenThingsOrTenders;
use Ctm\Recipe;
use PHPUnit\Framework\TestCase;

class SauceIngredientsTest extends TestCase {

	public function testIngredientAmountTooLow(): void
	{
		$this->expectException(AmountTooLowException::class);

		(new Recipe)->addIngredientForSauce(new BonelessAndSkinlessChickenThingsOrTenders(10))
			->cook();
	}

	public function testIngredientAmountTooHigh(): void
	{
		$this->expectException(AmountTooHighException::class);

		(new Recipe)->addIngredientForSauce(new BonelessAndSkinlessChickenThingsOrTenders(100))
			->cook();
	}

	public function testMissingIngredient(): void
	{
		$this->expectException(MissingIngredientException::class);

		(new Recipe)->cook();
	}
}