<?php

namespace App\Repositories\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductDiscountSellerGroup;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductPriceDistributorGroup;
use App\Models\Product\ProductPriceSellerGroup;
use App\Models\Product\ProductSerial;
use App\Repositories\Language\LanguageRepository;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductRepository extends BaseRepository
{
    private $productTranslationRepository;
    private $languageRepository;
    private $productCategoryRepository;
    private $productPriceDistributorGroupRepository;

    public function __construct(
        Application $app,
        ProductTranslationRepository $productTranslationRepository,
        ProductCategoryRepository $productCategoryRepository,
        LanguageRepository $languageRepository,
        ProductPriceDistributorGroupRepository $productPriceDistributorGroupRepository,
    )
    {
        parent::__construct($app);
        $this->productTranslationRepository = $productTranslationRepository;
        $this->languageRepository = $languageRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->productPriceDistributorGroupRepository = $productPriceDistributorGroupRepository;
    }

    public function getAllProducts($requestData): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $langId = $this->languageRepository->getLangByCode(app()->getLocale())->id;
        if (in_array($requestData->input('sort_by'), ['created_at', 'name', 'brand', 'type', 'quantity', 'status', 'price', 'cost_price', 'wholesale_price']))
            $sortBy = $requestData->input('sort_by');
        else
            $sortBy = 'created_at';

        $sortDirection = $requestData->input('sort_direction', 'desc');
        $searchTerm = $requestData->input('search', '');
        $categoryIds = null;
        if ($requestData->has('category_ids') && $requestData->input('category_ids') != '') {
            $categoryIds = explode(',', $requestData->input('category_ids', null));
        }
        $brandIds = null;
        if ($requestData->has('brand_ids') && $requestData->input('brand_ids') != '') {
            $brandIds = explode(',', $requestData->input('brand_ids', null));
        }

        // Build the base query
        $query = $this->model->query();
        // Join attached table
        $query->join('brands', function (JoinClause $join) use ($brandIds) {
            $join->on("products.brand_id", '=', "brands.id");
            if (!empty($brandIds)) {
                $join->whereIn('products.brand_id', $brandIds);
            } else {
                $join->orWhereNull('products.brand_id');
            }
        });
        $query->leftJoin('product_translations', function (JoinClause $join) use ($langId) {
            $join->on("product_translations.product_id", '=', "products.id");
                // ->where("product_translations.language_id", $langId);
        });
        $query->select('products.*');
        $query->groupBy(
            "id",
            "brand_id",
            "serial",
            "quantity",
            "image",
            "price",
            "cost_price",
            "points",
            "status",
            "sort_order",
            "created_at",
            "updated_at",
            "type",
            "vendor_id",
            "web",
            "mobile",
            "sku",
            "notify",
            "minimum_quantity",
            "max_quantity",
            "wholesale_price",
            "tax_id",
            "packing_method",
            "tax_type",
            "tax_amount",
            "is_live_integration",
            "is_available",
        );
        if ($categoryIds) {
            $query->whereHas('categories', function (Builder $query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            });
        }
        // get attaching with product
        $query->with(['brand', 'categories', 'vendor', 'productPriceDistributorGroups.distributorGroup']);
        // Apply sorting
        if ($sortBy == 'name')
            $query->orderBy('product_translations.name', $sortDirection);
        elseif ($sortBy == 'brand')
            $query->orderBy('brands.name', $sortDirection);
//        elseif ($sortBy == 'category')
//            $query->orderBy('category_translations.name', $sortDirection);
        else
            $query->orderBy($sortBy, $sortDirection);
        // Apply searching
        if ($searchTerm) {
            $query->where('product_translations.name', 'like', '%' . $searchTerm . '%');
        }
        // Retrieve paginated results
        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function get_brand_products($brand_id): array|\Illuminate\Database\Eloquent\Collection
    {

        return $this->model->with(['translations', 'product_images', 'brand', 'category', 'vendor'])->where('brand_id', $brand_id)->get();
    }

    public function showProductByIdAndCategoryId($id, $categoryId)
    {
        return $this->model
            ->where('id', $id)
            ->when($categoryId, function ($query, $categoryId) {
                $query->whereHas('categories', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
                return $query;
            })
            ->with([
                'product_images',
                'vendor:id,name,logo',
                'brand',
                'categories',
            ])
            ->active()
            ->first();
    }

    public function store($data_request)
    {

        $product = $this->model->create($data_request);
        if ($product) {
            $this->productTranslationRepository->store($data_request, $product->id);
            if (!empty($data_request['images'])) {
                ProductImage::where('product_id', $product->id)->delete();
                foreach ($data_request['images'] as $image) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $image,
                    ]);
                }
            }

            // save categories for product
            $product->categories()->sync($data_request['category_ids']);

            // Now, iterate over each related model and save the data
            $relatedData = [
                // ProductDiscountCustomerGroup::class => [],
                ProductDiscountSellerGroup::class => [],
                // ProductPriceCustomerGroup::class => [],
                ProductPriceSellerGroup::class => []
            ];

            foreach ($relatedData as $relatedModel => $dummyArray) {
                // Assuming your data structure matches your database columns
                $relatedModelData = $data_request[strtolower(class_basename($relatedModel))] ?? [];

                // Save related model data if available
                if (!empty($relatedModelData)) {
                    foreach ($relatedModelData as $item) {
                        // Instantiate the related model class
                        $relatedModelInstance = new $relatedModel;

                        // Fill the model with data
                        $relatedModelInstance->fill($item);

                        // Save the related model
                        $relatedModelInstance->product_id = $product->id;
                        $relatedModelInstance->save();
                    }
                }
            }
        }

      return  $product->load('translations');

    }

    public function serials($data_request)
    {
        $product = $this->model->find($data_request['product_id']);
        $product->update($data_request);
        ProductSerial::where('product_id', $product->id)->delete();
        if ($product) {
            // Now, iterate over each related model and save the data
            $relatedData = [
                ProductSerial::class => [],
            ];

            foreach ($relatedData as $relatedModel => $dummyArray) {
                // Assuming your data structure matches your database columns
                $relatedModelData = $data_request[strtolower(class_basename($relatedModel))] ?? [];

                // Save related model data if available

                // Save related model data if available
                if (!empty($relatedModelData)) {
                    foreach ($relatedModelData as $item) {
                        // Instantiate the related model class
                        $relatedModelInstance = new $relatedModel;

                        // Fill the model with data
                        $relatedModelInstance->fill($item);

                        // Save the related model
                        $relatedModelInstance->save();
                    }
                }
            }
        }

        return $product->load('translations');

    }

    public function applyPriceAll($data_request)
    {
        if (in_array($data_request['price_type'], ['cost_price', 'price', 'wholesale_price'])) {
            $this->model->query()->update([$data_request['price_type'] => $data_request['amount']
            ]);
            return true;
        }
        return false;
    }

    public function applyPriceAllGroups($data_request)
    {
        // Check if the price action is 'fixed' or 'percentage'
        // Update records for seller groups if seller_group_id is provided
        if ($data_request['seller_group_id']) {
            ProductPriceSellerGroup::query()
                ->where('seller_group_id', $data_request['seller_group_id'])
                ->update([
                    "amount_percentage" => $data_request['amount_percentage'],
                    "price" => $data_request['amount']
                ]);
        }

        return true;
    }

    public function prices($data_request)
    {
        Log::info($data_request);

        $data = ['success' => true, 'error' => null];

        // Save related model data if available
        if (!empty($data_request['productpricemerchantgroup'])) {
            foreach ($data_request['productpricemerchantgroup'] as $item) {
                if ($item['price_product'] < $item['cost_price']) {
                    $data['success'] = false;
                    $data['error'] = 'price is less than cost_price';
                    return $data;
                }
                $product = $this->model->find($item['product_id']);
                $product->cost_price = $item['cost_price'];
                $product->price = $item['price_product'];
                $product->wholesale_price = $item['wholesale_price'];
                $product->save();
                Log::info($data_request);
                if (isset($data_request['productpricemerchantgroup']) && isset($item['merchant_group']) && isset($item['product_id'])) {
                    $this->productPriceDistributorGroupRepository->deleteByProductId($item['product_id']);
                    $productPriceSellerGroup = $this->productPriceDistributorGroupRepository->store($item);
                    if (! $productPriceSellerGroup) {
                        $data['success'] = false;
                        $data['error'] = 'price in groups is less than cost price or wholesale price';
                        return $data;
                    }
                }
            }
        }
        return $data;
    }


    public function update($data_request, $product_id)
    {
        $product = $this->model->find($product_id);
        $product->update($data_request);
        // save categories for product
        $product->categories()->sync($data_request['category_ids']);
        $productTranslation = $this->productTranslationRepository->deleteByProductId($product->id);
        // ProductDiscountCustomerGroup::where('product_id', $product->id)->delete();
        ProductDiscountSellerGroup::where('product_id', $product->id)->delete();
        // ProductPriceCustomerGroup::where('product_id', $product->id)->delete();
        ProductPriceSellerGroup::where('product_id', $product->id)->delete();
        if (!empty($data_request['images'])) {
            ProductImage::where('product_id', $product->id)->delete();
            foreach ($data_request['images'] as $image) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $image,
                ]);
            }
        }
        if ($productTranslation) {
            $this->productTranslationRepository->store($data_request, $product->id);
            // Now, iterate over each related model and save the data
            $relatedData = [
                // ProductDiscountCustomerGroup::class => [],
                ProductDiscountSellerGroup::class => [],
                // ProductPriceCustomerGroup::class => [],
                ProductPriceSellerGroup::class => []
            ];

            foreach ($relatedData as $relatedModel => $dummyArray) {
                // Assuming your data structure matches your database columns
                $relatedModelData = $data_request[strtolower(class_basename($relatedModel))] ?? [];

                // Save related model data if available

                // Save related model data if available
                if (!empty($relatedModelData)) {
                    foreach ($relatedModelData as $item) {
                        // Instantiate the related model class
                        $relatedModelInstance = new $relatedModel;

                        // Fill the model with data
                        $relatedModelInstance->fill($item);

                        // Save the related model
                        $relatedModelInstance->save();
                    }
                }
            }
        }

        return  $product->load('translations');

    }

    public function changeStatus($data_request, $product_id)
    {
        $product = $this->model->find($product_id);
        $product->update($data_request);
        return $product->load('translations');

    }

    public function show($id)
    {
        return $this->model->where('id', $id)->with(['translations', 'product_images', 'brand', 'categories.ancestors', 'vendor', 'productPriceDistributorGroups.distributorGroup'])->first();
    }

    public function getAllProductsWithRatings($requestData)
    {
        $langId = $this->languageRepository->getLangByCode(app()->getLocale())->id;
        $validSortColumns = ['last_rating_date', 'name', 'rating_average', 'ratings_count'];
        $sortBy = in_array($requestData->input('sort_by'), $validSortColumns, true) ? $requestData->input('sort_by') : 'last_rating_date';
        $sortDirection = $requestData->input('sort_direction', 'desc');
        $searchTerm = $requestData->input('search', '');
        //$ratingAverage = $requestData->input('rating_average', null);
        $minRatingsCount = $requestData->input('min_ratings_count', null);
        $maxRatingsCount = $requestData->input('max_ratings_count', null);
        $ratingStartDate = $requestData->input('rating_start_date', null);
        $ratingEndDate = $requestData->input('rating_end_date', null);
        $ratingAverage = null;
        if ($requestData->has('rating_average') && $requestData->input('rating_average') != '') {
            $ratingAverage = explode(',', $requestData->input('rating_average', null));
        }
        $categoryIds = null;
        if ($requestData->has('category_ids') && $requestData->input('category_ids') != '') {
            $categoryIds = explode(',', $requestData->input('category_ids', null));
        }
        $brandIds = null;
        if ($requestData->has('brand_ids') && $requestData->input('brand_ids') != '') {
            $brandIds = explode(',', $requestData->input('brand_ids', null));
        }

        $query = Product::query()
        ->leftJoin('product_translations', function (JoinClause $join) use ($langId) {
            $join->on("product_translations.product_id", '=', "products.id")
                ->where("product_translations.language_id", $langId);

        })
        ->leftJoin('ratings', "ratings.product_id", '=', "products.id")
        ->join('brands', function (JoinClause $join) use ($brandIds) {
            $join->on("products.brand_id", '=', "brands.id");
            if (!empty($brandIds)) {
                $join->whereIn('products.brand_id', $brandIds);
            } else {
                $join->orWhereNull('products.brand_id');
            }
        })
        ->select(
            'products.id',
            'products.image',
            DB::raw('AVG(ratings.stars) as rating_average'),
            DB::raw('COUNT(ratings.stars) as ratings_count'),
        )
        ->with([
            'ratings',
        ])
        // ->withCount([
        //     'ratings'
        // ])
        ->groupBy('products.id', 'products.image')
        ->withLastRatingDate();
        $query->orderBy($sortBy, $sortDirection);
        if ($ratingStartDate && $ratingEndDate) {
            $ratingEndDate = Carbon::parse($ratingEndDate);
            $ratingEndDate->addDay();
            $query->havingBetween('last_rating_date', [$ratingStartDate, $ratingEndDate]);
        }
        if ($minRatingsCount && $maxRatingsCount) {
            $query->having('ratings_count', '>=', $minRatingsCount)
                ->having('ratings_count', '<=', $maxRatingsCount);
        }
        if ($ratingAverage) {
            $query->havingRaw('rating_average IN (' . implode(',', $ratingAverage) . ')');
        }
        if ($categoryIds) {
            $query->whereHas('categories', function (Builder $query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            });
        }
        if ($searchTerm) {
            $query->where('product_translations.name', 'like', '%' . $searchTerm . '%');
        }
        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function showProductWithRatings($id)
    {
        return $this->model
            ->where('id', $id)
            ->select(
                'products.id',
                'products.image',
                'products.brand_id',
            )
            ->with([
                'brand',
                'ratings.customer:id,name,image',
                'ratings.replies.admin:id,name,avatar',
                'ratings.reactions.admin:id,name,avatar',
                'starVotes',
            ])
            ->withCount([
                'ratings'
            ])
            //->withStarVotes()
            ->first();
    }

    public function updateProductQuantity($id, $quantity)
    {
        $product = $this->model->where('id', $id)->first();
        $product->quantity = $product->quantity - $quantity;
        $product->save();
    }

    public function stockAlmostOut()
    {
        return $this->model
        ->whereColumn('notify', '>=', 'quantity')
        ->select(['id', 'image', 'brand_id', 'quantity', 'notify'])
        ->with(['brand:id'])
        ->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function destroy($id)
    {
        $product = $this->model->where('id', $id)->first();
        if (
            !$product ||
            $product->order_products()->count() > 0 ||
            $product->productSerials()->count() > 0 ||
            $product->vendorProducts()->count() > 0 ||
            $product->ratings()->count() > 0
        ){
            return false;
        }
        //ProductDiscountCustomerGroup::where('product_id', $id)->delete();
        //ProductPriceCustomerGroup::where('product_id', $id)->delete();
        return $product->delete();
    }

    public function multiDelete($requestData)
    {
        foreach ($requestData->ids as $id) {
            $this->destroy($id);
        }
        return true;
    }

    public function productsByCategoryIdAndBrandId($requestData, $categoryId, $brandId , $has_subs = 0): LengthAwarePaginator
    {
        $langId = $this->languageRepository->getLangByCode(app()->getLocale())->id;
        $searchTerm = $requestData->input('search', '');
        // Build the base query
        $query = $this->model->query();
        $query->leftJoin('product_translations', function (JoinClause $join) use ($langId) {
            $join->on("product_translations.product_id", '=', "products.id")
                ->where("product_translations.language_id", $langId);
        });
        $query->select(
            "products.id",
            "products.brand_id",
            "products.image",
            "products.quantity",
            "products.price",
            "products.status",
            "products.type",
            "products.sku",
            "products.is_live_integration",
            "products.is_available",
            "products.wholesale_price",
        );
        $query->with(['product_images']);
        $query->where('product_translations.name', 'like', '%' . $searchTerm . '%');
        $query->active();
/*        if ($has_subs)
        {
            $query->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }*/

        $query->where('brand_id', $brandId);
        $query->orderBy('products.price');

        return $query->active()->paginate(PAGINATION_COUNT_WEB);
    }


    /**
     * Product Model
     *
     * @return string
     */
    public function model(): string
    {
        return Product::class;
    }
}
