<?php

namespace App\Providers;

use App\Models\VideoArtista;
use App\Models\VideoMusica;
use App\Models\VideoTag;
use App\Models\Video;
use App\Observers\VideoArtistaObserver;
use App\Observers\VideoMusicaObserver;
use App\Observers\VideoTagObserver;
use App\Observers\VideoObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        VideoArtista::observe(VideoArtistaObserver::class);
        VideoTag::observe(VideoTagObserver::class);
        VideoMusica::observe(VideoMusicaObserver::class);        
        Video::observe(VideoObserver::class);

    }
}
