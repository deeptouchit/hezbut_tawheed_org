<?php

use Spatie\Permission\DefaultTeamResolver;
use App\Models\Permission;  // ← আপনার কাস্টম Permission মডেল
use App\Models\Role;        // ← আপনার কাস্টম Role মডেল

return [

    'models' => [
          /*
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */
        'permission' => Permission::class,   // ← কাস্টম Permission মডেল

          /*
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */
        'role' => Role::class,   // ← কাস্টম Role মডেল
    ],

    'table_names' => [
        'roles'                 => 'roles',
        'permissions'           => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles'       => 'model_has_roles',
        'role_has_permissions'  => 'role_has_permissions',
    ],

    'column_names' => [
        'role_pivot_key'       => null,
        'permission_pivot_key' => null,
        'model_morph_key'      => 'model_id',
        'team_foreign_key'     => 'team_id',
    ],

    'register_permission_check_method' => true,
    'register_octane_reset_listener'   => false,
    'events_enabled'                   => false,
    'teams'                            => false,
    'team_resolver'                    => DefaultTeamResolver::class,
    'use_passport_client_credentials'  => false,
    'display_permission_in_exception'  => false,
    'display_role_in_exception'        => false,
    'enable_wildcard_permission'       => false,

    'cache' => [
        'expiration_time' => DateInterval::createFromDateString('24 hours'),
        'key'             => 'spatie.permission.cache',
        'store'           => 'default',
    ],
];
