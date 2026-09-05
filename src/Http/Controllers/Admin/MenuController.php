<?php

namespace Larazoul\Larazoul\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Larazoul\Larazoul\Models\Menu;
use Larazoul\Larazoul\Models\MenuItem;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('items.children')->latest()->get();

        return view('larazoul::admin.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'slug' => ['nullable', 'string', 'max:60', 'alpha_dash'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $menu = Menu::create($data);

        return redirect()->route('larazoul.menus.index')
            ->with('success', "Menu [{$menu->name}] created.");
    }

    public function storeItem(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name_en' => ['required', 'string', 'max:60'],
            'name_ar' => ['nullable', 'string', 'max:60'],
            'link' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:60'],
            'parent_id' => ['nullable', 'exists:menu_items,id'],
            'order' => ['nullable', 'integer'],
            'slug' => ['nullable', 'string', 'max:60'],
        ]);

        $data['menu_id'] = $menu->id;

        MenuItem::create($data);

        return redirect()->route('larazoul.menus.index')
            ->with('success', 'Menu item added.');
    }

    public function destroyItem(MenuItem $item)
    {
        $item->delete();

        return back()->with('success', 'Menu item deleted.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('larazoul.menus.index')
            ->with('success', "Menu [{$menu->name}] deleted.");
    }
}