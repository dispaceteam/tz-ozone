<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public $fillable = [
        'offer_id',
        'task_id'
    ];

    protected $casts = [
        'offer_id' => 'string',
        'task_id' => 'integer'
    ];

    static function addAfterSend($products_entity, $task_id){
        foreach ($products_entity as $product_entity){
            $product = self::firstOrCreate([
                'offer_id' => $product_entity->offer_id
            ]);
            $product->task_id = $task_id;
            $product->save();
        }
    }

    public function scopeInProgress($query){
        return $query->whereIn('status', config('ozone.products.status.in_progress'));
    }
}
