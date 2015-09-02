<?php
/**
 * @name        FileCommentSniff
 * @package     Yaapi\Coding-Standards
 * @copyright   Copyright (C) 2015 JoomWorker - http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

if (class_exists('PEAR_Sniffs_Commenting_FileCommentSniff', true) === false)
{
    throw new PHP_CodeSniffer_Exception('Class PEAR_Sniffs_Commenting_FileCommentSniff not found.');
}

/**
 * Parses and verifies the doc comments for files.
 *
 * @since   1.0
 */
class YaAPI_Sniffs_Commenting_FileCommentSniff extends PEAR_Sniffs_Commenting_FileCommentSniff
{
    /**
     * Tags in correct order and related info.
     *
     * @var   array
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
                                         'order_text'     => 'precedes @package',
                                        ],
                       '@package'    => [
                                         'required'       => false,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must follows @category (if used)',
                                        ],
                       '@subpackage' => [
                                         'required'       => false,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must follow @package',
                                        ],
                       '@author'     => [
                                         'required'       => false,
                                         'allow_multiple' => true,
                                         'order_text'     => 'must follow @subpackage (if used) or @package',
                                        ],
                       '@copyright'  => [
                                         'required'       => true,
                                         'allow_multiple' => true,
                                         'order_text'     => 'must follow @author (if used), @subpackage (if used) or @package',
                                        ],
                       '@license'    => [
                                         'required'       => true,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must follow @copyright',
                                        ],
                       '@link'       => [
                                         'required'       => false,
                                         'allow_multiple' => true,
                                         'order_text'     => 'must follow @license',
                                        ],
                       '@see'        => [
                                         'required'       => false,
                                         'allow_multiple' => true,
                                         'order_text'     => 'must follow @link (if used) or @license',
                                        ],
                       '@since'      => [
                                         'required'       => false,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must follows @see (if used), @link (if used) or @license',
                                        ],
                       '@deprecated' => [
                                         'required'       => false,
                                         'allow_multiple' => false,
                                         'order_text'     => 'must follow @since (if used), @see (if used), @link (if used) or @license',
                                        ],
                      ];

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * Note: Removed code for checking the PHP version.
     *
     * @param   PHP_CodeSniffer_File  $phpcsFile The file being scanned.
     * @param   int                   $stackPtr  The position of the current token
     *                                           in the stack passed in $tokens.
     *
     * @return integer
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Find the next non whitespace token.
        $commentStart = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);

        // Allow declare() statements at the top of the file.
        if ($tokens[$commentStart]['code'] === T_DECLARE)
        {
            $semicolon    = $phpcsFile->findNext(T_SEMICOLON, ($commentStart + 1));
            $commentStart = $phpcsFile->findNext(T_WHITESPACE, ($semicolon + 1), null, true);
        }

        // Ignore vim header.
        if ($tokens[$commentStart]['code'] === T_COMMENT)
        {
            if (strstr($tokens[$commentStart]['content'], 'vim:') !== false)
            {
                $commentStart = $phpcsFile->findNext(
                    T_WHITESPACE,
                    ($commentStart + 1),
                    null,
                    true
                );
            }
        }

        $errorToken = ($stackPtr + 1);

        if (isset($tokens[$errorToken]) === false)
        {
            $errorToken--;
        }

        if ($tokens[$commentStart]['code'] === T_CLOSE_TAG)
        {
            // We are only interested if this is the first open tag.
            return ($phpcsFile->numTokens + 1);
        }
        elseif ($tokens[$commentStart]['code'] === T_COMMENT)
        {
            $error = 'You must use "/**" style comments for a file comment';
            $phpcsFile->addError($error, $errorToken, 'WrongStyle');
            $phpcsFile->recordMetric($stackPtr, 'File has doc comment', 'yes');

            return ($phpcsFile->numTokens + 1);
        }
        elseif ($commentStart === false
            || $tokens[$commentStart]['code'] !== T_DOC_COMMENT_OPEN_TAG
        )
        {
            $phpcsFile->addError('Missing file doc comment', $errorToken, 'Missing');
            $phpcsFile->recordMetric($stackPtr, 'File has doc comment', 'no');

            return ($phpcsFile->numTokens + 1);
        }
        else
        {
            $phpcsFile->recordMetric($stackPtr, 'File has doc comment', 'yes');
        }

        // Check each tag.
        $this->processTags($phpcsFile, $stackPtr, $commentStart);

        // Ignore the rest of the file.
        return ($phpcsFile->numTokens + 1);
    }//end process()

    /**
     * Process the copyright tags.
     *
     * Note: Changed preg_match regex to allow string(s) before the year.
     *
     * @param   PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param   array                $tags      The tokens for these tags.
     *
     * @return  void
     */
    protected function processCopyright(PHP_CodeSniffer_File $phpcsFile, array $tags)
    {
        $tokens = $phpcsFile->getTokens();

        foreach ($tags as $tag)
        {
            if ($tokens[($tag + 2)]['code'] !== T_DOC_COMMENT_STRING)
            {
                // No content.
                continue;
            }

            $content = $tokens[($tag + 2)]['content'];
            $matches = [];

            if (preg_match('/^.*?([0-9]{4})((.{1})([0-9]{4}))? (.+)$/', $content, $matches) !== 0)
            {
                // Check earliest-latest year order.
                if ($matches[3] !== '')
                {
                    if ($matches[3] !== '-')
                    {
                        $error = 'A hyphen must be used between the earliest and latest year';
                        $phpcsFile->addError($error, $tag, 'CopyrightHyphen');
                    }

                    if ($matches[4] !== '' && $matches[4] < $matches[1])
                    {
                        $error = "Invalid year span \"$matches[1]$matches[3]$matches[4]\" found; consider \"$matches[4]-$matches[1]\" instead";
                        $phpcsFile->addWarning($error, $tag, 'InvalidCopyright');
                    }
                }
            }
            else
            {
                $error = '@copyright tag must contain a year and the name of the copyright holder';
                $phpcsFile->addError($error, $tag, 'IncompleteCopyright');
            }
        }//end foreach
    }//end processCopyright()
}//end class
