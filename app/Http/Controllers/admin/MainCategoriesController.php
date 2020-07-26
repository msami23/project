<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MainCategoriesController extends Controller
{
    public function index()
    {
        $default_lang = get_default_lang();
        $categories = MainCategory::where('translation_lang', $default_lang)
            ->selection()
            ->get();

        return view('admin.maincategories.index', compact('categories'));
    }
    public function create()
    {
        return view('admin.maincategories.create');

    }

    public function store(MainCategoryRequest $request)
    {

        try {
            //return $request;

            $main_categories = collect($request->category);

            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] == get_default_lang();
            });

          $default_category = array_values($filter->all()) [0];


            $filePath = "";
            if ($request->has('photo')) {

             $filePath = uploadImage('main_categories', $request->photo);
            }

            DB::beginTransaction();

            $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $filePath
            ]);

            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != get_default_lang();
            });



            if (isset($categories) && $categories->count()) {

                $categories_arr = [];
                foreach ($categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $filePath
                    ];
                }

              MainCategory::insert($categories_arr);
            }

            DB::commit();

            return redirect()->route('admin.main_categories')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.main_categories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }



    public function edit($id)
    {
       $mainCategories =  MainCategory::selection()->find($id);
       if (!$mainCategories)
           return redirect()->route('admin.main_categories')->with(['error' => 'هده القسم غير موجود ']);

           return view('admin.maincategories.edit', compact('mainCategories'));


       }



    public function update($id, MainCategoryRequest $request )

    {
        try {
            $mainCategories = MainCategory::find($id);
            if (!$mainCategories)
                return redirect()->route('admin.main_categories')->with(['error' => 'هده القسم غير موجود ']);
            $category = array_values($request->category) [0];

            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);


            MainCategory::where('id', $id)->update([
                'name' => $category['name'],
                'active' => $request->active,

            ]);
            if ($request->has('photo')) {
                $filePath = uploadImage('main_categories', $request->photo);
                MainCategory::where('id', $id)
                    ->update([
                        'photo' => $filePath,
                    ]);
            }


            return redirect()->route('admin.main_categories')->with(['success' => 'تم التحديث بنجاح  ']);
        } catch (\Exception $ex) {

            return redirect()->route('admin.main_categories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function delete()
    {

    }
}
