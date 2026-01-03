<?php
declare(strict_types=1);

namespace Znojil\Heureka\Feed\DTO;

final readonly class CategoryTreeDTO{

	/**
	 * @param CategoryDTO[] $children
	 */
	public function __construct(
		public int $id,
		public string $name,
		public array $children = []
	){}

}

/**
 * @implements \IteratorAggregate<int, CategoryTreeDTO>
 */
final readonly class CategoryTreeDTOCollection implements \IteratorAggregate, \Countable{

	/**
	 * @param CategoryTreeDTO[] $items
	 */
	public function __construct(
		private array $items
	){}

	/**
	 * @return array<string, array<string, string>>
	 */
	public function toGroupedArray(): array{
		$r = [];
		foreach($this->items as $v){
			$r[$v->name] = [];
			foreach($v->children as $v2){
				$r[$v->name][$v2->fullName] = $v2->name;
			}
		}

		return $r;
	}

	/**
	 * @return \Generator<int, CategoryTreeDTO>
	 */
	public function getIterator(): \Generator{
		foreach($this->items as $v){
			yield $v->id => $v;
		}
	}

	public function count(): int{
		return count($this->items);
	}

	/**
	 * @return CategoryTreeDTO[]
	 */
	public function getItems(): array{
		return $this->items;
	}

}
