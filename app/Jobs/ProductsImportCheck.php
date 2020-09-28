<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\Ozone\Api\OzoneSeller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\OneProductImportTaskCheck;

class ProductsImportCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products_by_task = Product::InProgress()->get()->groupBy('task_id');
        $products_by_task->each(function($products, $task_id)
        {
            sleep(1); // Так как тестовое задание и проверять будут без должной конфигурации, то поставил задержку, чтоб не привысить лимит обращений к api
            // Cпециально реализовал через очереди, если они сконфигурированы не в режиме sync, то задержка не нужна
            dispatch(new OneProductImportTaskCheck((int) $task_id));
        });
    }
}
