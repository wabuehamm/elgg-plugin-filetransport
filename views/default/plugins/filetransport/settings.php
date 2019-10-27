<?php

echo elgg_view_field([
    label => elgg_echo('filetransport:settings:path'),
    help => elgg_echo('filetransport:settings:path:help'),
    type => 'text',
    name => 'params[path]'
]);
