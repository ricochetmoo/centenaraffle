<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
	public static function isSignatureValid(Request $request, $endpoint)
	{
		$hash = hash_hmac("sha256", env('SQUARE_NOTIFICATION_URL')."/".$endpoint.$request->getContent(), env('SQUARE_SIGNATURE_KEY'), true);
		return  base64_encode($hash) == $request->header('X-Square-HmacSha256-Signature');
	}
}
