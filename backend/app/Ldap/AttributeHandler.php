<?php

namespace App\Ldap;

use App\Models\User as DatabaseUser;
use App\Models\Ldap\User as LdapUser;

class AttributeHandler
{
    public function handle(LdapUser $ldap, DatabaseUser $database)
    {
        //set isLdap, set isProf
        //$database->name = $ldap->getFirstAttribute('cn');
        //$database->email = $ldap->getFirstAttribute('mail');
    }
}