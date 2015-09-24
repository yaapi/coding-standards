<?php
/**
 * @name        MemberVarSpacingUnitTest
 * @package     Yaapi\Coding-Standards
 * @copyright   Copyright (C) 2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

/**
 * InstantiateNewClassesUnitTest
 *
 * @since   1.0
 */
class YaAPI_Tests_WhiteSpace_MemberVarSpacingUnitTest extends AbstractSniffUnitTest
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
                5   => 1,
                8   => 1,
                23  => 1,
                28  => 1,
                42  => 1,
                45  => 1,
                51  => 1,
                54  => 1,
                69  => 1,
                74  => 1,
                88  => 1,
                91  => 1,
                103 => 1,
                106 => 1,
                113 => 1,
                128 => 1,
                131 => 1,
                138 => 1,
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
