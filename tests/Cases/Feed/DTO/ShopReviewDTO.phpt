<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Cases\Feed\DTO;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class ShopReviewDTOTest extends \Tester\TestCase{

	public function testToArray(): void{
		$orderedDatetime = new \DateTimeImmutable('2025-12-01 12:00:00');
		$createdDatetime = new \DateTimeImmutable('2025-12-08 12:00:00');

		$shopReview = new \Znojil\Heureka\Feed\DTO\ShopReviewDTO(
			1,
			'shop',
			'Mara Z.',
			'O123',
			5,
			false,
			'OK',
			'--',
			"👍\r\n👎",
			4.5,
			null,
			5,
			5,
			null,
			$orderedDatetime,
			$createdDatetime
		);

		\Tester\Assert::same([
			'id' => 1,
			'source' => 'shop',
			'author' => 'Mara Z.',
			'orderId' => 'O123',
			'totalRating' => 5.0,
			'recommendation' => false,
			'pros' => 'OK',
			'cons' => '--',
			'summary' => "👍\r\n👎",
			'transportQuality' => 4.5,
			'webUsability' => null,
			'communication' => 5.0,
			'deliveryTime' => 5.0,
			'reaction' => null,
			'orderedDatetime' => $orderedDatetime,
			'createdDatetime' => $createdDatetime
		], $shopReview->toArray());
	}

}

(new ShopReviewDTOTest)->run();
