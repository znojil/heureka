<?php
declare(strict_types=1);

namespace Znojil\Heureka\Feed\DTO;

final readonly class ShopReviewDTO{

	public function __construct(
		public int $id,
		public string $source,
		public ?string $author,
		public string $orderId,
		public ?float $totalRating,
		public ?bool $recommendation,
		public ?string $pros,
		public ?string $cons,
		public ?string $summary,
		public ?float $transportQuality,
		public ?float $webUsability,
		public ?float $communication,
		public ?float $deliveryTime,
		public ?string $reaction,
		public \DateTimeImmutable $orderedDatetime,
		public \DateTimeImmutable $createdDatetime
	){}

	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array{
		return get_object_vars($this);
	}

}
