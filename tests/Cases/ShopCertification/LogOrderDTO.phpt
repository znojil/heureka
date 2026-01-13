<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Cases\ShopCertification;

use Tester\Assert;
use Znojil\Heureka\ShopCertification\LogOrderDTO;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class LogOrderDTOTest extends \Tester\TestCase{

	public function testToArray(): void{
		Assert::same(['email' => 'Email@example.com'], (new LogOrderDTO('Email@example.com'))->toArray());
		Assert::same([
			'email' => 'email@example.com',
			'orderId' => 'ORd20250001',
			'productItemIds' => ['1', '2', '3', '04']
		], (new LogOrderDTO('email@example.com', 'ORd20250001', [1, '2', 03, '04']))->toArray());
	}

}

(new LogOrderDTOTest)->run();
