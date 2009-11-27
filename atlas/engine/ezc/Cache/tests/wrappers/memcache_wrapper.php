<?php
/**
 * File containing the ezcCacheStorageMemcacheWrapper class.
 *
 * @package Cache
 * @version 1.4.1
 * @subpackage Tests
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @filesource
 */

/**
 * Access to the $registry field. For testing purposes only.
 *
 * @package Cache
 * @version 1.4.1
 * @subpackage Tests
 */
class ezcCacheStorageMemcacheWrapper extends ezcCacheStorageMemcachePlain
{
    /**
     * Sets the static field $registry with the provided value.
     *
     * @param array(string=>mixed) $registry
     */
    public function setRegistry( array $registry = array() )
    {
        $this->registry = $registry;
    }

    /**
     * Returns the static field $registry.
     *
     * @return array(string=>mixed)
     */
    public function getRegistry()
    {
        return $this->registry;
    }
}
?>
