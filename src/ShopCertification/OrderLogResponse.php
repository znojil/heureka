<?php
declare(strict_types=1);

namespace Znojil\Heureka\ShopCertification;

final readonly class OrderLogResponse{

	public int $code;

	public string $message;

	public ?string $description;

	public function __construct(string $responseBody){
		/** @var ?array{code: int|string, message: string, description?: string} */
		$response = json_decode($responseBody, true);

		if(!is_array($response)){
			throw new \Znojil\Heureka\Exception\JsonException(sprintf(
				"Unexpected response '%s' returned. JSON error: %s",
				$responseBody,
				json_last_error_msg()
			), json_last_error());
		}

		$this->code = (int) $response['code'];
		$this->message = $response['message'];
		$this->description = $response['description'] ?? null;
	}

	public function isSuccessful(): bool{
		return $this->code >= 200 && $this->code < 300;
	}

}
