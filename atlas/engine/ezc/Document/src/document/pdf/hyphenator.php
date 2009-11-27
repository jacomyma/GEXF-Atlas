<?php
/**
 * File containing the ezcDocumentPdfHyphenator class
 *
 * @package Document
 * @version 1.1.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * Abstract base class for hyphenation implementations.
 *
 * Hyphenation implementations are responsbile for language dependant splitting
 * of words into hyphens, for better text wrapping especially in justified
 * paragraphs.
 *
 * @package Document
 * @access private
 * @version 1.1.2
 */
abstract class ezcDocumentPdfHyphenator
{
    /**
     * Split word into hypens
     *
     * Takes a word as a string and should return an array containing arrays of
     * two words, which each represent a possible split of a word. The german
     * word "Zuckerstück" for example changes its hyphens depending on the
     * splitting point, so the return value would look like:
     *
     * <code>
     *  array(
     *      array( 'Zuk', 'kerstück' ),
     *      array( 'Zucker', 'stück' ),
     *  )
     * </code>
     * 
     * @param mixed $word 
     * @return void
     */
    abstract public function splitWord( $word );
}
?>
