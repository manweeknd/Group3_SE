<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Order Summary -->
                        <div class="lg:col-span-1 order-2 lg:order-2">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                                <div class="flow-root">
                                    <dl class="-my-4 divide-y divide-gray-200">
                                        <div class="py-4 flex items-center justify-between">
                                            <dt class="text-gray-600">Event</dt>
                                            <dd class="font-medium text-gray-900">{{ $booking->event->title ?? 'Event Name' }}</dd>
                                        </div>
                                        <div class="py-4 flex items-center justify-between">
                                            <dt class="text-gray-600">Quantity</dt>
                                            <dd class="font-medium text-gray-900 flex items-center gap-3">
                                                <button type="button" onclick="updateQuantity(-1)" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 text-gray-600 focus:outline-none">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                                </button>
                                                <span id="quantity-display" class="w-8 text-center">{{ $booking->quantity ?? 1 }}</span>
                                                <button type="button" onclick="updateQuantity(1)" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 text-gray-600 focus:outline-none">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                </button>
                                                <input type="hidden" name="quantity" id="quantity-input" value="{{ $booking->quantity ?? 1 }}">
                                            </dd>
                                        </div>
                                        <div class="py-4 flex items-center justify-between">
                                            <dt class="text-gray-600">Total Amount</dt>
                                            <dd class="font-medium text-purple-600 text-xl">RM <span id="total-amount-display">{{ number_format($booking->total_price ?? 0, 2) }}</span></dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>



                        <!-- Payment Options -->
                        <div class="lg:col-span-2 order-1 lg:order-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Select Payment Method</h3>
                            
                            <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                <input type="hidden" name="booking_quantity" id="form-quantity" value="{{ $booking->quantity ?? 1 }}">
                                <input type="hidden" name="total_amount" id="form-total" value="{{ $booking->total_price ?? 0 }}">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8" id="payment-methods-container">
                                    <!-- Credit Card -->
                                    <label class="relative flex items-center justify-between min-w-0 p-4 bg-white border rounded-lg cursor-pointer hover:border-purple-500 focus-within:ring-2 focus-within:ring-purple-500">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="credit_card" class="h-4 w-4 text-purple-600 border-gray-300 focus:ring-purple-500" checked>
                                            <div class="font-medium text-gray-900">Credit Card</div>
                                        </div>
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    </label>

                                    <!-- Debit Card -->
                                    <label class="relative flex items-center justify-between min-w-0 p-4 bg-white border rounded-lg cursor-pointer hover:border-purple-500 focus-within:ring-2 focus-within:ring-purple-500">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="debit_card" class="h-4 w-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                                            <div class="font-medium text-gray-900">Debit Card</div>
                                        </div>
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    </label>

                                    <!-- FPX -->
                                    <label class="relative flex items-center justify-between min-w-0 p-4 bg-white border rounded-lg cursor-pointer hover:border-purple-500 focus-within:ring-2 focus-within:ring-purple-500">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="fpx" class="h-4 w-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                                            <div class="font-medium text-gray-900">FPX Online Banking</div>
                                        </div>
                                        <div class="text-xs text-gray-500 font-bold border border-gray-300 rounded px-1">FPX</div>
                                    </label>

                                    <!-- QR Pay -->
                                    <label class="relative flex items-center justify-between min-w-0 p-4 bg-white border rounded-lg cursor-pointer hover:border-purple-500 focus-within:ring-2 focus-within:ring-purple-500">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="qr_pay" class="h-4 w-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                                            <div class="font-medium text-gray-900">QR Pay</div>
                                        </div>
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-2h-2v4h6v-2h4m1-9h2m-6 0h-2m-2-2h-4m9 2h2m-4-4h2m-2-2h-2m2 4h4m-4-6h2m-2 2h2v4h4v-2h-4V4h-4v2m-4-2H4v4H0V4h4V0h4v4z"></path></svg>
                                    </label>

                                    <!-- JomPay -->
                                    <label class="relative flex items-center justify-between min-w-0 p-4 bg-white border rounded-lg cursor-pointer hover:border-purple-500 focus-within:ring-2 focus-within:ring-purple-500">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="jompay" class="h-4 w-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                                            <div class="font-medium text-gray-900">JomPay</div>
                                        </div>
                                        <div class="text-xs text-gray-500 font-bold border border-gray-300 rounded px-1">JomPay</div>
                                    </label>
                                </div>

                                <!-- Voucher Code -->
                                <div class="mb-8">
                                    <label for="voucher" class="block text-sm font-medium text-gray-700 mb-2">Voucher Code</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="voucher_code" id="voucher-code" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm" placeholder="Enter voucher code">
                                        <button type="button" onclick="applyVoucher()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                            Apply
                                        </button>
                                    </div>
                                    <p id="voucher-message" class="mt-2 text-sm hidden"></p>
                                </div>

                                <!-- Pay Button -->
                                <div class="mt-8">
                                    <button type="button" onclick="handlePayButton()" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" id="pay-button">
                                        Pay Now
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Bank Selection Modal (Hidden) -->
                        <div id="bank-modal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
                            <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white">
                                <div class="mt-3 text-center">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Select Bank</h3>
                                    <div class="mt-4 text-left">
                                        <div class="space-y-2">
                                            <label class="flex items-center space-x-3 p-3 border rounded hover:bg-gray-50 cursor-pointer">
                                                <input type="radio" name="bank" value="maybank" class="h-4 w-4 text-purple-600">
                                                <span>Maybank2u</span>
                                            </label>
                                            <label class="flex items-center space-x-3 p-3 border rounded hover:bg-gray-50 cursor-pointer">
                                                <input type="radio" name="bank" value="cimb" class="h-4 w-4 text-purple-600">
                                                <span>CIMB Clicks</span>
                                            </label>
                                            <label class="flex items-center space-x-3 p-3 border rounded hover:bg-gray-50 cursor-pointer">
                                                <input type="radio" name="bank" value="public" class="h-4 w-4 text-purple-600">
                                                <span>Public Bank</span>
                                            </label>
                                            <label class="flex items-center space-x-3 p-3 border rounded hover:bg-gray-50 cursor-pointer">
                                                <input type="radio" name="bank" value="rhb" class="h-4 w-4 text-purple-600">
                                                <span>RHB Now</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="items-center px-4 py-3">
                                        <button id="confirm-bank" class="px-4 py-2 bg-purple-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-300">
                                            Confirm Payment
                                        </button>
                                        <button onclick="document.getElementById('bank-modal').classList.add('hidden')" class="mt-2 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QR Pay Modal (Hidden) -->
                        <div id="qr-modal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
                            <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white">
                                <div class="mt-3 text-center">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Scan QR to Pay</h3>
                                    <div class="mt-4 flex justify-center bg-gray-100 p-4 rounded text-gray-400">
                                         <img 
                                            src="https://lh3.googleusercontent.com/pw/AP1GczM5EmFS5Z9N6bFW7Gn2Jruni3ul__Cj9n3qfc3Jtu3qaDrY3NCvSxUQ4sVSB9Q_jXSfmBq4xGBI4-L-F4AlZwbq7EZ14lVMVAMot8u-1hp0iGliBe0=w2400" 
                                            alt="IIUM Logo" 
                                            className="w-15 h-15 object-contain rounded-md"
                                        />
                                    </div>
                                    <p class="mt-4 text-sm text-gray-500">Please complete the payment within</p>
                                    <div id="qr-timer" class="text-2xl font-bold text-purple-600 my-2">10</div>
                                    <p class="text-sm text-gray-500">seconds</p>
                                </div>
                            </div>
                        </div>

                        <script>
                            const unitPrice = {{ $booking->event->price ?? 0 }};
                            let currentQuantity = {{ $booking->quantity ?? 1 }};
                            let currentVoucher = null; // Store voucher info: { type: 'fixed'|'percent', value: 10 }

                            function updateUI() {
                                document.getElementById('quantity-display').innerText = currentQuantity;
                                document.getElementById('quantity-input').value = currentQuantity;
                                document.getElementById('form-quantity').value = currentQuantity;
        
                                let total = currentQuantity * unitPrice;
                                let discount = 0;

                                // Recalculate discount if voucher present
                                if (currentVoucher) {
                                    if (currentVoucher.type === 'fixed') {
                                        discount = parseFloat(currentVoucher.value);
                                    } else if (currentVoucher.type === 'percent') {
                                        discount = total * (parseFloat(currentVoucher.value) / 100);
                                    }
                                }

                                let finalTotal = Math.max(0, total - discount);
                                
                                document.getElementById('total-amount-display').innerText = finalTotal.toFixed(2);
                                document.getElementById('form-total').value = finalTotal.toFixed(2);
                                
                                // Update message if voucher is applied
                                if (currentVoucher) {
                                    const messageEl = document.getElementById('voucher-message');
                                    messageEl.innerText = `Voucher applied! Discount: RM ${discount.toFixed(2)}`;
                                }

                                if (finalTotal === 0) {
                                    document.getElementById('payment-methods-container').classList.add('opacity-50', 'pointer-events-none');
                                } else {
                                    document.getElementById('payment-methods-container').classList.remove('opacity-50', 'pointer-events-none');
                                }
                            }

                            function updateQuantity(change) {
                                let newQuantity = currentQuantity + change;
                                if (newQuantity < 1) return;
        
                                currentQuantity = newQuantity;
                                updateUI();
                            }

                            // Initial load
                            updateUI();

                            function applyVoucher() {
                                const code = document.getElementById('voucher-code').value;
                                const messageEl = document.getElementById('voucher-message');
                                
                                fetch('{{ route('vouchers.apply') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        voucher_code: code,
                                        booking_id: {{ $booking->id }},
                                        quantity: currentQuantity // Send current quantity
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    messageEl.classList.remove('hidden');
                                    if(data.success) {
                                        messageEl.className = 'mt-2 text-sm text-green-600';
                                        
                                        // Store voucher for dynamic calculation
                                        currentVoucher = {
                                            type: data.voucher_type,
                                            value: data.voucher_value
                                        };
                                        
                                        updateUI(); // Recalculate everything
                                    } else {
                                        currentVoucher = null;
                                        messageEl.className = 'mt-2 text-sm text-red-600';
                                        messageEl.innerText = data.message;
                                        updateUI(); // Reset total
                                    }
                                });
                            }

                            function handlePayButton() {
                                const total = parseFloat(document.getElementById('form-total').value);
                                
                                if (total === 0) {
                                    if(confirm('Total amount is RM 0.00. Confirm payment?')) {
                                        document.getElementById('payment-form').submit();
                                    }
                                    return;
                                }

                                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                                
                                if (['credit_card', 'debit_card', 'fpx'].includes(paymentMethod)) {
                                    document.getElementById('bank-modal').classList.remove('hidden');
                                } else if (paymentMethod === 'qr_pay') {
                                    startQRTimer();
                                } else if (paymentMethod === 'jompay') {
                                    if(confirm('You will be redirected to JomPay website. Continue?')) {
                                        window.open('https://www.jompay.com.my', '_blank');
                                        setTimeout(() => {
                                            document.getElementById('payment-form').submit();
                                        }, 3000); 
                                    }
                                }
                            }

                            document.getElementById('confirm-bank').addEventListener('click', function() {
                                if (!document.querySelector('input[name="bank"]:checked')) {
                                    alert('Please select a bank');
                                    return;
                                }
                                document.getElementById('payment-form').submit();
                            });

                            function startQRTimer() {
                                document.getElementById('qr-modal').classList.remove('hidden');
                                let seconds = 10;
                                const timerEl = document.getElementById('qr-timer');
                                
                                const interval = setInterval(() => {
                                    seconds--;
                                    timerEl.innerText = seconds;
                                    
                                    if (seconds <= 0) {
                                        clearInterval(interval);
                                        document.getElementById('payment-form').submit();
                                    }
                                }, 1000);
                            }

                        </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
