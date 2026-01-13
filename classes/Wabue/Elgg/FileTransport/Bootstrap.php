<?php

namespace Wabue\Elgg\FileTransport;

use Elgg\DefaultPluginBootstrap;
use Elgg\PluginBootstrapInterface;


class Bootstrap extends DefaultPluginBootstrap implements PluginBootstrapInterface
{
    public function boot()
    {
        $path = elgg_get_plugin_setting('path', 'filetransport', elgg_get_data_path() . "/notifications_log/zend");

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        _elgg_services()->set('mailer', new \Laminas\Mail\Transport\File(
            new \Laminas\Mail\Transport\FileOptions(
                [
                    "path" => $path
                ]
            )
        ));

        $webService = new WebService();
        $events = $this->elgg()->events;
        $events->registerHandler('register', 'api_methods', array($webService, 'register'));
    }
}
