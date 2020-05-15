<?php
/**
 * @file
 * MultiKeywordSniff.php
 */

namespace JParkinson1991\PhpCodeSnifferStandards\Standards\JPSR12\Sniffs\ControlSignatures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Verifies multi keyword control structure
 *
 * Hacked/slashed from Drupal standard to enforce that else/else if and catch
 * are on a new line
 */
class MultiKeywordSniff implements Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return int[]
     */
    public function register()
    {
        return [
            T_CATCH,
            T_ELSE,
            T_ELSEIF,
        ];

    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     *     The file being scanned.
     * @param int $stackPtr
     *     The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $closer = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($closer === false || $tokens[$closer]['code'] !== T_CLOSE_CURLY_BRACKET) {
            return;
        }

        // New line after closing brace.
        $found = 'newline';
        if ($tokens[($closer + 1)]['code'] !== T_WHITESPACE) {
            $found = 'none';
        } else if (strpos($tokens[($closer + 1)]['content'], "\n") === false) {
            $found = 'spaces';
        }

        if ($found !== 'newline') {
            $error = 'Expected newline after closing brace';
            $fix   = $phpcsFile->addFixableError($error, $closer, 'NewlineRequired');
            if ($fix === true) {
                if ($found === 'none') {
                    $phpcsFile->fixer->addContent($closer, "\n");
                } else {
                    $phpcsFile->fixer->replaceToken(($closer + 1), "\n");
                }
            }
        }
    }
}
