<?php
/**
 * @name        ControlStructuresBracketsSniff
 * @package     Yaapi\Coding-Standards
 * @copyright   Copyright (C) 2014-2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

/**
 * Control Structures Brackets Test.
 *
 * Checks if the declaration of control structures is correct.
 * Curly brackets must be on a line by their own.
 */
class YaAPI_Sniffs_ControlStructures_ControlStructuresBracketsSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * The number of spaces code should be indented.
     *
     * @var int
     */
    public $indent = 4;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
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
        $errorData = [strtolower($tokens[$stackPtr]['content'])];

        if (false === isset($tokens[$stackPtr]['scope_opener']))
        {
            $error = 'Possible parse error: %s missing opening or closing brace';
            $phpcsFile->addWarning($error, $stackPtr, 'MissingBrace', $errorData);
            return;
        }

        $curlyBrace  = $tokens[$stackPtr]['scope_opener'];
        $lastContent = $phpcsFile->findPrevious(T_WHITESPACE, ($curlyBrace - 1), $stackPtr, true);
        $classLine   = $tokens[$lastContent]['line'];
        $braceLine   = $tokens[$curlyBrace]['line'];
        
        if ($braceLine === $classLine)
        {
            $phpcsFile->recordMetric($stackPtr, 'Class opening brace placement', 'same line');
            $error = 'Opening brace of a %s must be on the line after the definition';
            $fix   = $phpcsFile->addFixableError($error, $curlyBrace, 'OpenBraceNewLine', $errorData);
            
            if (true === $fix)
            {
                $phpcsFile->fixer->beginChangeset();
                
                if (T_WHITESPACE === $tokens[($curlyBrace - 1)]['code'])
                {
                    $phpcsFile->fixer->replaceToken(($curlyBrace - 1), '');
                }

                $phpcsFile->fixer->addNewlineBefore($curlyBrace);
                $phpcsFile->fixer->endChangeset();
            }

            return;
        }
        else
        {
            $phpcsFile->recordMetric($stackPtr, 'Class opening brace placement', 'new line');

            if ($braceLine > ($classLine + 1))
            {
                $error = 'Opening brace of a %s must be on the line following the %s declaration.; Found %s line(s).';
                $data  = [
                          $tokens[$stackPtr]['content'],
                          $tokens[$stackPtr]['content'],
                          ($braceLine - $classLine - 1),
                         ];
                $fix   = $phpcsFile->addFixableError($error, $curlyBrace, 'OpenBraceWrongLine', $data);
                
                if (true === $fix)
                {
                    $phpcsFile->fixer->beginChangeset();
                    
                    for ($i = ($curlyBrace - 1); $i > $lastContent; $i--)
                    {
                        if ($tokens[$i]['line'] === ($tokens[$curlyBrace]['line'] + 1))
                        {
                            break;
                        }

                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->endChangeset();
                }

                return;
            }
        }

        if ($tokens[($curlyBrace + 1)]['content'] !== $phpcsFile->eolChar)
        {
            $error = 'Opening %s brace must be on a line by itself.';
            $fix   = $phpcsFile->addFixableError($error, $curlyBrace, 'OpenBraceNotAlone', $errorData);
            
            if (true === $fix)
            {
                $phpcsFile->fixer->addNewline($curlyBrace);
            }
        }

        if (T_WHITESPACE === $tokens[($curlyBrace - 1)]['code'])
        {
            $prevContent = $tokens[($curlyBrace - 1)]['content'];
            
            if ($prevContent === $phpcsFile->eolChar)
            {
                $spaces = 0;
            }
            else
            {
                $blankSpace = substr($prevContent, strpos($prevContent, $phpcsFile->eolChar));
                $spaces     = strlen($blankSpace);
            }

            $expected = ($tokens[$stackPtr]['level'] * $this->indent);
            
            if ($spaces !== $expected)
            {
                $error = 'Expected %s spaces before opening brace; %s found';
                $data  = [$expected, $spaces];
                $fix   = $phpcsFile->addFixableError($error, $curlyBrace, 'SpaceBeforeBrace', $data);
                
                if ($fix === true)
                {
                    $indent = str_repeat(' ', $expected);
                    
                    if ($spaces === 0)
                    {
                        $phpcsFile->fixer->addContentBefore($curlyBrace, $indent);
                    }
                    else
                    {
                        $phpcsFile->fixer->replaceToken(($curlyBrace - 1), $indent);
                    }
                }
            }
        }
    }
}
