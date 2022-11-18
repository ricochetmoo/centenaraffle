<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			{{ __('Dashboard') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 bg-white border-b border-gray-200">
					You're logged in!

					<h1 class="text-2xl font-bold">Sell a ticket</h1>

					<x-input-label for="number_of_tickets" :value="__('Number of Tickets')" />

					<x-text-input id="number_of_tickets" class="block mt-1 w-full"
									type="number"
									name="number_of_tickets" required />

					<x-input-error :messages="$errors->get('number_of_tickets')" class="mt-2" />

					<x-primary-button class="mt-4" onclick="chargeIos();">
                    {{ __('Sell') }}
                	</x-primary-button>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

<script>
    function chargeIos()
    {
        var dataParameter = {
        amount_money: {
        amount:        "200",
        currency_code: "GBP"
        },

        // Replace this value with your application's callback URL
        callback_url: "http://localhost:8000/api/callback",

        // Replace this value with your application's ID
        client_id: "sq0idp-BQTkwHLv_0MmWX3mO5rvwQ",

        version: "1.3",
        notes: "notes for the transaction",
        options: {
        supported_tender_types: ["CREDIT_CARD","CASH","OTHER","SQUARE_GIFT_CARD","CARD_ON_FILE"*/]
        }
        };

         window.location =
        "square-commerce-v1://payment/create?data=" +
        encodeURIComponent(JSON.stringify(dataParameter));
    }
</script>

