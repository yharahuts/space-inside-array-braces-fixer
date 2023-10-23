<?php
namespace Codestyle\Fixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class SpaceInsideArrayBracesFixer extends AbstractFixer {
	/** {@inheritdoc} */
	public function isCandidate( Tokens $tokens ) : bool {
		return true;
	}

	/** {@inheritdoc} */
	public function getName() : string {
		return 'Yharahuts/space_inside_array_braces';
	}

	/** {@inheritdoc} */
	public function getDefinition() : FixerDefinitionInterface {
		return new FixerDefinition(
			'Adds space inside array declaration or array index braces.',
			[new CodeSample( '<?php $foo = ["bar"]; $foo["baz"] = "foo";' )]
		);
	}

	protected function applyFix( \SplFileInfo $file, Tokens $tokens ) : void {
		/** @var Token $token */
		foreach ( $tokens as $index => $token ) {
			if ( $token->isGivenKind( CT::T_ARRAY_SQUARE_BRACE_OPEN ) ) {
				$endParenthesisIndex = $tokens->findBlockEnd( Tokens::BLOCK_TYPE_ARRAY_SQUARE_BRACE, $index );
			} else if ( $token->isGivenKind( CT::T_DESTRUCTURING_SQUARE_BRACE_OPEN ) ) {
				$endParenthesisIndex = $tokens->findBlockEnd( Tokens::BLOCK_TYPE_DESTRUCTURING_SQUARE_BRACE, $index );
			} else if ( $token->equals( '[' ) ) {
				$endParenthesisIndex = $tokens->findBlockEnd( Tokens::BLOCK_TYPE_INDEX_SQUARE_BRACE, $index );
			} else {
				continue;
			}

			$this->fixParenthesisInnerEdge( $tokens, $index, $endParenthesisIndex );
		}
	}

	private function fixParenthesisInnerEdge( Tokens $tokens, $start, $end ) : void {
		// add single-line whitespace before )
		if ( !$tokens[ $end - 1 ]->isWhitespace() && !str_contains( $tokens[ $end - 1 ]->getContent(), "\n" ) ) {
			$tokens->ensureWhitespaceAtIndex( $end, 0, ' ' );
		}

		// add single-line whitespace after (
		if ( !$tokens[ $start + 1 ]->isWhitespace() && !str_contains( $tokens[ $start + 1 ]->getContent(), "\n" ) ) {
			$tokens->ensureWhitespaceAtIndex( $start, 1, ' ' );
		}
	}
}
