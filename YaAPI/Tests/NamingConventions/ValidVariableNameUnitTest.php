<?php
/**
 * @name        ValidVariableUnitTest
 * @package     Yaapi\Coding-Standards
 * @copyright   Copyright (C) 2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

/**
 * ValidVariableUnitTest
 *
 * @since   1.0
 */
class YaAPI_Tests_NamingConventions_ValidVariableUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getErrorList()
    {
        return [
                4  => 1,
                6 => 1,
               ];
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getWarningList()
    {
        return [];
    }
}
