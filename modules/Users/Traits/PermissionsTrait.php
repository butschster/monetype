<?php

namespace Modules\Users\Traits;

use Modules\Users\Model\Role;
use Modules\Users\Model\Permission;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PermissionsTrait
 * @package Modules\Users\Traits
 *
 * @property Collection     $roles
 */
trait PermissionsTrait
{

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole(Role::ROLE_ADMIN);
    }


    /**
     * @return bool
     */
    public function isModerator()
    {
        return $this->hasRole(Role::ROLE_MODERATOR);
    }


    /**
     * Assign the given role to the user.
     *
     * @param  string $role
     *
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->save(Role::whereName($role)->firstOrFail());
    }


    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if (is_string($role) && strpos($role, '|') !== false) {
            return ! ! count(array_intersect(explode('|', $role), $this->roles->lists('name')->all()));
        }

        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return ! ! $role->intersect($this->roles)->count();
    }


    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Permission $permission
     *
     * @return boolean
     */
    public function hasPermission(Permission $permission)
    {
        return $this->isAdmin() or $this->hasRole($permission->roles);
    }

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}