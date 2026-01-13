<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Cases\Feed\Request;

use Tester\Assert;
use Znojil\Heureka\Feed\DTO\ShopReviewDTO;
use Znojil\Heureka\Feed\Request\GetShopReviewsRequest;
use Znojil\Http\Message\Response;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class GetShopReviewsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetShopReviewsRequest;

		Assert::false($request->onApi());
		Assert::true($request->requiresAuth());
		Assert::same('GET', $request->getMethod());
		Assert::same('direct/dotaznik/export-review.php', $request->getUrn());
		Assert::same([], $request->getHeaders());
		Assert::same([], $request->getData());
		Assert::same([], $request->getHttpClientOptions());
	}

	public function testCreateResponse(): void{
		$response = new Response(200, body: file_get_contents(__DIR__ . '/../../../Fixtures/data/reviews.xml'));
		$request = new GetShopReviewsRequest;
		$result = $request->createResponse($response);

		Assert::count(2, $result);
		Assert::type(ShopReviewDTO::class, $result[0]);

		$shopReview = $result[0];
		Assert::same(10, $shopReview->id);
		Assert::same('regular', $shopReview->source);
		Assert::same('Znojil Marek', $shopReview->author);
		Assert::same('202500010', $shopReview->orderId);
		Assert::same(4.5, $shopReview->totalRating);
		Assert::same(true, $shopReview->recommendation);
		Assert::same("First pros.\nSecond pros.", $shopReview->pros);
		Assert::same("First cons.\nSecond cons.", $shopReview->cons);
		Assert::same("Summary e-shop,\n\neverything is fine!", $shopReview->summary);
		Assert::same(5.0, $shopReview->transportQuality);
		Assert::same(5.0, $shopReview->webUsability);
		Assert::same(5.0, $shopReview->communication);
		Assert::same(5.0, $shopReview->deliveryTime);
		Assert::same("Thanks!\nRegards..", $shopReview->reaction);
		Assert::same('2025-12-01T13:00:00+01:00', $shopReview->orderedDatetime->format('c'));
		Assert::same('2025-12-08T13:00:00+01:00', $shopReview->createdDatetime->format('c'));

		// optional
		$shopReview2 = $result[1];
		Assert::same(9, $shopReview2->id);
		Assert::same('regular', $shopReview2->source);
		Assert::null($shopReview2->author);
		Assert::same('202500009', $shopReview2->orderId);
		Assert::null($shopReview2->totalRating);
		Assert::null($shopReview2->recommendation);
		Assert::null($shopReview2->pros);
		Assert::null($shopReview2->cons);
		Assert::null($shopReview2->summary);
		Assert::null($shopReview2->transportQuality);
		Assert::null($shopReview2->webUsability);
		Assert::null($shopReview2->communication);
		Assert::null($shopReview2->deliveryTime);
		Assert::null($shopReview2->reaction);
		Assert::same('2025-12-01T13:00:01+01:00', $shopReview2->orderedDatetime->format('c'));
		Assert::same('2025-12-08T13:00:01+01:00', $shopReview2->createdDatetime->format('c'));
	}

	public function testCreateResponseBlank(): void{
		$response = new Response(200, body: file_get_contents(__DIR__ . '/../../../Fixtures/data/reviews-blank.xml'));
		$request = new GetShopReviewsRequest;
		$result = $request->createResponse($response);

		Assert::count(0, $result);
	}

}

(new GetShopReviewsRequestTest)->run();
