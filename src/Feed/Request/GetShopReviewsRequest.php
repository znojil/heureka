<?php
declare(strict_types=1);

namespace Znojil\Heureka\Feed\Request;

use Znojil\Heureka\Feed\DTO\ShopReviewDTO;
use Znojil\Heureka\Internal\StringUtil;
use Znojil\Heureka\Internal\XmlExtractor;

/**
 * @extends BaseRequest<ShopReviewDTO[]>
 */
final class GetShopReviewsRequest extends BaseRequest{

	public function requiresAuth(): bool{
		return true;
	}

	public function getUrn(): string{
		return 'direct/dotaznik/export-review.php';
	}

	/**
	 * @return ShopReviewDTO[]
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $response): array{
		$r = [];
		foreach($this->getXmlFromResponseBody($response->getBody())->review ?? [] as $v){
			$r[] = new ShopReviewDTO(
				XmlExtractor::getRequiredInt($v, 'rating_id'),
				XmlExtractor::getRequiredString($v, 'source'),
				XmlExtractor::getOptionalString($v, 'name'),
				XmlExtractor::getRequiredString($v, 'order_id'),
				XmlExtractor::getOptionalFloat($v, 'total_rating'),
				XmlExtractor::getOptionalBool($v, 'recommends'),
				($pros = XmlExtractor::getOptionalString($v, 'pros')) !== null ? StringUtil::normalizeLineEndingsAndTrim($pros) : null,
				($cons = XmlExtractor::getOptionalString($v, 'cons')) !== null ? StringUtil::normalizeLineEndingsAndTrim($cons) : null,
				($summary = XmlExtractor::getOptionalString($v, 'summary')) !== null ? StringUtil::normalizeLineEndingsAndTrim($summary) : null,
				XmlExtractor::getOptionalFloat($v, 'transport_quality'),
				XmlExtractor::getOptionalFloat($v, 'web_usability'),
				XmlExtractor::getOptionalFloat($v, 'communication'),
				XmlExtractor::getOptionalFloat($v, 'delivery_time'),
				XmlExtractor::getOptionalString($v, 'reaction'),
				$this->createDatetimeFromUnixTimestamp(XmlExtractor::getRequiredString($v, 'ordered')),
				$this->createDatetimeFromUnixTimestamp(XmlExtractor::getRequiredString($v, 'unix_timestamp'))
			);
		}

		return $r;
	}

}
