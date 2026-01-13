<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Cases\Internal;

use Tester\Assert;
use Znojil\Heureka\Internal\StringUtil;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class StringUtilTest extends \Tester\TestCase{

	/**
	 * @return array<mixed>
	 */
	public function getNormalizeLineEndingsAndTrimArgs(): array{
		return [
			["	 line1\r\nline2	 ", "line1\nline2"],
			["a\rb\nc", "a\nb\nc"],
			["a
	b", "a\n	b"],
			['	 	', '']
		];
	}

	/**
	 * @dataProvider getNormalizeLineEndingsAndTrimArgs
	 */
	public function testNormalizeLineEndingsAndTrim(string $input, string $expected): void{
		Assert::same($expected, StringUtil::normalizeLineEndingsAndTrim($input));
	}

}

(new StringUtilTest)->run();
