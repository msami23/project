<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\vendorCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;


class VendorsController extends Controller
{
    public function index()
    {
       $vendors =  Vendor::selection()->paginate(PAGINATION_COUNT);
       return view('admin.vendors.index',compact('vendors'));

    }

    public function create()
    {
       $categories =  MainCategory::where('translation_of',0)->active()->get();
        return view('admin.vendors.create',compact('categories'));
    }

    public function store(VendorRequest $request)
    {
        try {
            //active
            if(!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

                //logo
            $filePath = "";
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
            }

          $vendor =   Vendor::create([
                'name'=> $request->name,
                'mobile'=> $request->mobile,
                'email'=>$request->email,
                'active'=>$request->active,
                'logo'=>$filePath,
                'address'=>$request->address,
                'category_id'=>$request->category_id,
            ]);

            Notification::send($vendor, new vendorCreated($vendor));

            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);




        }catch (\Exception $ex){

            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);


        }



    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function changeStatus()
    {

    }

}
