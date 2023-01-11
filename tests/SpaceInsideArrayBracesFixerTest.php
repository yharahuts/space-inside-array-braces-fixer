<?php
namespace Tests;

use Codestyle\Fixer\SpaceInsideArrayBracesFixer;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * @covers \Codestyle\Fixer\SpaceInsideArrayBracesFixer
 *
 * @internal
 */
class SpaceInsideArrayBracesFixerTest extends \PHPUnit\Framework\TestCase {
	/**
	 * @dataProvider ProvideFixerData
	 */
	public function testFixerIsWorkingCorrectly( string $code, string $expected_result ) {
		$file = new \SplFileInfo( __FILE__ );
		$fixer = new SpaceInsideArrayBracesFixer();

		Tokens::clearCache();
		$tokens = Tokens::fromCode( $code );

		$fixer->fix( $file, $tokens );
		$actual_code = $tokens->generateCode();

		$this->assertSame( $expected_result, $actual_code );
	}

	public function ProvideFixerData() : \Generator {
		yield [
			'<?php $foo = ["bar"];',
			'<?php $foo = [ "bar" ];',
		];

		yield [
			'<?php $foo = ["foo", "bar"];',
			'<?php $foo = [ "foo", "bar" ];',
		];

		yield [
			'<?php $foo = [0 => "bar"];',
			'<?php $foo = [ 0 => "bar" ];',
		];

		yield [
			'<?php $foo = ["foo" => "bar"];',
			'<?php $foo = [ "foo" => "bar" ];',
		];

		yield [
			'<?php $foo["bar"] = "foo";',
			'<?php $foo[ "bar" ] = "foo";',
		];

		yield [
			'<?php $foo["foo"]["bar"] = "foo";',
			'<?php $foo[ "foo" ][ "bar" ] = "foo";',
		];

		yield [
			'<?php [$foo,$bar] = ["foo","bar"];',
			'<?php [ $foo,$bar ] = [ "foo","bar" ];',
		];

		yield [
			'<?php ["foo" => $foo,"bar" => $bar] = ["foo" => "foo","bar"=>"bar"];',
			'<?php [ "foo" => $foo,"bar" => $bar ] = [ "foo" => "foo","bar"=>"bar" ];',
		];
	}
}
