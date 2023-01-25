<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 6/5/18
 * Time: 2:37 AM
 */

namespace Larazoul\Larazoul\Larazoul\Controllers\Admin;

use Larazoul\Larazoul\Larazoul\Models\MenuItem;
use Larazoul\Larazoul\Larazoul\Requests\ItemsRequest;


class ItemsController
{

    public function store(ItemsRequest $request, MenuItem $item)
    {

        $item->create($request->all());

        return redirect()->back();
    }

    public function destroy($id, MenuItem $item)
    {

        $item->findOrFail($id)->delete();

        return redirect()->back();
    }

    public function update($id  , ItemsRequest $request, MenuItem $item){

        $item->findOrFail($id)->update($request->all());

        return redirect()->back();
    }


}