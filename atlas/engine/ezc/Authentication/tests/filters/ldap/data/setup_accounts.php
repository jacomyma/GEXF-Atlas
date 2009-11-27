<?php
/**
 * Setup the test accounts on the ldap server.
 *
 * Assumes the username entries are 'uid'.
 *
 */

$dc = "dc=foo,dc=bar";

$connection = Ldap::connect( 'ldap://localhost', "cn=%s,{$dc}", 'admin', 'wee123' );

Ldap::delete( $connection, 'john.doe', $dc );
Ldap::delete( $connection, 'jan.modaal', $dc );
Ldap::delete( $connection, 'zhang.san', $dc );
Ldap::delete( $connection, 'hans.mustermann', $dc );
Ldap::delete( $connection, 'Ruşinică Piţigoi', $dc );

Ldap::add( $connection, 'john.doe', '{CRYPT}' . crypt( 'foobar' ), $dc );
Ldap::add( $connection, 'jan.modaal', '{SHA}' . base64_encode( pack( 'H*', sha1( 'qwerty' ) ) ), $dc );
Ldap::add( $connection, 'zhang.san', '{MD5}' . base64_encode( pack( 'H*', md5( 'asdfgh' ) ) ), $dc );
//Ldap::add( $connection, 'jan.modaal', '{SHA}' . base64_encode( sha1( 'qwerty' ) ), $dc );
//Ldap::add( $connection, 'zhang.san', '{MD5}' . base64_encode( md5( 'asdfgh' ) ), $dc );
Ldap::add( $connection, 'hans.mustermann', 'abcdef', $dc );
Ldap::add( $connection, 'Ruşinică Piţigoi', '12345', $dc );

Ldap::fetchAll( $connection, $dc );

Ldap::close( $connection );


/**
 * Support for LDAP functions connect, add, delete and get_entries.
 */
class Ldap
{
    /**
     * Connects to an LDAP server specified by $uri, with admin $user and $password.
     *
     * Returns a resource which can be used in LDAP functions like add, delete, search.
     *
     * @param string $uri Uri for LDAP, such as 'ldap://example.com'
     * @param string $format Format for an entry, like 'cn=%s,dc=example,dc=com'. %s is a literal placeholder for username
     * @param string $user Admin username
     * @param string $password Password for admin
     * @return resource
     */
    public static function connect( $uri, $format, $user, $password )
    {
        if ( !extension_loaded( 'ldap' ) )
        {
            die( 'LDAP extension is not loaded.' );
        }
        $connection = ldap_connect( $uri );
        if ( !$connection )
        {
            throw new Exception( "Could not connect to host '{$uri}'" );
        }
        ldap_set_option( $connection, LDAP_OPT_PROTOCOL_VERSION, 3 );
        @ldap_bind( $connection, sprintf( $format, $user ), $password );
        $err = ldap_errno( $connection );
        switch ( $err )
        {
            case 0x51: // LDAP_SERVER_DOWN
            case 0x52: // LDAP_LOCAL_ERROR
            case 0x53: // LDAP_ENCODING_ERROR
            case 0x54: // LDAP_DECODING_ERROR
            case 0x55: // LDAP_TIMEOUT
            case 0x56: // LDAP_AUTH_UNKNOWN
            case 0x57: // LDAP_FILTER_ERROR
            case 0x58: // LDAP_USER_CANCELLED
            case 0x59: // LDAP_PARAM_ERROR
            case 0x5a: // LDAP_NO_MEMORY
                throw new Exception( "Could not connect to host '{$uri}'. (0x" . dechex( $err ) . ")" );
                break;
        }
        return $connection;
    }

    /**
     * Adds an entry in the LDAP directory.
     *
     * Throws a warning if the entry already exists.
     *
     * @param resource $connection Connection resource returned by ldap_connect()
     * @param string $user Username
     * @param string $password Password for username. Use an encryption function and put method in front of hash, like: '{MD5}hash'
     * @param string $dc The dc part of the entry, like: 'dc=example,dc=com'
     */
    public static function add( $connection, $user, $password, $dc )
    {
        $ldaprecord['uid'] = $user;
        $ldaprecord['objectclass'][0] = "account";
        $ldaprecord['objectclass'][1] = "simpleSecurityObject";
        $ldaprecord['objectclass'][2] = "top";
        $ldaprecord['userPassword'][0] = $password;
        $r = ldap_add( $connection, "uid={$user},{$dc}", $ldaprecord );
    }

    /**
     * Deletes an entry from the LDAP directory.
     *
     * @param resource $connection Connection resource returned by ldap_connect()
     * @param string $user Username to delete
     * @param string $dc The dc part of the entry, like: 'dc=example,dc=com'
     */
    public static function delete( $connection, $user, $dc )
    {
        ldap_delete( $connection, "uid={$user},{$dc}" );
    }

    /**
     * Returns an array of all the entries in the LDAP directory.
     *
     * @param resource $connection Connection resource returned by ldap_connect()
     * @param string $dc The dc part of the entry, like: 'dc=example,dc=com'
     * @return array(mixed)
     */
    public static function fetchAll( $connection, $dc )
    {
        $sr = ldap_search( $connection, $dc, '(&(uid=*))' );
        var_dump( ldap_get_entries( $connection, $sr ) );
    }

    /**
     * Closes the connection to the LDAP server.
     *
     * @param resource $connection Connection resource returned by ldap_connect()
     */
    public static function close( $connection )
    {
        ldap_close( $connection );
    }
}
?>
