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
        $allowedOUs = explode(";", env("LDAP_TEACHERS_OUs", ""));

        //check if user is in a allowedOU to 
        foreach ($allowedOUs as $ou) {
            if ($ldap->inside($ou)) {
                $user->isTeacher = true;
                return;
            }
        }

        $user->isTeacher = false;
    }
}
