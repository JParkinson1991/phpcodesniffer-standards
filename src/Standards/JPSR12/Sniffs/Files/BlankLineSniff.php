<?php
/**
 * @file
 * NewlineSniff.php
 */

namespace JParkinson1991\PhpCodeSnifferStandards\Standards\JPSR12\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class NewlineSniff
 *
 * @package JParkinson1991\PhpCodeSnifferStandards\Standards\JPSR12\Sniffs\Files
 */
class BlankLineSniff implements Sniff
{
    /**
     * Holds an array of line numbers that have had the error applied to them.
     *
     * Multiple whitespace items can trigger multiple errors for the same
     * two next/prev code blocks, storing line numbers here stops multiple
     * errors being flagged for the same line
     *
     * @var int
     */
    protected $createdErrorLines = [];

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * An example return value for a sniff that wants to listen for whitespace
     * and any comments would be:
     *
     * <code>
     *    return array(
     *            T_WHITESPACE,
     *            T_DOC_COMMENT,
     *            T_COMMENT,
     *           );
     * </code>
     *
     * @see    Tokens.php
     * @return mixed[]
     */
    public function register()
    {
        return [
            T_WHITESPACE,
            T_DOC_COMMENT_STAR
        ];
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * The stackPtr variable indicates where in the stack the token was found.
     * A sniff can acquire information this token, along with all the other
     * tokens within the stack by first acquiring the token stack:
     *
     * <code>
     *    $tokens = $phpcsFile->getTokens();
     *    echo 'Encountered a '.$tokens[$stackPtr]['type'].' token';
     *    echo 'token information: ';
     *    print_r($tokens[$stackPtr]);
     * </code>
     *
     * If the sniff discovers an anomaly in the code, they can raise an error
     * by calling addError() on the \PHP_CodeSniffer\Files\File object, specifying an error
     * message and the position of the offending token:
     *
     * <code>
     *    $phpcsFile->addError('Encountered an error', $stackPtr);
     * </code>
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The PHP_CodeSniffer file where the
     *                                               token was found.
     * @param int $stackPtr The position in the PHP_CodeSniffer
     *                                               file's token stack where the token
     *                                               was found.
     *
     * @return void|int Optionally returns a stack pointer. The sniff will not be
     *                  called again on the current file until the returned stack
     *                  pointer is reached. Return (count($tokens) + 1) to skip
     *                  the rest of the file.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $tokensExclude = ($tokens[$stackPtr]['code'] === T_WHITESPACE)
            ? [T_WHITESPACE]
            : [T_DOC_COMMENT_WHITESPACE, T_DOC_COMMENT_STAR];

        // Find non whitespace code lines before and after this
        $prevCodeLinePtr = $phpcsFile->findPrevious($tokensExclude, $stackPtr, null, true);
        $nextCodeLinePtr = $phpcsFile->findNext($tokensExclude, $stackPtr, null, true);

        $prevCodeLine = $tokens[$prevCodeLinePtr]['line'];
        $nextCodeLine = $tokens[$nextCodeLinePtr]['line'];

        $blankLinesBetween = ($nextCodeLine - $prevCodeLine) - 1;
        if($blankLinesBetween > 1 && !in_array($nextCodeLine, $this->createdErrorLines, true)) {
            // Add the error line avoiding duplicates
            $this->createdErrorLines[] = $nextCodeLine;

            // Determine error message error code
            $error = 'Expected a maximum of 1 blank line between code/comments. Found: '.$blankLinesBetween;
            $fix = $phpcsFile->addFixableError($error, $nextCodeLinePtr, ($tokens[$stackPtr]['code'] === T_WHITESPACE)
                ? 'MultipleBlankLines'
                : 'DocBlockMultipleBlankLines'
            );

            if ($fix === true) {
                // Determine fix params
                if ($tokens[$stackPtr]['code'] === T_WHITESPACE) {
                    $startPtr = $nextCodeLinePtr - 1;
                    $endPtr = $prevCodeLinePtr;
                    $addBlankLine = true;
                }
                else{
                    // Find the first star blank line after the previous code line
                    // Find the first star blank line before the next code line
                    $endPtr = $phpcsFile->findNext(T_DOC_COMMENT_STAR, $prevCodeLinePtr);
                    $startPtr = $phpcsFile->findPrevious(
                        T_DOC_COMMENT_STAR,
                        ($phpcsFile->findPrevious(T_DOC_COMMENT_STAR, $nextCodeLinePtr) - 1)
                    );
                    $addBlankLine = false;
                }

                $phpcsFile->fixer->beginChangeset();

                // Remove all content between the start and end
                for ($ptr = $startPtr; $ptr > $endPtr; $ptr--) {
                    $phpcsFile->fixer->replaceToken($ptr, '');
                }

                // Add a single blank line between if needed
                if ($addBlankLine) {
                    $phpcsFile->fixer->addContentBefore($nextCodeLinePtr, $phpcsFile->eolChar.$phpcsFile->eolChar);
                }

                $phpcsFile->fixer->endChangeset();
            }
        }
    }

}
