<?php
namespace App\Policies;

use App\Models\User;
use App\Models\MaintenanceRequests;
use App\Enums\UserRole; // تأكد من استيراد Enum الأدوار

class MaintenanceRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, MaintenanceRequests $maintenanceRequests): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return !in_array($user->role, [
            UserRole::CHAIRMAN,
            UserRole::ACCOUNTANT,
            UserRole:: MAINTTECH
        ]);
    }

    public function update(User $user, MaintenanceRequests $maintenanceRequests): bool
    {
        return !in_array($user->role, [
            UserRole::CHAIRMAN,
            UserRole::ACCOUNTANT,
            UserRole:: CLIENT
        ]);
    }

    public function delete(User $user): bool
    {
        return !in_array($user->role, [
            UserRole::CHAIRMAN,
            UserRole::ACCOUNTANT,
            UserRole:: CLIENT,
            UserRole:: MAINTTECH
        ]);
    }

    public function restore(User $user, MaintenanceRequests $maintenanceRequests): bool
    {
        return !in_array($user->role, [
            UserRole::CHAIRMAN,
            UserRole::ACCOUNTANT,
        ]);
    }

    // public function forceDelete(User $user, MaintenanceRequests $maintenanceRequests): bool
    public function forceDelete(User $user): bool
    {
        return !in_array($user->role, [
            UserRole::CHAIRMAN,
            UserRole::ACCOUNTANT,
            UserRole:: CLIENT,
            UserRole:: MAINTTECH
        ]);
    }
}
