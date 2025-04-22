<?php

namespace Database\Seeders;

use App\Models\Language\Language;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\PermissionTranslation;
use App\Models\RoleTranslation;

class SalesRepPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = Language::all();

        $permissions = [
            // Sales Rep Levels
            'view-sales-rep-levels' => [
                'translations' => [
                    'ar' => 'عرض مستويات المندوبين',
                    'en' => 'view sales rep levels',
                ],
            ],
            'create-sales-rep-levels' => [
                'translations' => [
                    'ar' => 'إضافة مستوى مندوب',
                    'en' => 'create sales rep level',
                ],
            ],
            'update-sales-rep-levels' => [
                'translations' => [
                    'ar' => 'تحديث مستوى مندوب',
                    'en' => 'update sales rep level',
                ],
            ],
            'delete-sales-rep-levels' => [
                'translations' => [
                    'ar' => 'حذف مستوى مندوب',
                    'en' => 'delete sales rep level',
                ],
            ],
            'bulk-delete-sales-rep-levels' => [
                'translations' => [
                    'ar' => 'حذف مستويات المندوبين بشكل جماعي',
                    'en' => 'bulk delete sales rep levels',
                ],
            ],
            'update-sales-rep-level-status' => [
                'translations' => [
                    'ar' => 'تحديث حالة مستوى مندوب',
                    'en' => 'update sales rep level status',
                ],
            ],

            // Sales Rep Users
            'view-sales-rep-users' => [
                'translations' => [
                    'ar' => 'عرض مستخدمي المندوبين',
                    'en' => 'view sales rep users',
                ],
            ],
            'create-sales-rep-users' => [
                'translations' => [
                    'ar' => 'إضافة مستخدم مندوب',
                    'en' => 'create sales rep user',
                ],
            ],
            'update-sales-rep-users' => [
                'translations' => [
                    'ar' => 'تحديث مستخدم مندوب',
                    'en' => 'update sales rep user',
                ],
            ],
            'delete-sales-rep-users' => [
                'translations' => [
                    'ar' => 'حذف مستخدم مندوب',
                    'en' => 'delete sales rep user',
                ],
            ],
            'bulk-delete-sales-rep-users' => [
                'translations' => [
                    'ar' => 'حذف مستخدمي المندوبين بشكل جماعي',
                    'en' => 'bulk delete sales rep users',
                ],
            ],
            'update-sales-rep-user-status' => [
                'translations' => [
                    'ar' => 'تحديث حالة مستخدم مندوب',
                    'en' => 'update sales rep user status',
                ],
            ],
        ];

        // Create permissions with translations
        $permissionsArray = [];
        foreach ($permissions as $permissionName => $transArr) {
            $permission = Permission::firstOrCreate(['guard_name' => 'salesRepApi', 'name' => $permissionName]);
            $permissionsArray[] = $permission;

            foreach ($languages as $language) {
                PermissionTranslation::updateOrCreate([
                    'permission_id' => $permission->id,
                    'language_id' => $language->id,
                ], [
                    'display_name' => $transArr['translations'][$language->code] ?? $permissionName,
                ]);
            }
        }

        // Define role translations
        $rolesTranslations = [
            'ar' => 'مندوب مبيعات',
            'en' => 'Sales Representative',
        ];

        // Create role with translations
        $salesRepRole = Role::firstOrCreate(['guard_name' => 'salesRepApi', 'name' => 'SalesRep']);
        $salesRepRole->givePermissionTo($permissionsArray);

        foreach ($languages as $language) {
            RoleTranslation::updateOrCreate([
                'role_id' => $salesRepRole->id,
                'language_id' => $language->id,
            ], [
                'display_name' => $rolesTranslations[$language->code] ?? 'SalesRep',
            ]);
        }
    }
}
