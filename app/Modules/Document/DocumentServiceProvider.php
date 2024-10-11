<?php

namespace App\Modules\Document;

use App\Listeners\DocumentStatusUpdateSubscriber;
use App\Modules\Document\Mappers\DocumentSignatureMapper;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class DocumentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DocumentServiceInterface::class, function (Application $app) {
            return new DocumentService(
                $app->make(DocumentSignatureMapper::class),
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::subscribe(DocumentStatusUpdateSubscriber::class);
    }
}
