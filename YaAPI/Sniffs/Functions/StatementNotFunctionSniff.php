<?php
/**
 * @name        StatementNotFunctionSniff
 * @package     Yaapi\Coding-Standards
 * @copyright   Copyright (C) 2014-2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

/**
 * Checks that language statements do no use brackets.
 *
 * @since   1.0
 */
class YaAPI_Sniffs_Functions_StatementNotFunctionSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
                T_INCLUDE_ONCE,
                T_REQUIRE_ONCE,
                T_REQUIRE,
                T_INCLUDE,
                T_CLONE,
                T_ECHO,
               ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param   PHP_CodeSniffer_File  $phpcsFile  The file being scanned.
     * @param   integer               $stackPtr   The position of the current token in the stack passed in $tokens.
     *
     * @return  void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $nextToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);

        if ($tokens[$nextToken]['code'] === T_OPEN_PARENTHESIS)
        {
            $error = '"%s" is a statement not a function; no parentheses are required';
            $data  = [$tokens[$stackPtr]['content']];
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'BracketsNotRequired', $data);

            if ($fix === true)
            {
                $end      = $phpcsFile->findEndOfStatement($nextToken);
                $ignore   = PHP_CodeSniffer_Tokens::$emptyTokens;
                $ignore[] = T_SEMICOLON;
                $closer   = $phpcsFile->findPrevious($ignore, ($end - 1), null, true);

                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->replaceToken($nextToken, '');

                if ($tokens[($stackPtr + 1)]['code'] === T_WHITESPACE)
                {
                    $phpcsFile->fixer->replaceToken(($stackPtr + 1), '');
                }

                if ($tokens[$closer]['code'] === T_CLOSE_PARENTHESIS)
                {
                    $phpcsFile->fixer->replaceToken($closer, '');
                }

                $phpcsFile->fixer->addContent($stackPtr, ' ');
                $phpcsFile->fixer->endChangeset();
            }
        }
    }
}
