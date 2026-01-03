<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Unit\Feed\DTO;

use Tester\Assert;
use Znojil\Heureka\Feed\DTO;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class CategoryTreeDTOTest extends \Tester\TestCase{

	public function testCollection(): void{
		$child1 = new DTO\CategoryDTO(10, 'TVs', 'Electronics | TVs');
		$child2 = new DTO\CategoryDTO(11, 'Radios', 'Electronics | Radios');
		$tree1 = new DTO\CategoryTreeDTO(1, 'Electronics', [$child1, $child2]);

		$child3 = new DTO\CategoryDTO(20, 'Sci-Fi', 'Books | Sci-Fi');
		$tree2 = new DTO\CategoryTreeDTO(2, 'Books', [$child3]);

		$collection = new DTO\CategoryTreeDTOCollection([$tree1, $tree2]);

		Assert::count(2, $collection);

		// iterator
		$items = [];
		foreach($collection as $k => $v){
			$items[$k] = $v;
		}
		Assert::same([1 => $tree1, 2 => $tree2], $items);

		Assert::same([$tree1, $tree2], $collection->getItems());
		Assert::same([
			'Electronics' => [
				'Electronics | TVs' => 'TVs',
				'Electronics | Radios' => 'Radios',
			],
			'Books' => [
				'Books | Sci-Fi' => 'Sci-Fi',
			],
		], $collection->toGroupedArray());
	}

}

(new CategoryTreeDTOTest)->run();