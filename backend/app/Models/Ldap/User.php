<?php 

namespace App\Models\Ldap;

/**
 * This class is used only for proxy purpose, to easily switch between OpenLDAP and ActiveDirectory or other LDAP implementations
*/
use LdapRecord\Models\OpenLDAP\User as LdapUser;

Class User extends LdapUser {

}