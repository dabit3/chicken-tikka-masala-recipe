<?php
declare(strict_types=1);

namespace Ctm;

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
use Ctm\Ingredients\Ingredient;
use Ctm\Ingredients\KashmiriChili;
use Ctm\Ingredients\MediumFreshDicedTomato;
use Ctm\Ingredients\MincedFreshGinger;
use Ctm\Ingredients\MincedGarlic;
use Ctm\Ingredients\PlainYogurt;
use Ctm\Ingredients\Salt;
use Ctm\Ingredients\ThickenedCream;
use Ctm\Ingredients\TomatoPuree;
use Ctm\Ingredients\TomatoSauce;
use Ctm\Ingredients\Water;

final class Recipe {

	private const MARINADE = 'marinade';
	private const SAUCE = 'sauce';

	/** @var array{string: IngredientRequirement[]}  */
	private array $requirements;

	/** @var array{string: Ingredient[]}  */
	private array $ingredients = [
		self::SAUCE => [],
		self::MARINADE => [],
	];

	private bool $marinadePrepared = false;
	private bool $saucePrepared = false;

	public function __construct()
	{
		$this->requirements = [
			self::MARINADE => [
				new IngredientRequirement(BonelessAndSkinlessChickenThingsOrTenders::class, 28),
				new IngredientRequirement(PlainYogurt::class, 1),
				new IngredientRequirement(MincedGarlic::class, 1 + 1/2),
				new IngredientRequirement(MincedFreshGinger::class, 1),
				new IngredientRequirement(ChickenTikkaMasalaSeasoning::class, 3.5),
				new IngredientRequirement(
					KashmiriChili::class, 1/3, null,
					new IngredientRequirement(GroundRedChiliPowder::class, 1/4)
				),
				new IngredientRequirement(Salt::class, 1),
				new IngredientRequirement(Butter::class, 3),
			],
			self::SAUCE => [
				new IngredientRequirement(Butter::class, 5, 8),
				new IngredientRequirement(
					FinelyDicedSmallOnion::class, 3, null,
					new IngredientRequirement(FinelyDicedLargeOnion::class, 1, 1.5)
				),
				new IngredientRequirement(FinelyGratedGarlic::class, 1 + 1/2),
				new IngredientRequirement(FinelyGratedGinger::class, 1),
				new IngredientRequirement(ChickenTikkaMasalaSeasoning::class, 3.5,),
				new IngredientRequirement(
					TomatoPuree::class, 12, 14,
					new IngredientRequirement(TomatoSauce::class, 12, 14)
				),
				new IngredientRequirement(MediumFreshDicedTomato::class, 2),
				new IngredientRequirement(
					KashmiriChili::class, 1/3, 1/2,
					new IngredientRequirement(GroundRedChiliPowder::class, 1/3, 1/2)
				),
				new IngredientRequirement(Salt::class, 1),
				new IngredientRequirement(
					HeavyCream::class, 1 + 1/4, null,
					new IngredientRequirement(ThickenedCream::class, 1 + 1/4)
				),
				new IngredientRequirement(BrownSugar::class, 1),
				new IngredientRequirement(Water::class, 1/4),
			],
		];
	}

	public function addIngredientForMarinade(Ingredient $ingredient): Recipe
	{
		$this->ingredients[self::MARINADE][\get_class($ingredient)] = $ingredient;

		return $this;
	}

	public function addIngredientForSauce(Ingredient $ingredient): Recipe
	{
		$this->ingredients[self::SAUCE][\get_class($ingredient)] = $ingredient;

		return $this;
	}

	public function prepareMeal(): bool
	{
		$this->prepareMarinade();

		$this->prepareSauce();

		$this->finish();

		return true;
	}

	public function prepareMarinade(): bool
	{
		if ( ! $this->marinadePrepared) {
			$this->validateIngredients(self::MARINADE);

			// @todo really prepare it
			$this->marinadePrepared = true;
		}

		return $this->marinadePrepared;
	}

	public function prepareSauce(): bool
	{
		if ( ! $this->saucePrepared) {
			$this->validateIngredients(self::SAUCE);

			// @todo really prepare it

			$this->saucePrepared = true;
		}

		return $this->saucePrepared;
	}

	private function finish(): bool
	{
		// @todo

		\sleep(1);

		return true;
	}

	private function validateIngredients(string $type): void
	{
		$requirements = $this->requirements[$type];
		foreach ($requirements as $requirement)
		{
			$isReplacementIngredient = false;

			/** @var Ingredient|null $ingredient */
			$ingredient = $this->ingredients[$type][$requirement->getIngredientClass()] ?? null;

			if (!$ingredient) {
				$ingredient = $this->ingredients[$type][$requirement->getReplacementIngredient()?->getIngredientClass()] ?? null;

				if ($ingredient) {
					$isReplacementIngredient = true;
				}
			}

			if (!$ingredient) {
				throw new MissingIngredientException($requirement->getIngredientClass()." not added to {$type} ingredients");
			}

			$requirementMinAmount = !$isReplacementIngredient ? $requirement->getMinAmount() : $requirement->getReplacementIngredient()->getMinAmount();

			if ($ingredient->getAmount() < $requirementMinAmount) {
				throw new AmountTooLowException("Add at least {$requirementMinAmount} of {$ingredient->getName()} to the {$type}");
			}

			if (!$isReplacementIngredient)
			{
				$requirementMaxAmount = $requirement->getMaxAmount() ?? $requirement->getMinAmount();
			}
			else
			{
				$requirementMaxAmount = $requirement->getReplacementIngredient()->getMaxAmount() ?? $requirement->getReplacementIngredient()->getMinAmount();
			}

			if ($ingredient->getAmount() > $requirementMaxAmount) {
				throw new AmountTooHighException("Too much of {$ingredient->getName()} - max allowed is {$requirementMaxAmount}");
			}
		}
	}

}