<?php
/**
 * @name        ControlStructuresBracketsExistsSniff
 * @package     Yaapi\Coding-Standards
 * @copyright   Copyright (C) 2014-2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

/**
 * Ensures curly brackets are used with if, else, elseif, foreach and for.
 * while and dowhile are covered elsewhere
 *
 */
class YaAPI_Sniffs_ControlStructures_ControlStructuresBracketsExistsSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $a = ['test', 'test1'];
        return [
                T_IF,
                T_ELSEIF,
                T_ELSE,
                T_FOREACH,
                T_FOR,
                T_SWITCH,
                T_DO,
                T_WHILE,
                T_TRY,
                T_CATCH,
               ];

    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $nextToken = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        
        if ($tokens[$nextToken]['code'] === T_OPEN_PARENTHESIS)
        {
            $closer = $tokens[$nextToken]['parenthesis_closer'];
            $diff = $closer - $stackPtr;
            $nextToken = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + $diff + 1), null, true);
        }
        
        if ($tokens[$nextToken]['code'] === T_IF)
        {
            // "else if" is not checked by this sniff, another sniff takes care of that.
            return;
        }
        
        if ($tokens[$nextToken]['code'] !== T_OPEN_CURLY_BRACKET && $tokens[$nextToken]['code'] !== T_COLON)
        {
            $error = 'Curly brackets required for if/elseif/else/foreach/for.';
            $phpcsFile->addError($error, $stackPtr, 'NotAllowed');
        }
    }
}
