<?php
// Autogenerated class file

/**
 * Data class ce_bad_word.
 * Class to be used with eZ Components PersistentObject.
 */
class ce_bad_word
{
    /**
     * badword_id
     *
     * @var int
     */
    private $badword_id;
    /**
     * substitution
     *
     * @var string
     */
    private $substitution;
    /**
     * word
     *
     * @var string
     */
    private $word;

    /**
     * Set the PersistentObject state.
     *
     * @param array(string=>mixed) $state The state to set.
     * @return void
     */
     public function setState( array $state )
     {
         foreach ( $state as $attribute => $value )
         {
             $this->$attribute = $value;
         }
     }

    /**
     * Get the PersistentObject state.
     *
     * @return array(string=>mixed) The state of the object.
     */
     public function getState()
     {
         return array(
             'badword_id' => $this->badword_id,
             'substitution' => $this->substitution,
             'word' => $this->word,
         );
     }
}
?>
