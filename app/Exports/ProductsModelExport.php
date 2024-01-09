<?php

namespace App\Exports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsModelExport implements FromCollection, WithHeadings
{       
    public function collection()
    {   
        return Products::all(); // Возвращает коллекцию данных из вашей модели
    }

    public function headings(): array
    {
        // Возвращение заголовков столбцов
        return ["id", "	name", "product_id", "url", "price", "old_price", "currencyId", "picture", "vendor", "category", "sub_category", "sub_sub_category", "available", "created_at", "updated_at"];
    }
}
