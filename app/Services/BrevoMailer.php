<?php

namespace App\Services;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use GuzzleHttp\Client;

class BrevoMailer
{
    public static function send($to, $subject, $html)
    {
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', env('BREVO_API_KEY'));

        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );

        $sendSmtpEmail = new \Brevo\Client\Model\SendSmtpEmail([
            'subject' => $subject,
            'sender' => [
                'name' => env('MAIL_FROM_NAME'),
                'email' => env('MAIL_FROM_ADDRESS'),
            ],
            'to' => [
                ['email' => $to],
            ],
            'htmlContent' => $html,
        ]);

        return $apiInstance->sendTransacEmail($sendSmtpEmail);
    }
}
