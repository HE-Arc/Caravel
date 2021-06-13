<?php

namespace App\Ldap;

use App\Models\User as DatabaseUser;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;

class AttributeHandler
{
    public function handle(LdapUser $ldap, DatabaseUser $user)
    {
        //set isLdap, set isProf
        $user->isLDAP = 1;
        //$database->name = $ldap->getFirstAttribute('cn');
        //$database->email = $ldap->getFirstAttribute('mail');
    }
}
