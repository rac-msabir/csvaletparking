<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\UserProvider as UserProviderContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class AdminUserProvider extends EloquentUserProvider
{
    /**
     * The user model to use.
     *
     * @var string
     */
    protected $model;

    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct(HasherContract $hasher, $model)
    {
        parent::__construct($hasher, $model);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $user = parent::retrieveByCredentials($credentials);

        // Only return users who are active and either a super admin, tenant admin, or employee
        if ($user && $user->is_active && ($user->is_super_admin || $user->is_tenant_admin || $user->is_employee)) {
            return $user;
        }

        return null;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        // First validate the password
        if (!parent::validateCredentials($user, $credentials)) {
            return false;
        }

        // Then check if the user is active
        if (!$user->is_active) {
            return false;
        }

        // Check if the user has the required role
        if (!($user->is_super_admin || $user->is_tenant_admin || $user->is_employee)) {
            return false;
        }

        return true;
    }
}
