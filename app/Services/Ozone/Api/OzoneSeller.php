<?php


namespace App\Services\Ozone\Api;

use Gam6itko\OzonSeller\Service\V1\ProductService;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use \Gam6itko\OzonSeller\Exception\BadRequestException;

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
        } catch (BadRequestException $e) {
            throw new HttpResponseException(
                new JsonResponse([
                    "message" => $e->getMessage(),
                    "errors" => $e->getData()
                ], 422)
            );

        }
    }
}
