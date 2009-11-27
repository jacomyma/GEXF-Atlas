<?php
/**
 * File containing the ezcWebdavErrorResponse class.
 *
 * @package Webdav
 * @version 1.1
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * Class generated by the backend to indicate an error
 *
 * If a {@link ezcWebdavBackend} produces an error, it will return an instance
 * of this class, which might also be encapsulated in an {@link
 * ezcWebdavMultistatusResponse}.
 *
 * @property string|null $requestUri
 *           Path refering to the resource that produced the error.
 * @property string|null $responseDescription
 *           Details about the error.
 *
 * @version 1.1
 * @package Webdav
 */
class ezcWebdavErrorResponse extends ezcWebdavResponse
{
    /**
     * Creates a new response object.
     *
     * Creates a new error response, with the given $status code, which refers
     * to the resource identified by $requestUri. Further details about the
     * error may be provided in $desc.
     * 
     * @param int $status 
     * @param string $requestUri 
     * @param string $desc
     * @return void
     */
    public function __construct( $status, $requestUri = null, $desc = null )
    {
        parent::__construct( $status );

        // Initialize property
        $this->properties['requestUri'] = null;

        if ( $requestUri !== null )
        {
            $this->requestUri = $requestUri;
        }
        if ( $desc !== null )
        {
            $this->responseDescription = $desc;
        }
    }

    /**
     * Sets a property.
     *
     * This method is called when an property is to be set.
     * 
     * @param string $propertyName The name of the property to set.
     * @param mixed $propertyValue The property value.
     * @return void
     * @ignore
     *
     * @throws ezcBasePropertyNotFoundException
     *         if the given property does not exist.
     * @throws ezcBaseValueException
     *         if the value to be assigned to a property is invalid.
     * @throws ezcBasePropertyPermissionException
     *         if the property to be set is a read-only property.
     */
    public function __set( $propertyName, $propertyValue )
    {
        switch ( $propertyName )
        {
            case 'requestUri':
                if ( !is_string( $propertyValue ) )
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, 'string' );
                }

                $this->properties[$propertyName] = $propertyValue;
                break;
            case 'responseDescription':
               if ( $this->responseDescription !== null )
               {
                    $this->setHeader( 'Warning', 'eZComponents error "' . $this->responseDescription . '"' );
               }
               parent::__set( $propertyName, $propertyValue );
               break;
            default:
                parent::__set( $propertyName, $propertyValue );
        }
    }
}

?>