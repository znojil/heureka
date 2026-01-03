<?php
declare(strict_types=1);

namespace Znojil\Heureka\Internal;

final class StringUtil{

	public static function normalizeLineEndingsAndTrim(string $value): string{
		return trim((string) preg_replace('~\R~u', "\n", $value));
	}

}
