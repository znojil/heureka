<?php
declare(strict_types=1);

namespace Znojil\Heureka\Feed\Request;

use Znojil\Heureka\Feed\DTO\ProductReviewDTO;
use Znojil\Heureka\Internal\StringUtil;
use Znojil\Heureka\Internal\XmlExtractor;

/**
 * @extends BaseRequest<ProductReviewDTO[]>
 */
final class GetProductReviewsRequest extends BaseRequest{

	public function __construct(
		private readonly ?\DateTimeInterface $from = null
	){}

	public function requiresAuth(): bool{
		return true;
	}

	public function getUrn(): string{
		$urn = 'direct/dotaznik/export-product-review.php';
		if($this->from !== null){
			$urn .= '?from=' . $this->from->format('Y-m-d H:i:s');
		}

		return $urn;
	}

	/**
	 * @return ProductReviewDTO[]
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $response): array{
		$r = [];
		foreach($this->getXmlFromResponseBody($response->getBody())->product ?? [] as $v){
			foreach($v->reviews->review ?? [] as $v2){
				$r[] = new ProductReviewDTO(
					XmlExtractor::getRequiredInt($v2, 'rating_id'),
					XmlExtractor::getRequiredString($v2, 'rating_id_type'),
					XmlExtractor::getOptionalString($v2, 'name'),
					XmlExtractor::getOptionalString($v, 'productno'),
					XmlExtractor::getRequiredString($v, 'product_name'),
					XmlExtractor::getRequiredString($v, 'url'),
					XmlExtractor::getRequiredFloat($v, 'price'),
					XmlExtractor::getOptionalString($v, 'ean'),
					XmlExtractor::getRequiredString($v, 'order_id'),
					XmlExtractor::getOptionalFloat($v2, 'rating'),
					XmlExtractor::getOptionalBool($v2, 'recommends'),
					($pros = XmlExtractor::getOptionalString($v2, 'pros')) !== null ? StringUtil::normalizeLineEndingsAndTrim($pros) : null,
					($cons = XmlExtractor::getOptionalString($v2, 'cons')) !== null ? StringUtil::normalizeLineEndingsAndTrim($cons) : null,
					($summary = XmlExtractor::getOptionalString($v2, 'summary')) !== null ? StringUtil::normalizeLineEndingsAndTrim($summary) : null,
					$this->createDatetimeFromUnixTimestamp(XmlExtractor::getRequiredString($v2, 'unix_timestamp'))
				);
			}
		}

		return $r;
	}

}
