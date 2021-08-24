<?php
declare(strict_types=1);

namespace Test;

use Ctm\Exception\AmountTooHighException;
use Ctm\Exception\AmountTooLowException;
use Ctm\Exception\MissingIngredientException;
use Ctm\Ingredients\BonelessAndSkinlessChickenThingsOrTenders;
use Ctm\Ingredients\BrownSugar;
use Ctm\Ingredients\Butter;
use Ctm\Ingredients\ChickenTikkaMasalaSeasoning;
use Ctm\Ingredients\FinelyDicedLargeOnion;
use Ctm\Ingredients\FinelyDicedSmallOnion;
use Ctm\Ingredients\FinelyGratedGarlic;
use Ctm\Ingredients\FinelyGratedGinger;
use Ctm\Ingredients\GroundRedChiliPowder;
use Ctm\Ingredients\HeavyCream;
use Ctm\Ingredients\KashmiriChili;
use Ctm\Ingredients\MediumFreshDicedTomato;
use Ctm\Ingredients\MincedFreshGinger;
use Ctm\Ingredients\MincedGarlic;
use Ctm\Ingredients\PlainYogurt;
use Ctm\Ingredients\Salt;
use Ctm\Ingredients\TomatoPuree;
use Ctm\Ingredients\Water;
use Ctm\Recipe;
use PHPUnit\Framework\TestCase;

class RecipeIngredientsTest extends TestCase {

	public function testIngredientAmountTooLow(): void
	{
		$this->expectException(AmountTooLowException::class);

		(new Recipe)->addIngredientForMarinade(new BonelessAndSkinlessChickenThingsOrTenders(10))
			->prepareMarinade();
	}

	public function testIngredientAmountTooHigh(): void
	{
		$this->expectException(AmountTooHighException::class);

		(new Recipe)->addIngredientForMarinade(new BonelessAndSkinlessChickenThingsOrTenders(100))
			->prepareMarinade();
	}

	public function testMissingIngredient(): void
	{
		$this->expectException(MissingIngredientException::class);

		(new Recipe)->prepareMeal();
	}
	
	public function testReplacementIngredient()
	{
		$this->expectException(AmountTooHighException::class);
		$this->expectExceptionMessageMatches('/^Too much of Finely Diced Large Onion/');

		(new Recipe)->addIngredientForSauce(new Butter(6))
			->addIngredientForSauce(new FinelyDicedLargeOnion(10)) // default is FinelyDicedSmallOnion
			->prepareSauce();
	}
	
	public function testAllIngredients(): void
	{
		$recipe = new Recipe;

		$recipe->addIngredientForMarinade(new BonelessAndSkinlessChickenThingsOrTenders(28))
			->addIngredientForMarinade(new PlainYogurt(1))
			->addIngredientForMarinade(new MincedGarlic(1 + 1/2))
			->addIngredientForMarinade(new MincedFreshGinger(1))
			->addIngredientForMarinade(new ChickenTikkaMasalaSeasoning(3.5))
			->addIngredientForMarinade(new KashmiriChili(1/3))
			->addIngredientForMarinade(new Salt(1))
			->addIngredientForMarinade(new Butter(3));

		$this->assertTrue($recipe->prepareMarinade());

		$recipe->addIngredientForSauce(new Butter(6))
			->addIngredientForSauce(new FinelyDicedSmallOnion(3))
			->addIngredientForSauce(new FinelyGratedGarlic(1 + 1/2))
			->addIngredientForSauce(new FinelyGratedGinger(1))
			->addIngredientForSauce(new ChickenTikkaMasalaSeasoning(3.5))
			->addIngredientForSauce(new TomatoPuree(13))
			->addIngredientForSauce(new MediumFreshDicedTomato(2))
			->addIngredientForSauce(new GroundRedChiliPowder(1/3))
			->addIngredientForSauce(new Salt(1))
			->addIngredientForSauce(new HeavyCream(1 + 1/4))
			->addIngredientForSauce(new BrownSugar(1))
			->addIngredientForSauce(new Water(1/4));

		$this->assertTrue($recipe->prepareSauce());

		$this->assertTrue($recipe->prepareMeal());
	}

}