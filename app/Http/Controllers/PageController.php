<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Subcategories;
use App\Models\Subsubcategories;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\XmlProcessingService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsModelExport;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    private $xmlService;

    public function __construct(XmlProcessingService $xmlService)
    {
        $this->xmlService = $xmlService;
    }

    public function exportToExcel()
    {                    
        return Excel::download(new ProductsModelExport, 'example.xlsx');
    }
    
    public function showForm() 
    {
        return view('upload-xml');
    }

    public function downloadAndProcessXml(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'xmlFile' => 'required|file|mimes:xml|max:2048', // Проверка наличия файла, типа, и размера
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $xmlFile = $request->file('xmlFile');

        // Сохраняем файл во временный каталог
        $path = $xmlFile->storeAs('temp', 'temp.xml');

        // Путь к сохраненному файлу
        $filePath = storage_path('app/' . $path);

        $shopArray = $this->xmlService->parseXmlToArray($filePath);

        //Цикл для записи категорий в БД
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Subsubcategories::truncate();
        Subcategories::truncate();
        Categories::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        foreach ($shopArray['categories'] as $key => $value) {            
            $categories = new Categories([
                'category_id' => $key,
                'name' => $value['name']
            ]);
            $categories->save();
            
            //Цикл для записи подкатегорий в БД
            foreach ($shopArray['categories'][$key]['subcategory'] as $key2 => $value2) {
                $subcategories = new Subcategories([
                    'sub_category_id' => $key2,
                    'name' => $value2['name'],
                    'parent_id' => $key,
                    'parent_name' => $value['name']
                ]);
                $subcategories->save();

                //Цикл для записи подподкатегорий в БД
                foreach ($shopArray['categories'][$key]['subcategory'][$key2]['subsubcategory'] as $key3 => $value3) {                                       
                    $subsubcategories = new Subsubcategories([
                        'sub_sub_category_id' => $key3,
                        'name' => $value3['name'],
                        'parent_id' => $key2,
                        'parent_name' => $value2['name']
                    ]);
                    $subsubcategories->save();
                }
            }
        }
        
        $product = Products::createWithCategories($shopArray);
        
        return redirect()->route('download-xlsx');
    }
}
