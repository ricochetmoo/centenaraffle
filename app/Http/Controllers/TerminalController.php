<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terminal;
use \Square\SquareClient;
use \Square\Environment;

class TerminalController extends Controller
{
	public function index()
	{
		return response()->json(Terminal::all(), 200);
	}

	public function getCode(Request $request)
	{
		$client = new SquareClient
		([
			'accessToken' => env('SQUARE_ACCESS_TOKEN'),
			'environment' => Environment::PRODUCTION,
		]);

		$device_code = new \Square\Models\DeviceCode('TERMINAL_API');
		$device_code->setName($request->friendlyName);
        $device_code->setProductType('TERMINAL_API');
		
		$body = new \Square\Models\CreateDeviceCodeRequest(uniqid(), $device_code);
		
		$api_response = $client->getDevicesApi()->createDeviceCode($body);
		
		if ($api_response->isSuccess())
		{
			$result = $api_response->getResult();
			$terminal = new Terminal();
			$terminal->friendly_name = $result->getDeviceCode()->getName();
			$terminal->code = $result->getDeviceCode()->getCode();
			$terminal->save();

			return response()->json($terminal, 201);
		}
		else
		{
			$errors = $api_response->getErrors();

			return response()->json($errors, 500);
		}
	}

	public function markAsPaired(Request $request)
	{
		if(!WebhookController::isSignatureValid($request, "markAsPaired"))
        {
            return response()->json(null, 403);
        }
        
        $terminal = Terminal::where('code', $request->data['object']['device_code']['code'])->firstOrFail();
		$terminal->paired = True;
		$terminal->device_id = $request->data['object']['device_code']['device_id'];
		$terminal->save();
	}

	public static function charge($terminalId, $transaction)
	{
		$client = new SquareClient
		([
			'accessToken' => env('SQUARE_ACCESS_TOKEN'),
			'environment' => Environment::PRODUCTION,
		]);
		
		$terminal = Terminal::findOrFail($terminalId);

		$amount_money = new \Square\Models\Money();
		$amount_money->setAmount($transaction->amount);
		$amount_money->setCurrency('GBP');

		$tip_settings = new \Square\Models\TipSettings();
		$tip_settings->setAllowTipping(false);
		$tip_settings->setSeparateTipScreen(false);
		$tip_settings->setCustomTipField(false);

		$device_options = new \Square\Models\DeviceCheckoutOptions($terminal->device_id);
		$device_options->setSkipReceiptScreen(true);
		$device_options->setTipSettings($tip_settings);

		$checkout = new \Square\Models\TerminalCheckout($amount_money, $device_options);
		$checkout->setReferenceId("CRAF" . $order->id);
		$checkout->setNote("CentenaRaffle Ticket");

		$body = new \Square\Models\CreateTerminalCheckoutRequest(uniqid(), $checkout);

		$api_response = $client->getTerminalApi()->createTerminalCheckout($body);

		if ($api_response->isSuccess())
		{
			$result = $api_response->getResult();
			return true;
		}
		else
		{
			$errors = $api_response->getErrors();
			return response()->json(json_encode($errors), 500);
		}
	}
}
