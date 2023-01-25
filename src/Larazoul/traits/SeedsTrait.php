<?php

namespace Larazoul\Larazoul\Larazoul\Traits;

use Larazoul\Larazoul\Larazoul\Models\Menu;
use Larazoul\Larazoul\Larazoul\Models\MenuItem;
use Larazoul\Larazoul\Larazoul\Models\Module;

trait SeedsTrait
{

    public function adminMenu()
    {
        Menu::create(['name' => 'admin']);
    }

    public function menuItems()
    {

        $items = [
            [
                'id' => 1,
                'name_ar' => 'القوائم',
                'name_en' => 'Menus',
                'slug' => 'menus',
                'order' => 0,
                'menu_id' => 1,
                'parent_id' => 0,
                'icon' => '<i class="fa fa-server"></i>',
                'link' => '/admin/menu'
            ],
            [
                'id' => 2,
                'name_ar' => 'المديولات',
                'name_en' => 'Modules',
                'slug' => 'generator',
                'order' => 0,
                'menu_id' => 1,
                'parent_id' => 0,
                'icon' => '<i class="fa fa-dashboard"></i>',
                'link' => '#'
            ],
            [
                'id' => 3,
                'name_ar' => 'انشاء المديولات',
                'name_en' => 'Add Module',
                'slug' => 'add-module',
                'order' => 0,
                'menu_id' => 1,
                'parent_id' => 2,
                'icon' => '',
                'link' => '/admin/generator/module/step-one'
            ],
            [
                'id' => 4,
                'name_ar' => 'التحكم في المدويلات',
                'name_en' => 'Module Control',
                'slug' => 'module-control',
                'order' => 1,
                'menu_id' => 1,
                'parent_id' => 2,
                'icon' => '',
                'link' => '/admin/generator/modules'
            ],
        ];


        MenuItem::insert($items);


    }

    protected function generateAllModulesItems()
    {
        foreach (Module::get() as $module) {
            $this->insertModuleToMenuItem($module);
        }
    }

    protected function insertModuleToMenuItem($module)
    {

        /*
         * count prev items to get order for new one
         */

        $order = MenuItem::count();

        /*
         * generate main item (parent)
         */

        $array = [
            'name_ar' => $module->name,
            'name_en' => $module->name,
            'slug' => $module->name,
            'order' => $order + 1,
            'menu_id' => 1,
            'parent_id' => 0,
            'icon' => '<i class="fa fa-server"></i>',
            'link' => '#'
        ];

        $parent = MenuItem::create($array);

        /*
         * gentrate control item
         */

        $array = [
            'name_ar' => 'Control ' . $module->name,
            'name_en' => 'Control ' . $module->name,
            'slug' => $module->name,
            'order' => $order + 2,
            'menu_id' => 1,
            'parent_id' => $parent->id,
            'icon' => '#',
            'link' => '/admin/' . strtolower($parent->slug)
        ];

        MenuItem::create($array);

        /*
         * gentrate create item
         */

        $array = [
            'name_ar' => 'Create ' . $module->name,
            'name_en' => 'Create ' . $module->name,
            'slug' => $module->name,
            'order' => $order + 3,
            'menu_id' => 1,
            'parent_id' => $parent->id,
            'icon' => '#',
            'link' => '/admin/' . strtolower($parent->slug) . '/create'
        ];

        MenuItem::create($array);

    }

}