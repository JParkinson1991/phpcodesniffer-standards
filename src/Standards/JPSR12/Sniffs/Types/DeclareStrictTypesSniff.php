<?php
/**
 * @file
 * DeclareStrictTypesSniff.php
 */

namespace JParkinson1991\PhpCodeSnifferStandards\Standards\JPSR12\Sniffs\Types;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class DeclareStrictTypesSniff
 *
 * @package JParkinson1991\PhpCodeSnifferStandards\Standards\JPSR12\Sniffs\Types
 */
class DeclareStrictTypesSniff implements Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_OPEN_TAG];
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

        // Process interface files separately, they must not declare strict_type
        $interfacePtr = $phpcsFile->findNext(T_INTERFACE, $stackPtr);
        $isInterface = ($interfacePtr !== false);

        // Flag determining whether a strict type declaration was found
        // Used in final error check for
        $declareStrictTypesFound = false;

        // Find and validate the declare strict_types statement
        $declarePtr = $phpcsFile->findNext(T_DECLARE, $stackPtr);
        if ($declarePtr !== false) {
            $declareStringPtr = $phpcsFile->findNext(T_STRING, $declarePtr, null, false, null, true);

            if ($declareStringPtr !== false) {
                if ($tokens[$declareStringPtr]['content'] === 'strict_types') {
                    $declareStrictTypesFound = true;

                    // If not an interface ensure, value is 1
                    // ie. declare(strict_types=1)
                    // This will never cause duplicate errors as, non interface files require strict types to be set
                    // thus the not found error will never occur
                    if ($isInterface === false) {
                        $declareNumPtr = $phpcsFile->findNext(T_LNUMBER, $declareStringPtr, null, false, null, true);

                        if ($tokens[$declareNumPtr]['content'] !== '1') {
                            $fix = $phpcsFile->addFixableError(
                                'Strict type checking must be enabled. Expected 1. Got: '.$tokens[$declareNumPtr]['content'],
                                $declareNumPtr,
                                'DeclarationValue'
                            );

                            if ($fix === true) {
                                $phpcsFile->fixer->replaceToken($declareNumPtr, '1');
                            }
                        }
                    }
                }
            }
        }

        // If not found for non interfaces
        if ($declareStrictTypesFound === false && $isInterface === false) {
            $phpcsFile->addError(
                "Missing required strict_types declaration",
                $stackPtr,
                'MissingDeclaration'
            );
        }

        // If found and interface
        if ($declareStrictTypesFound === true && $isInterface === true) {
            $fix = $phpcsFile->addFixableError(
                'Interfaces must not define a strict_types declaration',
                $declarePtr,
                'InterfaceDeclaration'
            );

            if ($fix === true) {
                $declareSemiColonPtr = $phpcsFile->findNext(T_SEMICOLON, $declarePtr, null, false, null, true);

                $phpcsFile->fixer->beginChangeset();
                for ($i = $declareSemiColonPtr; $i >= $declarePtr; $i--) {
                    $phpcsFile->fixer->replaceToken($i, '');
                }
                $phpcsFile->fixer->endChangeset();
            }
        }
    }
}
