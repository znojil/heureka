<?php
declare(strict_types=1);

namespace Znojil\Heureka\ShopCertification;

final readonly class LogOrderDTO{

	/** @var string[] */
	public array $productItemIds;

	/**
	 * @param array<string|int> $productItemIds
	 */
	public function __construct(
		public string $email,
		public ?string $orderId = null,
		array $productItemIds = []
	){
		$this->productItemIds = array_map(fn(string|int $v): string => (string) $v, $productItemIds);
	}

	/**
	 * @return array{email: string, orderId?: string, productItemIds?: string[]}
	 */
	public function toArray(): array{
		$data = ['email' => $this->email];

		if($this->orderId !== null){
			$data['orderId'] = $this->orderId;
		}

		if(!empty($this->productItemIds)){
			$data['productItemIds'] = $this->productItemIds;
		}

		return $data;
	}

}
