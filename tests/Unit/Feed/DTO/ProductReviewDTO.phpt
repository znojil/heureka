<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Unit\Feed\DTO;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class ProductReviewDTOTest extends \Tester\TestCase{

	public function testToArray(): void{
		$createdDatetime = new \DateTimeImmutable('2025-12-01 12:00:00');

		$productReview = new \Znojil\Heureka\Feed\DTO\ProductReviewDTO(
			1,
			'products',
			'Mara Z.',
			'666',
			'Product name',
			'http://product.com/666-product-name',
			69.67,
			null,
			'O123',
			5,
			true,
			'--',
			'NO!',
			"👎\n👍",
			$createdDatetime
		);

		\Tester\Assert::same([
			'id' => 1,
			'type' => 'products',
			'author' => 'Mara Z.',
			'productNumber' => '666',
			'productName' => 'Product name',
			'productUrl' => 'http://product.com/666-product-name',
			'productPrice' => 69.67,
			'productEan' => null,
			'orderId' => 'O123',
			'rating' => 5.0,
			'recommendation' => true,
			'pros' => '--',
			'cons' => 'NO!',
			'summary' => "👎\n👍",
			'createdDatetime' => $createdDatetime
		], $productReview->toArray());
	}

}

(new ProductReviewDTOTest)->run();
