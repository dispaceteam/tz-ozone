<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductsRequest;
use App\Services\Ozone\Api\OzoneSeller;

class ProductController extends Controller
{

    /**
     * Store a newly created products in storage.
     *
     * @param StoreProductsRequest $request
     * @param OzoneSeller $ozoneSeller
     * @return \Illuminate\Http\JsonResponse
     * @OA\Post(
     *     path="/api/products/store",
     *     summary="Отправить новые продукты в Ozone",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Json с продуктами",
     *         @OA\JsonContent(ref="#/components/schemas/ProductEntity"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно выполнено",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Ошибка в запросе",
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Ошибка в теле запроса",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Ошибка ответа Ozone",
     *     ),
     * )
     */
    public function store(StoreProductsRequest $request, OzoneSeller $ozoneSeller)
    {
        $products = $request->getProductsAsEntity();
        $task_id = $ozoneSeller->ProductsImport(json_decode(json_encode($products), true));
        Product::addAfterSend($products, $task_id);
        return response()->json([
            "result" => [
               "task_id" => $task_id
            ]
        ]);
    }

}
