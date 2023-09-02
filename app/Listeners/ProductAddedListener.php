<?php

namespace App\Listeners;
use Illuminate\Support\Facades\Log;
use App\Events\ProductAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProductAddedListener
{
    /**
     * Handle the event.
     *
     * @param  ProductAdded  $event
     * @return void
     */
    public function handle(ProductAdded $event)
    {
        // Handle the event here
        // You can access the product using $event->product

        // For example, you can log a message
        Log::info('Product added: ' . $event->product->name);
    }
}
