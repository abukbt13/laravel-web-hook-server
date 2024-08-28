<?php

namespace App\Handler;

use Illuminate\Http\Request;
use Spatie\WebhookClient\Exceptions\WebhookFailed;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;

class PaystackSignature implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $signature = $request->header($config->signatureHeaderName);
        if (!$signature) {
            return false;
        }
        $signingSecret = $config->signingSecret;
        if (empty($signingSecret)) {
            throw WebhookFailed::signingSecretNotSet();
        }
        $computedSignature = hash_hmac('sha512', $request->getContent(), $signingSecret);
        return hash_equals($signature, $computedSignature);
    }
}

Great. So the other file app/Handler/ProcessWebhook.php extends the ProcessWebhookJob class which holds the WebhookCall variables containing each job’s detail.

<?php

namespace App\Handler;

use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

//The class extends "ProcessWebhookJob" class as that is the class
//that will handle the job of processing our webhook before we have
//access to it.

class ProcessWebhook extends ProcessWebhookJob
{
    public function handle()
    {
        $dat = json_decode($this->webhookCall, true);
        $data = $dat['payload'];

        if ($data['event'] == 'charge.success') {
            // take action since the charge was success
            // Create order
            // Sed email
            // Whatever you want
            Log::info($data);
        }

        //Acknowledge you received the response
        http_response_code(200);
    }
}
