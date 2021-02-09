<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace LarvaCMS\WebDriver;

use Illuminate\Support\ServiceProvider;

/**
 * Class WebDriverServiceProvider
 * @author Tongle Xu <xutongle@gmail.com>
 */
class WebDriverServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\ChromeDriverCommand::class,
            ]);
        }
    }
}
