<?php
/**
 * @name        ClassCommentSniff
 * @package     Yaapi\Coding-Standards
 * @copyright   Copyright (C) 2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

if (class_exists('PEAR_Sniffs_Commenting_ClassCommentSniff', true) === false)
{
    throw new PHP_CodeSniffer_Exception('Class PEAR_Sniffs_Commenting_ClassCommentSniff not found.');
}

/**
 * Parses and verifies the doc comments for classes.
 *
 * @since   1.0
 */
class YaAPI_Sniffs_Commenting_ClassCommentSniff extends PEAR_Sniffs_Commenting_ClassCommentSniff
{
    /**
     * Tags in correct order and related info.
     *
     * @var  array
     */
    protected $tags = [
                       '@version'    => [
                                         'required'       => false,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must be first',
                                        ],
                       '@category'   => [
                                         'required'       => false,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must follow @version (if used)',
                                        ],
                       '@package'    => [
                                         'required'       => false,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must follow @category (if used)',
                                        ],
                       '@subpackage' => [
                                         'required'       => false,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must follow @package',
                                        ],
                       '@author'     => [
                                         'required'       => false,
                                         'allow_multiple' => true,
                                         'order_text'     => 'is first',
                                        ],
                       '@copyright'  => [
                                         'required'       => false,
                                         'allow_multiple' => true,
                                         'order_text'     => 'must follow @author (if used) or @subpackage (if used) or @package',
                                        ],
                       '@license'    => [
                                         'required'       => false,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must follow @copyright (if used)',
                                        ],
                       '@link'       => [
                                         'required'       => false,
                                         'allow_multiple' => true,
                                         'order_text'     => 'must follow @version (if used)',
                                        ],
                       '@see'        => [
                                         'required'       => false,
                                         'allow_multiple' => true,
                                         'order_text'     => 'must follow @link (if used)',
                                        ],
                       '@since'      => [
                                         'required'       => true,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must follow @see (if used) or @link (if used)',
                                        ],
                       '@deprecated' => [
                                         'required'       => false,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must follow @since (if used) or @see (if used) or @link (if used)',
                                        ],
                      ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return  array
     */
    public function register()
    {
        return [
                T_CLASS,
                T_INTERFACE,
                T_TRAIT,
               ];
    }
}
