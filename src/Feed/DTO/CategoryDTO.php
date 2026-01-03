<?php
declare(strict_types=1);

namespace Znojil\Heureka\Feed\DTO;

final readonly class CategoryDTO{

	public function __construct(
		public int $id,
		public string $name,
		public string $fullName
	){}

}
