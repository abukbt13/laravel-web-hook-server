<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookServer\WebhookCall;
use Spatie\WebhookServer\Exceptions\CouldNotCallWebhook;

class SavingController extends Controller
{

    public function save(Request $request) {
        $request->validate([
            'amount' => 'required',
            'date' => 'required',
        ]);

        $saving = new Saving();
        $saving->amount = $request['amount'];
        $saving->date = $request['date'];
        $saving->save();

        try {
            Log::info('Attempting to send webhook for saving ID: ' . $saving->id);

            WebhookCall::create()
                ->url('http://127.0.0.1:8001/api/savings')
                ->payload(['key' => $saving])
                ->useSecret('12443dd')
                ->dispatch();

            Log::info('Webhook sent successfully for saving ID: ' . $saving->id);

        } catch (CouldNotCallWebhook $exception) {
            Log::error('Webhook failed to send: ' . $exception->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Saved successfully',
            'saving' => $saving,
        ]);
    }

}
