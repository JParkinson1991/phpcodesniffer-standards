<?php
/**
 * @file
 * OperatorSpacingSniff.php
 */

namespace JParkinson1991\PhpCodeSnifferStandards\Standards\JPSR12\Sniffs\Operators;


use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\OperatorSpacingSniff as SquizOperatorSpacingSniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Class OperatorSpacingSniff
 *
 * Taken form PSR 12 standard
 * Require spacing between operators except for string concatenation.
 * Enforce no spacing with string concatenation.
 *
 * @package JParkinson1991\PhpCodeSnifferStandards\Standards\JPSR12\Sniffs\Operators
 */
class OperatorSpacingSniff extends SquizOperatorSpacingSniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     * @noinspection AdditionOperationOnArraysInspection
     */
    public function register()
    {
        parent::register();

        $targets = Tokens::$comparisonTokens;
        $targets += Tokens::$operators;
        $targets += Tokens::$assignmentTokens;
        $targets += Tokens::$booleanOperators;
        $targets[] = T_INLINE_THEN;
        $targets[] = T_INLINE_ELSE;
        $targets[] = T_STRING_CONCAT;
        $targets[] = T_INSTANCEOF;

        return $targets;
    }

    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     *     The current file being checked.
     * @param int $stackPtr
     *     The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($this->isOperator($phpcsFile, $stackPtr) === false) {
            return;
        }

        $operator = $tokens[$stackPtr]['content'];

        $checkBefore = true;
        $checkAfter = true;

        // Skip short ternary.
        if ($tokens[($stackPtr)]['code'] === T_INLINE_ELSE
            && $tokens[($stackPtr - 1)]['code'] === T_INLINE_THEN
        ) {
            $checkBefore = false;
        }

        // Skip operator with comment on previous line.
        if ($tokens[($stackPtr - 1)]['code'] === T_COMMENT
            && $tokens[($stackPtr - 1)]['line'] < $tokens[$stackPtr]['line']
        ) {
            $checkBefore = false;
        }

        if (isset($tokens[($stackPtr + 1)]) === true) {
            // Skip short ternary.
            if ($tokens[$stackPtr]['code'] === T_INLINE_THEN
                && $tokens[($stackPtr + 1)]['code'] === T_INLINE_ELSE
            ) {
                $checkAfter = false;
            }
        } else {
            // Skip partial files.
            $checkAfter = false;
        }

        // Non string concat operators - Require space before and after them
        // Else if string concat - require no space before and after
        if ($tokens[($stackPtr)]['code'] !== T_STRING_CONCAT) {
            if ($checkBefore === true && $tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE) {
                $error = 'Expected at least 1 space before "%s"; 0 found';
                $data = [$operator];
                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'NoSpaceBefore', $data);
                if ($fix === true) {
                    $phpcsFile->fixer->addContentBefore($stackPtr, ' ');
                }
            }

            if ($checkAfter === true && $tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
                $error = 'Expected at least 1 space after "%s"; 0 found';
                $data = [$operator];
                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'NoSpaceAfter', $data);
                if ($fix === true) {
                    $phpcsFile->fixer->addContent($stackPtr, ' ');
                }
            }
        } else {
            if ($checkBefore === true && $tokens[($stackPtr - 1)]['code'] === T_WHITESPACE) {
                // Find previous non whitespace character, use it determine file line difference
                $previousNonWhitespaceToken = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
                $lineDiff = $tokens[$stackPtr]['line'] - $tokens[$previousNonWhitespaceToken]['line'];

                $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null);

                // Same line, check spaces
                // Else more than one line, show error
                // Single new line between arguments allowed
                if ($lineDiff === 0) {
                    $fix = $phpcsFile->addFixableError(
                        'Expected 0 spaces before string concatenation operator',
                        $stackPtr,
                        'StringConcatSpaceBefore'
                    );

                    if ($fix === true) {
                        $phpcsFile->fixer->replaceToken($stackPtr - 1, '');
                    }
                }
                else if ($lineDiff > 1) {
                    $fix = $phpcsFile->addFixableError(
                        'Empty lines found before string concatenation operator',
                        $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null),
                        'StringConcatEmptyLinesBefore'
                    );

                    if ($fix === true) {
                        $phpcsFile->fixer->replaceToken($stackPtr - 1, '');
                    }
                }
            }

            if ($checkAfter === true && $tokens[($stackPtr + 1)]['code'] === T_WHITESPACE) {
                // Find next non whitepsace character, use it determine file line difference
                $nextNonWhitespaceToken = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true);
                $lineDiff = $tokens[$nextNonWhitespaceToken]['line'] - $tokens[$stackPtr]['line'];

                // Same line, check spaces
                // Else more than one line, show error
                // Single new line between arguments allowed
                if ($lineDiff === 0) {
                    $fix = $phpcsFile->addFixableError(
                        'Expected 0 spaces after string concatenation operator',
                        $stackPtr,
                        'StringConcatSpaceAfter'
                    );

                    if ($fix === true) {
                        $phpcsFile->fixer->replaceToken($stackPtr + 1, '');
                    }
                }
                else if ($lineDiff > 1) {
                    $fix = $phpcsFile->addFixableError(
                        'Empty lines found after string concatenation operator',
                        $nextNonWhitespaceToken,
                        'StringConcatEmptyLinesAfter'
                    );

                    if ($fix === true) {
                        // Remove all whitespace, remove the string concat operator
                        $phpcsFile->fixer->replaceToken($stackPtr + 1, '');
                        $phpcsFile->fixer->replaceToken($stackPtr, '');

                        // Prefix the next none whitepsace token with the string concat operator
                        $phpcsFile->fixer->replaceToken($nextNonWhitespaceToken, '.'.$tokens[$nextNonWhitespaceToken]['content']);
                    }
                }
            }
        }
    }
}
