<?php
/**
 * @name        MemberVarSpacingSniff
 * @package     Yaapi\Coding-Standards
 * @copyright   Copyright (C) 2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

if (false === class_exists('Squiz_Sniffs_WhiteSpace_MemberVarSpacingSniff', true))
{
    throw new PHP_CodeSniffer_Exception('Class Squiz_Sniffs_WhiteSpace_MemberVarSpacingSniff not found');
}

/**
 * Verifies that class members are spaced correctly.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 *
 * @since   1.0
 */
class YaAPI_Sniffs_WhiteSpace_MemberVarSpacingSniff extends Squiz_Sniffs_WhiteSpace_MemberVarSpacingSniff
{
    /**
     * Processes the function tokens within the class.
     *
     * @param   PHP_CodeSniffer_File  $phpcsFile  The file where this token was found.
     * @param   int                   $stackPtr   The position where the token was found.
     *
     * @return  void
     */
    protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens   = $phpcsFile->getTokens();
        $ignore   = PHP_CodeSniffer_Tokens::$methodPrefixes;
        $ignore[] = T_VAR;
        $ignore[] = T_WHITESPACE;
        $start    = $stackPtr;
        $prev     = $phpcsFile->findPrevious($ignore, ($stackPtr - 1), null, true);

        if (isset(PHP_CodeSniffer_Tokens::$commentTokens[$tokens[$prev]['code']]) === true)
        {
            // Assume the comment belongs to the member var if it is on a line by itself.
            $prevContent = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($prev - 1), null, true);

            if ($tokens[$prevContent]['line'] !== $tokens[$prev]['line'])
            {
                // Check the spacing, but then skip it.
                $foundLines = ($tokens[$stackPtr]['line'] - $tokens[$prev]['line'] - 1);

                if ($foundLines > 0)
                {
                    $error = 'Expected 0 blank lines after member var comment; %s found';
                    $data  = [$foundLines];
                    $fix   = $phpcsFile->addFixableError($error, $prev, 'AfterComment', $data);

                    if ($fix === true)
                    {
                        $phpcsFile->fixer->beginChangeset();

                        for ($i = ($prev + 1); $i <= $stackPtr; $i++)
                        {
                            if ($tokens[$i]['line'] === $tokens[$stackPtr]['line'])
                            {
                                break;
                            }

                            $phpcsFile->fixer->replaceToken($i, '');
                        }

                        $phpcsFile->fixer->addNewline($prev);
                        $phpcsFile->fixer->endChangeset();
                    }
                }

                $start = $prev;
            }
        }

        // There needs to be 1 blank line before the var, not counting comments.
        if ($start === $stackPtr)
        {
            // No comment found.
            $first = $phpcsFile->findFirstOnLine(PHP_CodeSniffer_Tokens::$emptyTokens, $start, true);

            if ($first === false)
            {
                $first = $start;
            }
        }
        elseif ($tokens[$start]['code'] === T_DOC_COMMENT_CLOSE_TAG)
        {
            $first = $tokens[$start]['comment_opener'];
        }
        else
        {
            $first = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($start - 1), null, true);
            $first = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$commentTokens, ($first + 1));
        }

        $prev       = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($first - 1), null, true);
        $foundLines = ($tokens[$first]['line'] - $tokens[$prev]['line'] - 1);

        // No blank lines after class opener.
        if ($foundLines > 0 && $tokens[$prev]['code'] === T_OPEN_CURLY_BRACKET)
        {
            $error = 'Expected 0 blank lines before first member var; %s found';
            $data  = [$foundLines];
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'FirstMember', $data);

            if ($fix === true)
            {
                $phpcsFile->fixer->beginChangeset();

                for ($i = ($prev + 1); $i < $first; $i++)
                {
                    if ($tokens[$i]['line'] === $tokens[$prev]['line'])
                    {
                        continue;
                    }

                    if ($tokens[$i]['line'] === $tokens[$first]['line'])
                    {
                        break;
                    }

                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $phpcsFile->fixer->endChangeset();
            }
        }

        if ($foundLines === 1
            || $tokens[$prev]['code'] === T_OPEN_CURLY_BRACKET
        )
        {
            return;
        }

        $error = 'Expected 1 blank line before member var; %s found';
        $data  = [$foundLines];
        $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'Incorrect', $data);

        if ($fix === true)
        {
            $phpcsFile->fixer->beginChangeset();

            for ($i = ($prev + 1); $i < $first; $i++)
            {
                if ($tokens[$i]['line'] === $tokens[$prev]['line'])
                {
                    continue;
                }

                if ($tokens[$i]['line'] === $tokens[$first]['line'])
                {
                    $phpcsFile->fixer->addNewlineBefore($i);
                    break;
                }

                $phpcsFile->fixer->replaceToken($i, '');
            }

            $phpcsFile->fixer->endChangeset();
        }
    }
}
