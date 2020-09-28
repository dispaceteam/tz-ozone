<?php


namespace App\Services\Ozone\Api;

use Gam6itko\OzonSeller\Exception\OzonSellerException;
use Gam6itko\OzonSeller\Service\V1\ProductService;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class OzoneSeller
{
    protected $adapter, $config;

    /**
     * OzoneSeller constructor.
     */
    public function __construct()
    {
        $this->adapter = new GuzzleAdapter(new GuzzleClient());
        $this->config = [
            'clientId' => config('ozone.client_id'),
            'apiKey' => config('ozone.api_key'),
            'host' => config('ozone.host')
        ];

    }

    /**
     * @param $products
     * @return integer
     */
    public function ProductsImport($products)
    {
        $svcProduct = new ProductService($this->config, $this->adapter);
        try {
            $result = $svcProduct->import($products);
            if (isset($result['task_id']) === false) {
                throw new HttpResponseException(
                    new JsonResponse([
                        "message" => 'Ozone не вернул task_id'
                    ], 500)
                );
            }
            return $result['task_id'];
        } catch (OzonSellerException $e) {
            throw new HttpResponseException(
                new JsonResponse([
                    "message" => $e->getMessage(),
                    "errors" => $e->getData()
                ], 422)
            );

        }
    }

    /**
     * @param $taskId
     * @return array
     */
    public function ProductsImportInfo($taskId)
    {
        $svcProduct = new ProductService($this->config, $this->adapter);
        try {
            $result = $svcProduct->importInfo($taskId);
            if (isset($result['items'])) {
                return [
                    'success' => true,
                    'items' => $result['items']
                ];
            }else{
                return [
                    'success' => false,
                    'exception' => new \Exception('В результате нет продуктов')
                ];
            }
        } catch (OzonSellerException $e) {
            return [
                'success' => false,
                'exception' => $e
            ];

        }
    }
}
