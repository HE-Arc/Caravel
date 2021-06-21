<?php

namespace App\Ldap;

use App\Models\User as DatabaseUser;
use LdapRecord\Models\ActiveDirectory\OrganizationalUnit;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;

class AttributeHandler
{
    public function handle(LdapUser $ldap, DatabaseUser $user)
    {
        //set isLdap, set isProf
        $user->isLDAP = 1;
        $allowedOUs = explode(";", env("LDAP_TEACHERS_OUs", ""));
        $allowedOUs = array_filter($allowedOUs);

        //check if user is in a allowedOU to
        if (!empty($allowedOUs)) {
            foreach ($allowedOUs as $dn) {
                $ou = OrganizationalUnit::find($dn);
                if ($ou && $ldap->isDescendantOf($ou)) {
                    $user->isTeacher = 1;
                    return;
                }
            }
        }

        $user->isTeacher = 0;
    }
}
