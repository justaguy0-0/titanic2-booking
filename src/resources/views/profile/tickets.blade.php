<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Оформление заказа') }}
            </h2>
            <a href="{{ route('checkout.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Назад к рейсам
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Информация о рейсе -->
            <div class="bg-gradient-to-r from-blue-500 to-cyan-600 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h3 class="text-2xl font-bold mb-2">{{ $voyage->name }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <p class="text-blue-100 text-sm">Маршрут</p>
                            <p class="font-semibold">{{ $voyage->placeDeparture->name }} → {{ $voyage->icebergArrival->name }}</p>
                        </div>
                        <div>
                            <p class="text-blue-100 text-sm">Отправление</p>
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($voyage->departure_date)->format('d.m.Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-blue-100 text-sm">Время в пути</p>
                            <p class="font-semibold">{{ $voyage->travel_time }} часов</p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST" id="orderForm">
                @csrf
                <input type="hidden" name="voyage_id" value="{{ $voyage->id }}">

                <!-- Выбор билетов -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Выберите билеты</h3>
                        
                        @if($tickets->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($tickets as $ticket)
                                    <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition ticket-item">
                                        <input type="checkbox" name="tickets[]" value="{{ $ticket->id }}" 
                                               class="ticket-checkbox mt-1" 
                                               data-price="{{ $ticket->price }}"
                                               onchange="updateTotal()">
                                        <div class="ml-3 flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">{{ $ticket->type }}</span>
                                                    <p class="text-xs text-gray-500">№ {{ $ticket->number }}</p>
                                                </div>
                                                <span class="text-lg font-bold text-blue-600">
                                                    {{ number_format($ticket->price, 0) }} ₽
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">Нет доступных билетов на этот рейс</p>
                        @endif
                    </div>
                </div>

                <!-- Выбор развлечений -->
                @if($entertainments->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Дополнительные развлечения</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($entertainments as $entertainment)
                                    <div class="flex items-center justify-between p-4 border rounded-lg entertainment-item">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $entertainment->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ number_format($entertainment->price, 0) }} ₽</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button type="button" 
                                                    class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300"
                                                    onclick="decrementEntertainment({{ $entertainment->id }})">
                                                −
                                            </button>
                                            <input type="number" 
                                                   id="entertainment_{{ $entertainment->id }}_qty"
                                                   class="w-16 text-center border rounded entertainment-quantity"
                                                   value="0" 
                                                   min="0"
                                                   data-id="{{ $entertainment->id }}"
                                                   data-price="{{ $entertainment->price }}"
                                                   onchange="updateTotal()"
                                                   readonly>
                                            <button type="button" 
                                                    class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
                                                    onclick="incrementEntertainment({{ $entertainment->id }})">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Итоговая стоимость -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xl font-bold text-gray-900">Итого:</span>
                            <span id="totalPrice" class="text-3xl font-bold text-blue-600">0 ₽</span>
                        </div>
                        
                        <button type="submit" 
                                id="submitButton"
                                class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed"
                                disabled>
                            Оформить заказ
                        </button>
                        <p class="text-sm text-gray-500 text-center mt-2">Выберите хотя бы один билет</p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateTotal() {
            let total = 0;
            let hasTickets = false;

            // Считаем выбранные билеты
            document.querySelectorAll('.ticket-checkbox:checked').forEach(checkbox => {
                total += parseFloat(checkbox.dataset.price);
                hasTickets = true;
            });

            // Считаем развлечения
            document.querySelectorAll('.entertainment-quantity').forEach(input => {
                const quantity = parseInt(input.value) || 0;
                const price = parseFloat(input.dataset.price);
                total += quantity * price;
            });

            // Обновляем отображение
            document.getElementById('totalPrice').textContent = 
                new Intl.NumberFormat('ru-RU').format(total) + ' ₽';

            // Активируем/деактивируем кнопку
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = !hasTickets;
        }

        function incrementEntertainment(id) {
            const input = document.getElementById(`entertainment_${id}_qty`);
            input.value = parseInt(input.value) + 1;
            updateTotal();
        }

        function decrementEntertainment(id) {
            const input = document.getElementById(`entertainment_${id}_qty`);
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
                updateTotal();
            }
        }

        // Добавляем развлечения в форму перед отправкой
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            // Удаляем старые поля развлечений
            document.querySelectorAll('input[name^="entertainments"]').forEach(el => el.remove());

            // Добавляем новые
            document.querySelectorAll('.entertainment-quantity').forEach((input, index) => {
                const quantity = parseInt(input.value);
                if (quantity > 0) {
                    const id = input.dataset.id;
                    
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = `entertainments[${index}][id]`;
                    idInput.value = id;
                    this.appendChild(idInput);

                    const qtyInput = document.createElement('input');
                    qtyInput.type = 'hidden';
                    qtyInput.name = `entertainments[${index}][quantity]`;
                    qtyInput.value = quantity;
                    this.appendChild(qtyInput);
                }
            });
        });
    </script>
</x-app-layout>