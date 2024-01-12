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
        
        //Очистка таблиц в БД
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Subsubcategories::truncate();
        Subcategories::truncate();
        Categories::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        //Создание категорий
        foreach ($shopArray['categories'] as $categoryData) {            
            $categories = Categories::create([
                'category_id' => $categoryData['id'],
                'name' => $categoryData['name'],
            ]);
        }

        //Создание подкатегорий
        foreach ($shopArray['subcategories'] as $subcategoryData) { 
            $subcategories = Subcategories::create([
                'sub_category_id' => $subcategoryData['id'],
                'name' => $subcategoryData['name'],
                'parent_id' => $subcategoryData['parentId'],
                'parent_name' => $subcategoryData['parentName'],
            ]);
        }

        //Создание подподкатегорий
        foreach ($shopArray['subsubcategories'] as $subsubcategoryData) { 
            $subsubcategories = Subsubcategories::create([
                'sub_sub_category_id' => $subsubcategoryData['id'],
                'name' => $subsubcategoryData['name'],
                'parent_id' => $subsubcategoryData['parentId'],
                'parent_name' => $subsubcategoryData['parentName'],
            ]);
        }        
        
        $product = Products::createWithCategories($shopArray);
        
        return redirect()->route('download-xlsx');
    }
}
