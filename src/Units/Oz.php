<?php
declare(strict_types=1);

namespace Ctm\Units;

trait Oz {

	use Unit {
		Unit::__construct as parentConstruct;
	}

	public function __construct(int $amount)
	{
		$this->parentConstruct($amount);
	}

}