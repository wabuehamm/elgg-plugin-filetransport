<?php

echo elgg_view_field([
    '#label' => elgg_echo('filetransport:settings:path'),
    '#help' => elgg_echo('filetransport:settings:path:help'),
    '#type' => 'text',
    name => 'params[path]',
    value => elgg_get_plugin_setting('path', 'filetransport', elgg_get_data_path() . "/notifications_log/zend")
]);
