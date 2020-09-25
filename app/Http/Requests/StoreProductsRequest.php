<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\Ozone\Entity\ProductEntity;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreProductsRequest extends FormRequest
{
    protected $required_item_fields = [];


    public function rules(ProductEntity $productEntity)
    {
        $this->required_item_fields = $productEntity->getRequired();
        return [
        ];
    }

    public function getProductsAsEntity()
    {
        $products_as_entity = [];
        foreach ($this->input('items') as $item){
            $product_entity = new ProductEntity;
            $product_entity->fill_fields($item);
            $products_as_entity[] = $product_entity;
        }
        return $products_as_entity;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if($this->input('items', false) === false){
                $validator->errors()->add('items', 'В запросе не указано поле items');
                return;
            }

            $items = $this->input('items');
            if(!is_array($items) || count($items) === 0){
                $validator->errors()->add('items', 'Поле items должно быть массивом и содержать минимум один товар');
                return;
            }

            foreach ($items as $item){
                foreach ($this->required_item_fields as $required) {
                    if (isset($item[$required]) === false) {
                        $validator->errors()->add($required, 'Поле товара ' . $required . ' обязательно для заполнения');
                    }
                }
            }
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'items.required' => "В запросе не указано поле items",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            new JsonResponse([
                "message" => collect($validator->errors())->flatten()->implode(', '),
                "errors" => $validator->errors()
            ], 422)
        );
    }
}
