<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{

    /**
     * @method getSupervisorsPermissions gets all permissions corresponded to supervisors
     * @return array of all supervisors permissions
     */
    public static function getSupervisorsPermissions(): array
    {
        $permissions = [
            'supervisors::add',
            'supervisors::remove',
            'supervisors::retrieve',
            'supervisors::update',
            'supervisors::view_role',
            'supervisors::set_role',
            'supervisors::revoke_role',
            'supervisors::view_permissions',
        ];

        return $permissions;
    }

    /**
     * @method getFeesPermissions gets all permissions corresponded to fees
     * @return array of all fees permissions
     */
    public static function getFeesPermissions(): array
    {
        $permissions = [
            'fees::add',
            'fees::remove',
            'fees::retrieve',
            'fees::update',
        ];

        return $permissions;
    }

    /**
     * @method getInstallmentsPermissions gets all permissions corresponded to installments
     * @return array of all fees installments
     */
    public static function getInstallmentsPermissions(): array
    {
        $permissions = [
            'installments::add',
            'installments::remove',
            'installments::retrieve',
            'installments::update',
        ];

        return $permissions;
    }

    /**
     * @method getReductionsPermissions gets all permissions corresponded to reductions
     * @return array of all fees reductions
     */
    public static function getReductionsPermissions(): array
    {
        $permissions = [
            'reductions::add',
            'reductions::remove',
            'reductions::retrieve',
            'reductions::update',
        ];

        return $permissions;
    }

    /**
     * @method getStudentsPermissions gets all permissions corresponded to students
     * @return array of all fees students
     */
    public static function getStudentsPermissions(): array
    {
        $permissions = [
            'students::add',
            'students::remove',
            'students::retrieve',
            'students::update',
        ];

        return $permissions;
    }

    /**
     * @method getPaymentsPermissions gets all permissions corresponded to payments
     * @return array of all fees payments
     */
    public static function getPaymentsPermissions(): array
    {
        $permissions = [
            'payments::add',
            'payments::remove',
            'payments::retrieve',
            'payments::update',
        ];

        return $permissions;
    }



    /**
     * @method getRolesPermissions gets all permissions corresponded to roles
     * @return array of all roles permissions
     */
    public static function getRolesPermissions(): array
    {
        $permissions = [
            'roles::add',
            'roles::remove',
            'roles::retrieve',
            'roles::update',
            'roles::view_permissions',
            'roles::set_permissions',
        ];

        return $permissions;
    }


    /**
     * @method getSystemPermissions gets all valid permissions
     * @return array of all valid permissions
     */
    public static function getSystemPermissions(): array
    {
        $global = [
            'permissions::retrieve',
            'activity_logs::retrieve'
        ];
        $permissionsSets = [
            $global,
            static::getSupervisorsPermissions(),
            static::getRolesPermissions(),
            static::getFeesPermissions(),
            static::getInstallmentsPermissions(),
            static::getReductionsPermissions(),
            static::getStudentsPermissions(),
            static::getPaymentsPermissions(),
        ];

        $systemPermissions = [];
        foreach ($permissionsSets as $permissions) {
            $systemPermissions = array_merge($systemPermissions, $permissions);
        }

        return $systemPermissions;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allPermissions = PermissionSeeder::getSystemPermissions();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::find(RoleSeeder::rootSupervisor['id']);

        foreach ($allPermissions as $permissionName) {
            Permission::create(['name' => $permissionName, 'guard_name' => 'supervisors']);
            $role->givePermissionTo($permissionName);
        }
    }
}
