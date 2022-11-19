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
					
					<form id="form">
					<x-input-label for="name" :value="__('Name')" />

					<x-text-input  class="block mt-1 w-full"
									type="text"
									name="name"
									id="name" required />

					<x-input-label for="email" :value="__('Email')" />

					<x-text-input  class="block mt-1 w-full"
									type="email"
									name="email"
									id="email" required />
					
					<x-input-label for="club" :value="__('club')" />

					<x-text-input  class="block mt-1 w-full"
									type="text"
									name="club"
									id="club" required />

					<x-input-label for="number_of_tickets" :value="__('Number of Tickets')" />

					<x-text-input  class="block mt-1 w-full"
									type="number"
									name="number_of_tickets" onchange="updatePrice()" id="quantity" required />

					<x-input-error :messages="$errors->get('number_of_tickets')" class="mt-2" />

					<x-input-label for="reader" :value="__('Reader')" />

					<x-text-input  class="block mt-1 w-full"
									type="number"
									name="reader" id="reader" required />
					</form>

					<x-primary-button class="mt-4" onclick="charge();">
                    {{ __('Sell') }}
                	</x-primary-button>

					<x-primary-button class="mt-4" onclick="cashCharge();">
                    {{ __('Cash') }}
                	</x-primary-button>

					<h1 class="text-xl font-bold" id="price">£0</h1>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

<script>
	function getAmount()
	{
		let quantity = document.querySelector("#quantity").value;

		let price = Math.floor(quantity / 3) * 5;
		price += (quantity % 3) * 2;

		return price;
	}

    function updatePrice()
	{
		

		document.querySelector("#price").innerHTML = "£" + getAmount();
	}

	async function postData(url = '', data = {}) {
  // Default options are marked with *
  const response = await fetch(url, {
    method: 'POST', // *GET, POST, PUT, DELETE, etc.
    headers: {
      'Content-Type': 'application/json'
      // 'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: JSON.stringify(data) // body data type must match "Content-Type" header
  });
  return;
}

	function charge()
	{
		var form = document.querySelector("#form");
		var elements = form.elements;
		for (var i = 0, len = elements.length; i < len; ++i) {
			elements[i].readOnly = true;
		}
		console.log({email: document.querySelector("#email").value, name: document.querySelector("#name").value, amount: parseInt(getAmount() * 100), club: document.querySelector("#club"), terminalId: 16})

	postData('api/orderAndCharge', { email: document.querySelector("#email").value, name: document.querySelector("#name").value, amount: parseInt(getAmount() * 100), club: document.querySelector("#club").value, terminalId: document.querySelector("#reader")})
  .then(() => {
    resetForm(); // JSON data parsed by `data.json()` call
  });
	}

	function resetForm()
	{
		var form = document.querySelector("#form");
		var elements = form.elements;
		for (var i = 0, len = elements.length; i < len; ++i) {
			elements[i].readOnly = false;
			elements[i].value = "";
		}

		getAmount();
	}

	function cashCharge()
	{
		var form = document.querySelector("#form");
		var elements = form.elements;
		for (var i = 0, len = elements.length; i < len; ++i) {
			elements[i].readOnly = true;
		}
		console.log({email: document.querySelector("#email").value, name: document.querySelector("#name").value, amount: parseInt(getAmount() * 100), club: document.querySelector("#club"), terminalId: 16})

	postData('api/cash', { email: document.querySelector("#email").value, name: document.querySelector("#name").value, amount: parseInt(getAmount() * 100), club: document.querySelector("#club").value, terminalId: 16})
  .then((data) => {
    resetForm(); // JSON data parsed by `data.json()` call
  });
	}
</script>

