<?php

namespace Wabue\Elgg\FileTransport;

use Elgg\Exceptions\Http\Gatekeeper\AdminGatekeeperException;
use Mail\MailParser;

class WebService {

    public function getNotifications() {
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
        $path = elgg_get_plugin_setting('path', 'filetransport', elgg_get_data_path() . "/notifications_log/zend");
        $notifications = glob($path . DIRECTORY_SEPARATOR . '*');
        return count($notifications);
    }

    public function flushNotifications() {
        $path = elgg_get_plugin_setting('path', 'filetransport', elgg_get_data_path() . "/notifications_log/zend");
        $notifications = glob($path . DIRECTORY_SEPARATOR . '*');
        foreach ($notifications as $notification) {
            unlink($notification);
        }
        return true;
    }

    public function sendNotifications() {
        $stop_time = time() + 45;
        _elgg_services()->notifications->processQueue($stop_time);
        return true;
    }

    public function register(\Elgg\Event $event) {
        $results = $event->getValue();

        $results['filetransport.notifications.get']['GET'] = [
            'callback' => array($this, 'getNotifications'),
            'description' => 'Return a list of currently sent notifications',
            'require_api_auth' => true
        ];
        $results['filetransport.notifications.count']['GET'] = [
            'callback' => array($this, 'countNotifications'),
            'description' => 'Return the count of currently sent notifications',
            'require_api_auth' => true
        ];
        $results['filetransport.notifications.flush']['POST'] = [
            'callback' => array($this, 'flushNotifications'),
            'description' => 'Flush all currently sent notifications',
            'require_api_auth' => true
        ];
        $results['filetransport.notifications.send']['POST'] = [
            'callback' => array($this, 'sendNotifications'),
            'description' => 'Send out notifications now',
            'require_api_auth' => true
        ];

        return $results;
    }

}
