<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\OrganizationUser;

class OrganizationUserPolicy
{
    /**
     * Determine if the user can view any organization users.
     * Read access is public, so this always returns true.
     */
    public function viewAny(OrganizationUser|Admin|null $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the organization user.
     * Read access is public, so this always returns true.
     */
    public function view(OrganizationUser|Admin|null $user, OrganizationUser $organizationUser): bool
    {
        return true;
    }

    /**
     * Determine if the user can create organization users.
     * Only admins can create organization users.
     */
    public function create(OrganizationUser|Admin|null $user): bool
    {
        return $user instanceof Admin;
    }

    /**
     * Determine if the user can update the organization user.
     * Only admins can update organization users.
     * Organization users cannot update other users.
     */
    public function update(OrganizationUser|Admin|null $user, OrganizationUser $organizationUser): bool
    {
        return $user instanceof Admin;
    }

    /**
     * Determine if the user can delete the organization user.
     * Only admins can delete organization users.
     * Organization users cannot delete other users.
     */
    public function delete(OrganizationUser|Admin|null $user, OrganizationUser $organizationUser): bool
    {
        return $user instanceof Admin;
    }
}
