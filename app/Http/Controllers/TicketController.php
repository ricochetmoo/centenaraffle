<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ticket;
use App\Models\Order;

class TicketController extends Controller
{
    public static function assignTickets()
    {
        $orders = Order::all();

        if ($orders->count() <= 0)
        {
            die();
        }

        foreach ($orders as $order)
        {
            $tickets = 0;

            if ($order->paid)
            {
                $amount = $order->amount / 100;
                $strips = floor($amount / 5);
                $tickets += $strips * 3;
                $tickets += ($amount % 5) / 2;
                $tickets = floor($tickets);

                foreach (range(1, $tickets) as $number)
                {
                    $ticketObject = new Ticket;
                    $ticketObject->order_id = $order->id;
                    $ticketObject->save();
                }
            }
        }
    }

    public static function doDraw(Request $request)
    {
        $prizes = $request->prizes;
        $tickets = Ticket::all();

        if ($prizes > $tickets->count())
        {
            return response()->json(null, 400);
        }

        while ($prizes > 0)
        {
            while (!$won)
            {
                $won = false;
                $winner = rand(0, $tickets->count());
                
                $winningTicket = $tickets->get($winner);
    
                if (!$winningTicket->winner)
                {
                    $winningTicket->winner = 1;
                    $winningTicket->save();
    
                    $won = true;

                    $prizes += -1;
                }
            }
        }

        return response()->json(Ticket::where('winner', 1)->get(), 200);
    }

    public static function index()
    {
        return response()->json(Ticket::all(), 200);
    }
}
