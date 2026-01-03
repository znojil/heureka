<?php
declare(strict_types=1);

namespace Znojil\Heureka\ShopCertification;

use Znojil\Heureka\Http\Request;

/**
 * @implements Request<OrderLogResponse>
 */
final class OrderLogRequest implements Request{

	public function __construct(
		private readonly LogOrderDTO $logOrder
	){}

	public function onApi(): bool{
		return true;
	}

	public function requiresAuth(): bool{
		return true;
	}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'shop-certification/v2/order/log';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json; charset=utf-8'];
	}

	public function getData(): array{
		return $this->logOrder->toArray();
	}

	public function getHttpClientOptions(): array{
		return [CURLOPT_TIMEOUT => 5];
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $response): OrderLogResponse{
		return new OrderLogResponse((string) $response->getBody());
	}

}
