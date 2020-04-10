<?php

namespace Wabue\Elgg\FileTransport;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap
{
    public function boot()
    {
        $path = elgg_get_plugin_setting('path', 'filetransport', elgg_get_data_path() . "/notifications_log/zend");

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        elgg_set_email_transport(new \Zend\Mail\Transport\File(
            new \Zend\Mail\Transport\FileOptions(
                [
                    path => $path
                ]
            )
        ));

        $webService = new WebService();
        $webService->register();
    }
}
