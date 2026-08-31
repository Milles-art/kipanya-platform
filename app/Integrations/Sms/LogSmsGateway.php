<?php

namespace App\Integrations\Sms;

use Illuminate\Support\Facades\Log;

final class LogSmsGateway implements SmsGateway
{
    public function send(string $phone, string $message): void
    {
        Log::info('SMS dispatched', [
            'phone' => $phone,
            'message_length' => mb_strlen($message),
        ]);
    }
}
