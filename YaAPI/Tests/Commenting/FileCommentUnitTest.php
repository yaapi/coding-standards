<?php
/**
 * @name        FileCommentUnitTest
 * @package     Yaapi\Coding-Standards
 * @copyright   Copyright (C) 2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

/**
 * FileCommentUnitTest
 *
 * @since   1.0
 */
class YaAPI_Tests_Commenting_FileCommentUnitTest extends AbstractSniffUnitTest
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
                16 => 2,
                17 => 1,
                18 => 2,
                20 => 1,
                22 => 1,
                23 => 1,
                24 => 1,
                25 => 1,
                26 => 2,
                27 => 1,
                28 => 1,
                29 => 1,
                34 => 2,
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
        return [
                23 => 1,
                28 => 1,
               ];
    }
}
