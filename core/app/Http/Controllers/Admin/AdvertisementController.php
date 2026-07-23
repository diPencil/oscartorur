<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;

class AdvertisementController extends Controller
{
    public function index()
    {
        $pageTitle      = 'All Advertisement';
        $advertisements = Advertisement::searchable(['type', 'size'])->orderByDesc('id')->paginate(getPaginate());
        return view('admin.advertisement.index', compact('pageTitle', 'advertisements'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'type'         => 'required|in:image,script',
            'size'         => 'required|in:728x90,970x250,270x385',
            'redirect_url' => 'required_if:type,image',
            'script'       => 'required_if:type,script',
        ]);
        if ($request->type == 'image' && $request->hasFile('image')) {
            $request->validate([
                'image'  => [$id ? 'nullable' : 'required', new FileTypeValidate(['jpeg', 'jpg', 'png', 'gif'])],
            ]);
        }
        if ($id) {
            $advertisement = Advertisement::findOrFail($id);
            $notification = 'Advertisement updated successfully';
        } else {
            $advertisement = new Advertisement();
            $notification = 'Advertisement created successfully';
        }

        if ($request->hasFile('image')) {
            try {
                $old =  $advertisement->type == 'image' ? @$advertisement->content : null;
                $advertisement->content = fileUploader($request->image, getFilePath('advertisement'), $request->size,  $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your advertisement'];
                return back()->withNotify($notify);
            }
        }

        if ($request->type == "script") {
            $advertisement->content = $request->script;
        }
        $advertisement->type = $request->type;
        $advertisement->size = $request->size;
        $advertisement->redirect_url = $request->type == 'image' ? $request->redirect_url : 'N/A';
        $advertisement->save();
        $notify[] = ['success', $notification];

        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Advertisement::changeStatus($id);
    }
}
