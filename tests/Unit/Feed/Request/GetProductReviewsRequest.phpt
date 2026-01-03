<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Unit\Feed\Request;

use Tester\Assert;
use Znojil\Heureka\Feed\DTO\ProductReviewDTO;
use Znojil\Heureka\Feed\Request\GetProductReviewsRequest;
use Znojil\Http\Message\Response;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class GetProductReviewsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetProductReviewsRequest;

		Assert::false($request->onApi());
		Assert::true($request->requiresAuth());
		Assert::same('GET', $request->getMethod());
		Assert::same('direct/dotaznik/export-product-review.php', $request->getUrn());
		Assert::same([], $request->getHeaders());
		Assert::same([], $request->getData());
		Assert::same([], $request->getHttpClientOptions());

		// with $from
		$fromDatetime = new \DateTimeImmutable('2025-12-24 12:00:00');
		$request = new GetProductReviewsRequest($fromDatetime);

		Assert::same(
			'direct/dotaznik/export-product-review.php?from=' . $fromDatetime->format('Y-m-d H:i:s'),
			$request->getUrn()
		);
	}

	public function testCreateResponse(): void{
		$response = new Response(200, body: (string) file_get_contents(__DIR__ . '/../../../Fixtures/data/product-reviews.xml'));
		$request = new GetProductReviewsRequest;
		$result = $request->createResponse($response);

		Assert::count(2, $result);
		Assert::type(ProductReviewDTO::class, $result[0]);

		$productReview = $result[0];
		Assert::same(10, $productReview->id);
		Assert::same('product', $productReview->type);
		Assert::same('Marek Znojil', $productReview->author);
		Assert::same('1', $productReview->productNumber);
		Assert::same('Product a', $productReview->productName);
		Assert::same('https://www.example.com/product/1-product-a', $productReview->productUrl);
		Assert::same(99.99, $productReview->productPrice);
		Assert::same('0123456789123', $productReview->productEan);
		Assert::same('202500001', $productReview->orderId);
		Assert::same(5.0, $productReview->rating);
		Assert::same(true, $productReview->recommendation);
		Assert::same("First pros.\nSecond pros.", $productReview->pros);
		Assert::same("First cons.\nSecond cons.", $productReview->cons);
		Assert::same("Summary product 'a',\n\neverything is fine!", $productReview->summary);
		Assert::same('2025-12-24T18:30:00+01:00', $productReview->createdDatetime->format('c'));

		// optional
		$productReview2 = $result[1];
		Assert::same(20, $productReview2->id);
		Assert::same('product', $productReview2->type);
		Assert::null($productReview2->author);
		Assert::null($productReview2->productNumber);
		Assert::same('Product b', $productReview2->productName);
		Assert::same('https://www.example.com/product/2-product-b', $productReview2->productUrl);
		Assert::same(69.99, $productReview2->productPrice);
		Assert::null($productReview2->productEan);
		Assert::same('202500002', $productReview2->orderId);
		Assert::null($productReview2->rating);
		Assert::null($productReview2->recommendation);
		Assert::null($productReview2->pros);
		Assert::null($productReview2->cons);
		Assert::null($productReview2->summary);
		Assert::same('2025-12-24T18:30:01+01:00', $productReview2->createdDatetime->format('c'));
	}

	public function testCreateResponseBlank(): void{
		$response = new Response(200, body: (string) file_get_contents(__DIR__ . '/../../../Fixtures/data/product-reviews-blank.xml'));
		$request = new GetProductReviewsRequest;
		$result = $request->createResponse($response);

		Assert::count(0, $result);
	}

}

(new GetProductReviewsRequestTest)->run();
