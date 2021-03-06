<?php
/**
 * File containing the ezcLogWriterException class.
 *
 * @package EventLog
 * @version 1.3
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * The ezcLogWriterException will be thrown when an {@link ezcLogWriter} or
 * a subclass encounters an exceptional state.
 *
 * This exception is a container, containing any kind of exception.
 *
 * @apichange Remove the wrapping of exceptions.
 * @package EventLog
 * @version 1.3
 */
class ezcLogWriterException extends ezcBaseException
{
    /**
     * The wrapped exception.
     *
     * @var Exception
     */
    public $exception;

    /**
     * Constructs a new ezcLogWriterException with the original exception $e.
     *
     * @param Exception $e
     */
    public function __construct( Exception $e )
    {
        $this->exception = $e;
        parent::__construct( $e->getMessage() );
    }
}
?>
