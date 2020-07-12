<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCatalogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAll(Request $request)
    {
        $allRequest = $request->all();
        $products = DB::table('products')
            ->select('products.*');
        // join properties name/value if exists
        if (isset($allRequest, $allRequest['properties'])) {
            $i = 0;
            foreach ($allRequest['properties'] as $property_name => $property_values) {
                // products_properties on products.id = products_properties.product_id
                $products->leftJoin(
                    'products_properties as products_properties' . $i,
                    'products.id',
                    '=',
                    'products_properties' . $i . '.product_id');

                // select all property_names/property_values
                $products->addSelect([
                    'products_properties' . $i . '.property_name as property_name' . $i,
                    'products_properties' . $i . '.property_value as property_value' . $i
                ]);

                // property_name = property_name AND property_value IN (property_values)
                $products->where('products_properties' . $i . '.property_name', $property_name)
                    ->whereIn('products_properties' . $i . '.property_value', $property_values);

                $i++;
            }
        }

        return $products->paginate(40);
    }
}
