<?php

namespace App\Ldap;

use App\Models\Employee;
use LdapRecord\Models\Model;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Models\Concerns\CanAuthenticate;
use LdapRecord\Models\Concerns\HasPassword;
use LdapRecord\Models\OpenLDAP\Entry;
use LdapRecord\Models\OpenLDAP\Group;
use LdapRecord\Models\Relations\HasMany;


class UserLdap extends Entry implements Authenticatable
{
    /**
     * The object classes of the LDAP model.
     */
   // public static array $objectClasses = [];

    use HasPassword;
    use CanAuthenticate;

    /**
     * The password's attribute name.
     */
    protected string $passwordAttribute = 'userpassword';

    /**
     * The password's hash method.
     */
    protected string $passwordHashMethod = 'ssha';

    /**
     * The object classes of the LDAP model.
     */
    public static array $objectClasses = [
        'top',
        'person',
        'organizationalperson',
        'inetorgperson',
    ];

    /**
     * The groups relationship.
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'uniquemember');
    }
    
    public function employee() 
    {
       
        return Employee::where('email', Auth::user()->mail[0])->first();
    }
}
