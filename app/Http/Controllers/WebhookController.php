<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
	public static function in(Request $request)
	{
		$hash = hash_hmac("sha256", env('SQUARE_NOTIFICATION_URL').$request->path().$request->getContent(), env('SQUARE_SIGNATURE_KEY'), true);
		if (base64_encode($hash) != $request->header('X-Square-HmacSha256-Signature'))
		{
			return response()->json(null, 403);
		}

		switch ($request->type)
		{
			case "terminal.checkout.updated":
				OrderController::markAsPaid($request);
			case "device.code.paired":
				TerminalController::markAsPaired($request);
			default:
				return response(500);
		}
	}
}
