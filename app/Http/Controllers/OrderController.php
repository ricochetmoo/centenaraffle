<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function createAndCharge(Request $request)
    {
        $order = new Order();
        
        $order->email = $request->email;
        $order->name = $request->name;
        $order->club = $request->club;
        $order->amount = $request->amount;
        $order->paid = false;

        $squareResponse = TerminalController::charge($request->terminalId, $order);

        $order->squareId = $squareResponse->getCheckout()->getId();
        $order->save();
    }

    public static function stats()
    {
        $orders = Order::all();
        $cash = 0;
        $card = 0;

        foreach ($orders as $order)
        {
            if ($order->paid)
            {
                if ($order->square_id == 'cash')
                {
                    $cash += $order->amount;
                }
                else
                {
                    $card += $order->amount;
                }
            }
        }

        return response()->json(['cash' => $cash, 'card' => $card], 200);
    }

    public function cashPayment(Request $request)
    {
        $order = new Order();
        
        $order->email = $request->email;
        $order->name = $request->name;
        $order->club = $request->club;
        $order->amount = $request->amount;
        $order->paid = true;

        $order->squareId = "cash";
        $order->save();
    }

    public static function markAsPaid(Request $request)
    {
        if($request->data['object']['checkout']['status'] == 'COMPLETED')
        {
            $order = Order::where('squareId', $request->data['id'])->firstOrFail();

            $order->paid = true;
            $order->save();
        }
    }

    public static function index()
    {
        return response()->json(Order::all(), 200);
    }

    public static function findOne($id)
    {
        return response()->json(Order::findOrFail($id), 200);
    }
}
