<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Base\PageRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductReview;

class ProductController extends ApiController
{
    public function index(Product $product)
    {
        Product::$hideEmptyShopNowUrl = true;
        Product::setStaticVisible(Product::$publicColumns);
        ProductReview::setStaticVisible(ProductReview::$publicColumns);
        ProductCategory::setStaticVisible(ProductCategory::$publicColumns);

        $product->load([
            'product_reviews',
            'product_category',
        ]);

        return $this->respond([
            'product' => $product,
        ]);
    }

    public function list(PageRequest $request, ProductCategory $productCategory = null)
    {
        Product::$hideEmptyShopNowUrl = true;
        Product::setStaticVisible(Product::$publicColumns);
        ProductReview::setStaticVisible(ProductReview::$publicColumns);

        $models = Product::with([
            'product_reviews',
            'product_category',
        ])->orderBy('sort')->orderBy('id', 'desc')->limit(30);

        if(!empty($productCategory)){
            $models->where('product_category_id', $productCategory->id);
        }

        if(!empty($request->page)){
            $models->offset(30 * ($request->page - 1));
        }

        return $this->respond([
            'products' => $models->get(),
        ]);
    }

    public function listCategories()
    {
        ProductCategory::setStaticVisible(ProductCategory::$publicColumns);

        $models = ProductCategory::orderBy('sort')->orderBy('id', 'desc');

        return $this->respond([
            'product_categories' => $models->get(),
        ]);
    }


}
