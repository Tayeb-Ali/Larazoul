<?php

namespace Larazoul\Larazoul\Larazoul\Seeders;

use Illuminate\Database\Seeder;
use Larazoul\Larazoul\Larazoul\Models\Menu;
use Larazoul\Larazoul\Larazoul\Models\MenuItem;

class ItemsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $menu = Menu::create(['name' => 'admin']);

        $parent = [
            'name_ar' => 'المديولات',
            'name_en' => 'Modules',
            'slug' => 'generator',
            'order' => 0,
            'menu_id' => $menu->id,
            'parent_id' => 0,
            'icon' => '<i class="fa fa-dashboard"></i>',
            'link' => '#'
        ];

        $parent = MenuItem::create($parent);

        $items = [
            [
                'name_ar' => 'القوائم',
                'name_en' => 'Menus',
                'slug' => 'menus',
                'order' => 0,
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'icon' => '<i class="fa fa-server"></i>',
                'link' => '/admin/menu'
            ],
            [
                'name_ar' => 'انشاء المديولات',
                'name_en' => 'Add Module',
                'slug' => 'add-module',
                'order' => 0,
                'menu_id' => $menu->id,
                'parent_id' => $parent->id,
                'icon' => '',
                'link' => '/admin/generator/module/step-one'
            ],
            [
                'name_ar' => 'التحكم في المدويلات',
                'name_en' => 'Module Control',
                'slug' => 'module-control',
                'order' => 1,
                'menu_id' => $menu->id,
                'parent_id' => $parent->id,
                'icon' => '',
                'link' => '/admin/generator/modules'
            ],
        ];


        MenuItem::insert($items);


    }
}
