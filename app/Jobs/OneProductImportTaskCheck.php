<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\Ozone\Api\OzoneSeller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OneProductImportTaskCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task_id;

    /**
     * Create a new job instance.
     *
     * @param int $task_id
     */
    public function __construct(int $task_id)
    {
        $this->task_id = $task_id;
    }

    /**
     * Execute the job.
     *
     * @param OzoneSeller $ozone
     * @return void
     */
    public function handle(OzoneSeller $ozone)
    {
        $result = $ozone->ProductsImportInfo($this->task_id );

        if(isset($result['success']) && $result['success'] === true && isset($result['items'])){
            $items = collect($result['items']);
            $items->each(function($item)
            {
                if(isset($item['offer_id'], $item['status'])){
                    $product = Product::where('offer_id', $item['offer_id'])->first();
                    if($product){
                        $product->status = $item['status'];
                        $product->ozone_id = $item['product_id'];
                        $product->save();
                    }
                }
            });
        }
    }
}
