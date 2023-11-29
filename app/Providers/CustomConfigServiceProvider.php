<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class CustomConfigServiceProvider extends ServiceProvider{

    public function boot()
    {
        $udv = 200;
        $game = null;
        Config::set('custom.udv',$udv);
        Config::set('custom.game',$game);
    }
}
?>