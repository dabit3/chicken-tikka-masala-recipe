<?php
declare(strict_types=1);

namespace Ctm\Units;

trait Unit {

	public function __construct(
		private float|int $amount
	) {}

	final public function getAmount(): float|int
	{
		return $this->amount;
	}

}