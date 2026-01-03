<?php
declare(strict_types=1);

namespace Znojil\Heureka\Feed\DTO;

final readonly class ProductReviewDTO{

	public function __construct(
		public int $id,
		public string $type,
		public ?string $author,
		public ?string $productNumber,
		public string $productName,
		public string $productUrl,
		public float $productPrice,
		public ?string $productEan,
		public string $orderId,
		public ?float $rating,
		public ?bool $recommendation,
		public ?string $pros,
		public ?string $cons,
		public ?string $summary,
		public \DateTimeImmutable $createdDatetime
	){}

	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array{
		return get_object_vars($this);
	}

}
