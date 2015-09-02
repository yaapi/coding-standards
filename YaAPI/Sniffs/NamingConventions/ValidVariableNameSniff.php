<?php
/**
 * @name        ValidVariableNameSniff
 * @package     Yaapi\Coding-Standards
 * @copyright   Copyright (C) 2014-2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

if (class_exists('Squiz_Sniffs_NamingConventions_ValidVariableNameSniff', true) === false)
{
    throw new PHP_CodeSniffer_Exception('Class Squiz_Sniffs_NamingConventions_ValidVariableNameSniff not found');
}

/**
 * Extended ruleset for checking the naming of variables and member variables.
 *
 * @since   1.0
 */
class YaAPI_Sniffs_NamingConventions_ValidVariableNameSniff extends Squiz_Sniffs_NamingConventions_ValidVariableNameSniff
{
    /**
     * Processes class member variables.
     *
     * Extends Squiz.NamingConventions.ValidVariableName.processMemberVar to remove the requirement for leading underscores on
     * private member vars.
     *
     * @param   PHP_CodeSniffer_File  $phpcsFile  The file being scanned.
     * @param   integer               $stackPtr   The position of the current token in the stack passed in $tokens.
     *
     * @return  void
     */
    protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $varName     = ltrim($tokens[$stackPtr]['content'], '$');
        $memberProps = $phpcsFile->getMemberProperties($stackPtr);

        if (empty($memberProps) === true)
        {
            // Couldn't get any info about this variable, which generally means it is invalid or possibly has a parse
            // error. Any errors will be reported by the core, so we can ignore it.
            return;
        }

        $errorData = [$varName];

        if (substr($varName, 0, 1) === '_')
        {
            $error = '%s member variable "%s" must not contain a leading underscore';
            $data  = [
                      ucfirst($memberProps['scope']),
                      $errorData[0],
                     ];
            $phpcsFile->addError($error, $stackPtr, 'ClassVarHasUnderscore', $data);

            return;
        }

        if (PHP_CodeSniffer::isCamelCaps($varName, false, true, false) === false)
        {
            $error = 'Member variable "%s" is not in valid camel caps format';
            $phpcsFile->addError($error, $stackPtr, 'MemberNotCamelCaps', $errorData);
        }
    }
}
