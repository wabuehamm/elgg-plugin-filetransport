<?php

namespace Wabue\Elgg\FileTransport;

use Elgg\Http\Exception\AdminGatekeeperException;
use Mail\MailParser;

class WebService {

    public function getNotifications() {
        if (!elgg_is_admin_logged_in()) {
            throw new AdminGatekeeperException('This request requires an admin');
        }
        $path = elgg_get_plugin_setting('path', 'filetransport', elgg_get_data_path() . "/notifications_log/zend");
        $notifications = glob($path . DIRECTORY_SEPARATOR . '*');
        $return = [];
        foreach ($notifications as $notification) {
            $notificationFile = file_get_contents($notification);
            $message = new MailParser($notificationFile);
            if ($message) {
                $return[] = [
                    'from' => $message->getFrom(),
                    'to' => $message->getTo(),
                    'cc' => $message->getCc(),
                    'bcc' => $message->getBcc(),
                    'subject' => $message->getSubject(),
                    'body' => $message->getBody(),
                    'raw' => $notificationFile
                ];
            }
        }
        return $return;
    }

    public function countNotifications() {
        if (!elgg_is_admin_logged_in()) {
            throw new AdminGatekeeperException('This request requires an admin');
        }
        $path = elgg_get_plugin_setting('path', 'filetransport', elgg_get_data_path() . "/notifications_log/zend");
        $notifications = glob($path . DIRECTORY_SEPARATOR . '*');
        return count($notifications);
    }

    public function flushNotifications() {
        if (!elgg_is_admin_logged_in()) {
            throw new AdminGatekeeperException('This request requires an admin');
        }
        $path = elgg_get_plugin_setting('path', 'filetransport', elgg_get_data_path() . "/notifications_log/zend");
        $notifications = glob($path . DIRECTORY_SEPARATOR . '*');
        foreach ($notifications as $notification) {
            unlink($notification);
        }
        return true;
    }

    public function sendNotifications() {
        if (!elgg_is_admin_logged_in()) {
            throw new AdminGatekeeperException('This request requires an admin');
        }
        $stop_time = time() + 45;
        _elgg_services()->notifications->processQueue($stop_time);
        return true;
    }

    public function register() {
        elgg_ws_expose_function('filetransport.notifications.get', array($this, 'getNotifications'), null, 'Return a list of currently sent notifications', 'GET', false, true);
        elgg_ws_expose_function('filetransport.notifications.count', array($this, 'countNotifications'), null, 'Return the count of currently sent notifications', 'GET', false, true);
        elgg_ws_expose_function('filetransport.notifications.flush', array($this, 'flushNotifications'), null, 'Flush all currently sent notifications', 'POST', false, true);
        elgg_ws_expose_function('filetransport.notifications.send', array($this, 'sendNotifications'), null, 'Send out notifications now', 'POST', false, true);
    }
}
