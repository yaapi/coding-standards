<?php
/**
 * @name        InstantiateNewClassesUnitTest
 * @package     Yaapi\Coding-Standards
 * @copyright   Copyright (C) 2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

/**
 * InstantiateNewClassesUnitTest
 *
 * @since   1.0
 */
class YaAPI_Tests_Classes_InstantiateNewClassesUnitTest extends AbstractSniffUnitTest
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
                16 => 1,
                17 => 1,
                18 => 1,
                24 => 1,
                25 => 1,
                27 => 1,
                28 => 1,
                30 => 1,
                31 => 1,
                33 => 1,
                34 => 1,
                36 => 1,
                37 => 1,
                39 => 1,
                40 => 1,
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
