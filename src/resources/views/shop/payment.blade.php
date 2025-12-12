@extends('head')

@section('title', 'Оплата заказа')

@section('main_content')
<div class="container py-5 payment-container">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            @if (session('error'))
                <div class="alert payment-alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                </div>
            @endif

           
            <div class="payment-header">
                <h1 class="payment-title">
                    <i class="fas fa-credit-card me-3"></i>Оплата заказа
                </h1>
                <p class="payment-subtitle">Пожалуйста, проверьте детали вашего заказа перед оплатой</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="payment-card mb-4">
                        <div class="payment-card-header">
                            <h3 class="payment-card-title">
                                <i class="fas fa-ship me-2"></i>Информация о рейсе
                            </h3>
                        </div>
                        <div class="payment-card-body">
                            <div class="voyage-info-item">
                                <span class="voyage-info-label">
                                    <i class="fas fa-anchor payment-icon"></i>Рейс:
                                </span>
                                <span class="voyage-info-value">{{ $voyage->name }}</span>
                            </div>

                            <div class="voyage-info-item">
                                <span class="voyage-info-label">
                                    <i class="fas fa-calendar-alt payment-icon"></i>Отправление:
                                </span>
                                <span class="voyage-info-value">
                                    {{ \Carbon\Carbon::parse($voyage->departure_date)->format('d.m.Y H:i') }}
                                    @if($voyage->departurePlace)
                                        <br><small style="color: #94a3b8;">{{ $voyage->departurePlace->name }}</small>
                                    @endif
                                </span>
                            </div>

                            <div class="voyage-info-item">
                                <span class="voyage-info-label">
                                    <i class="fas fa-map-marker-alt payment-icon"></i>Прибытие:
                                </span>
                                <span class="voyage-info-value">
                                    {{ \Carbon\Carbon::parse($voyage->arrival_date)->format('d.m.Y H:i') }}
                                    @if($voyage->arrivalPlace)
                                        <br><small style="color: #94a3b8;">{{ $voyage->arrivalPlace->name }}</small>
                                    @endif
                                </span>
                            </div>

                            <div class="voyage-info-item">
                                <span class="voyage-info-label">
                                    <i class="fas fa-clock payment-icon"></i>Время в пути:
                                </span>
                                <span class="voyage-info-value">{{ $voyage->travel_time }} часов</span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="payment-card">
                        <div class="payment-card-header">
                            <h3 class="payment-card-title">
                                <i class="fas fa-percentage me-2"></i>Ваша скидка
                            </h3>
                        </div>
                        <div class="payment-card-body">
                            <div class="loyalty-info">
                                <div class="loyalty-level-display mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="loyalty-level-text">Уровень {{ $loyaltyInfo['level'] }}</span>
                                        <span class="loyalty-discount-badge">{{ $loyaltyInfo['discount'] }}% скидка</span>
                                    </div>
                                    <div class="progress loyalty-progress-bar mt-2">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $loyaltyInfo['progress'] }}%"
                                             aria-valuenow="{{ $loyaltyInfo['progress'] }}" 
                                             aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    @if($loyaltyInfo['next_level_tickets'])
                                        <small class="loyalty-hint mt-1">
                                            До уровня {{ $loyaltyInfo['level'] + 1 }} осталось: {{ $loyaltyInfo['next_level_tickets'] }} билетов
                                        </small>
                                    @endif
                                </div>

                                <div class="loyalty-breakdown">
                                    <div class="price-row">
                                        <span>Стоимость {{ count($tickets) }} билетов:</span>
                                        <span>{{ number_format($baseTotalPrice, 0) }} ₽</span>
                                    </div>
                                    @if($loyaltyInfo['discount'] > 0)
                                        <div class="price-row discount-row">
                                            <span>Скидка {{ $loyaltyInfo['discount'] }}%:</span>
                                            <span>-{{ number_format($discountCalculation['discount_amount'], 0) }} ₽</span>
                                        </div>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="payment-card">
                        <div class="payment-card-header">
                            <h3 class="payment-card-title">
                                <i class="fas fa-list-ul me-2"></i>Детали заказа
                            </h3>
                        </div>
                        <div class="payment-card-body">
                            <form action="{{ route('shop.process-payment') }}" method="POST" id="payment-form">
                                @csrf

                                {{-- Билеты с данными пассажиров --}}
                                <div class="mb-4">
                                <h5 class="mb-3" style="color: #fbbf24;">
                                    <i class="fas fa-users me-2"></i>Данные пассажиров
                                </h5>
                                @foreach($tickets as $index => $ticket)
                                    <div class="passenger-card-wrapper mb-3">
                                        <div class="passenger-card-title">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-user-circle me-2"></i>
                                                    <strong>Пассажир {{ $index + 1 }}</strong>
                                                    <span class="ms-2 passenger-info-text">{{ $ticket->cabinType->name ?? 'Билет' }} - Место {{ $ticket->number }}</span>
                                                </div>
                                                <div>
                                                    <span class="passenger-price">{{ number_format($ticket->price, 0) }} ₽</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="passenger-form-section">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="passenger-label">Имя <span class="required-mark">*</span></label>
                                                    <input type="text" name="passengers[{{ $index }}][first_name]" class="passenger-input" value="{{ old('passengers.'.$index.'.first_name') }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="passenger-label">Фамилия <span class="required-mark">*</span></label>
                                                    <input type="text" name="passengers[{{ $index }}][last_name]" class="passenger-input" value="{{ old('passengers.'.$index.'.last_name') }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="passenger-label">Дата рождения <span class="required-mark">*</span></label>
                                                    @php
                                                        $oldBirthDate = old('passengers.'.$index.'.birth_date');
                                                        $displayDate = '';
                                                        if ($oldBirthDate) {
                                                            $displayDate = \Carbon\Carbon::parse($oldBirthDate)->format('d.m.Y');
                                                        }
                                                    @endphp
                                                    <input
                                                        type="text"
                                                        class="passenger-input passenger-birthdate-display"
                                                        data-index="{{ $index }}"
                                                        placeholder="дд.мм.гггг"
                                                        maxlength="10"
                                                        autocomplete="off"
                                                        value="{{ $displayDate }}"
                                                        required>
                                                    <input
                                                        type="hidden"
                                                        name="passengers[{{ $index }}][birth_date]"
                                                        class="passenger-birthdate-hidden"
                                                        data-index="{{ $index }}"
                                                        value="{{ $oldBirthDate }}">
                                                    <small class="age-display" id="age-display-{{ $index }}"></small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="passenger-label">Гражданство <span class="required-mark">*</span></label>
                                                    <input type="text" name="passengers[{{ $index }}][citizenship]" class="passenger-input" value="{{ old('passengers.'.$index.'.citizenship', 'Россия') }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="passenger-label">Серия паспорта <span class="required-mark">*</span></label>
                                                    <input type="text" name="passengers[{{ $index }}][passport_series]" class="passenger-input" placeholder="1234" maxlength="10" value="{{ old('passengers.'.$index.'.passport_series') }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="passenger-label">Номер паспорта <span class="required-mark">*</span></label>
                                                    <input type="text" name="passengers[{{ $index }}][passport_number]" class="passenger-input" placeholder="567890" maxlength="20" value="{{ old('passengers.'.$index.'.passport_number') }}" required>
                                                </div>
                                                <input type="hidden" name="passengers[{{ $index }}][ticket_id]" value="{{ $ticket->id }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if(count($entertainmentItems) > 0)
                                <h5 class="mb-3 mt-4" style="color: #fbbf24;">
                                    <i class="fas fa-star me-2"></i>Дополнительные услуги
                                </h5>
                                <table class="order-table mb-3">
                                    <thead>
                                        <tr>
                                            <th>Наименование</th>
                                            <th style="text-align: center; width: 100px;">Кол-во</th>
                                            <th style="text-align: right; width: 150px;">Цена</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($entertainmentItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="item-name">
                                                        <i class="fas fa-star me-2"></i>
                                                        {{ $item['entertainment']->name }}
                                                    </div>
                                                    <small class="item-type">
                                                        {{ $item['entertainment']->description ?? 'Дополнительная услуга' }}
                                                    </small>
                                                </td>
                                                <td class="item-quantity">{{ $item['quantity'] }}</td>
                                                <td class="item-price">{{ number_format($item['subtotal'], 0) }} ₽</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                                <h5 class="mb-3 mt-4" style="color: #fbbf24;">
                                    <i class="fas fa-credit-card me-2"></i>Платежные данные
                                </h5>
                                <div class="payment-method-card mb-4">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="passenger-label">Номер карты <span class="required-mark">*</span></label>
                                            <div class="card-input-wrapper">
                                                <input
                                                    type="text"
                                                    name="card_number"
                                                    id="card-number-input"
                                                    class="passenger-input card-number-input"
                                                    placeholder="1234 5678 9012 3456"
                                                    maxlength="19"
                                                    autocomplete="off"
                                                    value="{{ old('card_number') }}"
                                                    required>
                                                <div class="card-type-indicator" id="card-type-indicator">
                                                    <span class="card-type-text">Введите номер</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="passenger-label">Срок действия <span class="required-mark">*</span></label>
                                            <input
                                                type="text"
                                                name="card_expiry"
                                                id="card-expiry-input"
                                                class="passenger-input"
                                                placeholder="MM/ГГ"
                                                maxlength="5"
                                                autocomplete="off"
                                                value="{{ old('card_expiry') }}"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="passenger-label">CVV код <span class="required-mark">*</span></label>
                                            <input
                                                type="text"
                                                name="card_cvv"
                                                id="card-cvv-input"
                                                class="passenger-input"
                                                placeholder="123"
                                                maxlength="3"
                                                autocomplete="off"
                                                value="{{ old('card_cvv') }}"
                                                required>
                                        </div>
                                        <div class="col-12">
                                            <label class="passenger-label">Имя держателя карты <span class="required-mark">*</span></label>
                                            <input
                                                type="text"
                                                name="card_holder"
                                                id="card-holder-input"
                                                class="passenger-input"
                                                placeholder="IVAN IVANOV"
                                                style="text-transform: uppercase;"
                                                value="{{ old('card_holder') }}"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="total-section">
                                    <div class="total-row final-total">
                                        <div class="total-label">
                                            <i class="fas fa-calculator me-2"></i>Итого к оплате:
                                        </div>
                                        <div class="total-amount">
                                            @if($loyaltyInfo['discount'] > 0)
                                                <div class="original-price" style="text-decoration: line-through; color: #94a3b8; font-size: 0.9em;">
                                                    {{ number_format($baseTotalPrice, 0) }} ₽
                                                </div>
                                            @endif
                                            <div class="final-price-amount" style="color: #10b981; font-size: 1.3em; font-weight: bold;">
                                                {{ number_format($discountCalculation['final_price'], 0) }} ₽
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment-actions">
                                        <button type="submit" class="btn-pay" id="pay-btn">
                                            <i class="fas fa-lock me-2"></i>Оплатить {{ number_format($discountCalculation['final_price'], 0) }} ₽
                                        </button>
                                        <a href="{{ route('shop') }}" class="btn-cancel">
                                            <i class="fas fa-times me-2"></i>Отмена
                                        </a>
                                    </div>
                                </div>

                                <div class="mt-4 text-center">
                                    <small style="color: #94a3b8;">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Безопасная оплата через защищенное соединение
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form');
    const payBtn = document.getElementById('pay-btn');

    function getYearsText(age) {
        const lastDigit = age % 10;
        const lastTwoDigits = age % 100;

        if (lastTwoDigits >= 11 && lastTwoDigits <= 19) {
            return 'лет';
        }

        if (lastDigit === 1) {
            return 'год';
        }

        if (lastDigit >= 2 && lastDigit <= 4) {
            return 'года';
        }

        return 'лет';
    }

    function validateDate(input) {
        const index = input.dataset.index;
        const dateStr = input.value;
        const ageDisplay = document.getElementById(`age-display-${index}`);
        const hiddenInput = document.querySelector(`.passenger-birthdate-hidden[data-index="${index}"]`);

        const datePattern = /^(\d{2})\.(\d{2})\.(\d{4})$/;
        const match = dateStr.match(datePattern);

        if (!match) {
            ageDisplay.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>Введите дату в формате дд.мм.гггг</span>';
            input.setCustomValidity('Неверный формат даты');
            hiddenInput.value = '';
            return;
        }

        const day = parseInt(match[1], 10);
        const month = parseInt(match[2], 10);
        const year = parseInt(match[3], 10);

        if (month < 1 || month > 12) {
            ageDisplay.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>Месяц должен быть от 01 до 12</span>';
            input.setCustomValidity('Неверный месяц');
            hiddenInput.value = '';
            return;
        }

        if (day < 1 || day > 31) {
            ageDisplay.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>День должен быть от 01 до 31</span>';
            input.setCustomValidity('Неверный день');
            hiddenInput.value = '';
            return;
        }

        const birthDate = new Date(year, month - 1, day);
        const today = new Date();

        if (birthDate.getDate() !== day || birthDate.getMonth() !== month - 1 || birthDate.getFullYear() !== year) {
            ageDisplay.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>Такой даты не существует</span>';
            input.setCustomValidity('Некорректная дата');
            hiddenInput.value = '';
            return;
        }

        if (birthDate > today) {
            ageDisplay.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>Дата не может быть в будущем</span>';
            input.setCustomValidity('Дата в будущем');
            hiddenInput.value = '';
            return;
        }

        if (year < 1920 || year > today.getFullYear()) {
            ageDisplay.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>Год должен быть от 1920 до ' + today.getFullYear() + '</span>';
            input.setCustomValidity('Неверный год');
            hiddenInput.value = '';
            return;
        }

        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        if (age < 0 || age > 120) {
            ageDisplay.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>Проверьте дату</span>';
            input.setCustomValidity('Некорректный возраст');
            hiddenInput.value = '';
            return;
        }

        const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        hiddenInput.value = formattedDate;
        input.setCustomValidity('');

        if (age < 12) {
            ageDisplay.innerHTML = `<i class="fas fa-child me-1"></i>Возраст: ${age} ${getYearsText(age)} <span style="color: #10b981; font-weight: bold;">• Детская скидка 20%</span>`;
            ageDisplay.style.color = '#10b981';
        } else if (age < 18) {
            ageDisplay.innerHTML = `<i class="fas fa-user me-1"></i>Возраст: ${age} ${getYearsText(age)} <span style="color: #94a3b8;">(несовершеннолетний)</span>`;
            ageDisplay.style.color = '#94a3b8';
        } else {
            ageDisplay.innerHTML = `<i class="fas fa-user-check me-1"></i>Возраст: ${age} ${getYearsText(age)}`;
            ageDisplay.style.color = '#94a3b8';
        }
    }

    form.addEventListener('submit', function(e) {
        const birthdateDisplays = document.querySelectorAll('.passenger-birthdate-display');
        let allDatesValid = true;

        birthdateDisplays.forEach(input => {
            const index = input.dataset.index;
            const hiddenInput = document.querySelector(`.passenger-birthdate-hidden[data-index="${index}"]`);

            if (!hiddenInput.value) {
                validateDate(input);
                if (!hiddenInput.value) {
                    input.focus();
                    allDatesValid = false;
                }
            }
        });

        if (!allDatesValid) {
            e.preventDefault();
            alert('Пожалуйста, проверьте правильность введенных дат рождения');
            return false;
        }

        if (!form.checkValidity()) {
            e.preventDefault();
            form.reportValidity();
            return false;
        }

        payBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Обработка платежа...';
        payBtn.disabled = true;
    });

    // Остальной JavaScript код остается таким же...
    const birthdateInputs = document.querySelectorAll('.passenger-birthdate-display');
    birthdateInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            let formattedValue = '';

            if (value.length > 0) {
                formattedValue = value.substring(0, 2); 
            }
            if (value.length >= 3) {
                formattedValue += '.' + value.substring(2, 4); 
            }
            if (value.length >= 5) {
                formattedValue += '.' + value.substring(4, 8);
            }

            this.value = formattedValue;

            if (formattedValue.length === 10) {
                setTimeout(() => {
                    validateDate(this);
                }, 100);
            }
        });

        input.addEventListener('blur', function() {
            validateDate(this);
        });
    });


    const cardNumberInput = document.getElementById('card-number-input');
    const cardExpiryInput = document.getElementById('card-expiry-input');
    const cardCvvInput = document.getElementById('card-cvv-input');
    const cardHolderInput = document.getElementById('card-holder-input');
    const cardTypeIndicator = document.getElementById('card-type-indicator');

    const paymentSystems = ['Visa', 'MasterCard', 'SBP', 'Tinkoff', 'Yandex'];
    let selectedCardType = null;

    function detectCardType() {
        const randomIndex = Math.floor(Math.random() * paymentSystems.length);
        selectedCardType = paymentSystems[randomIndex];

        cardTypeIndicator.innerHTML = `<span class="card-type-badge card-type-${selectedCardType.toLowerCase()}">${selectedCardType}</span>`;
    }

    cardNumberInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\s/g, '');
        value = value.replace(/\D/g, '');

        let formattedValue = '';
        for (let i = 0; i < value.length && i < 16; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }

        this.value = formattedValue;

        if (value.length >= 4 && !selectedCardType) {
            detectCardType();
        }

        if (value.length < 4) {
            selectedCardType = null;
            cardTypeIndicator.innerHTML = '<span class="card-type-text">Введите номер</span>';
        }
    });

    cardExpiryInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');

        if (value.length >= 2) {
            this.value = value.substring(0, 2) + '/' + value.substring(2, 4);
        } else {
            this.value = value;
        }
    });

    cardCvvInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '');
    });

    cardHolderInput.addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
        this.value = this.value.replace(/[^A-ZА-ЯЁ\s]/g, '');
    });


    window.addEventListener('DOMContentLoaded', function() {
        // Восстановление типа карты
        const cardNumber = cardNumberInput.value;
        if (cardNumber.replace(/\s/g, '').length >= 4) {
            detectCardType();
        }

        birthdateInputs.forEach(input => {
            if (input.value) {
                setTimeout(() => {
                    validateDate(input);
                }, 100);
            }
        });
    });

    const originalFormSubmit = form.onsubmit;
    form.addEventListener('submit', function(e) {
        const cardNumber = cardNumberInput.value.replace(/\s/g, '');
        if (cardNumber.length !== 16) {
            e.preventDefault();
            alert('Номер карты должен содержать 16 цифр');
            cardNumberInput.focus();
            return false;
        }

        const expiry = cardExpiryInput.value;
        const expiryPattern = /^(0[1-9]|1[0-2])\/\d{2}$/;
        if (!expiryPattern.test(expiry)) {
            e.preventDefault();
            alert('Введите срок действия в формате MM/ГГ');
            cardExpiryInput.focus();
            return false;
        }
        const [month, year] = expiry.split('/');
        const expiryDate = new Date(2000 + parseInt(year), parseInt(month) - 1);
        const today = new Date();
        if (expiryDate < today) {
            e.preventDefault();
            alert('Срок действия карты истек');
            cardExpiryInput.focus();
            return false;
        }

        if (cardCvvInput.value.length !== 3) {
            e.preventDefault();
            alert('CVV код должен содержать 3 цифры');
            cardCvvInput.focus();
            return false;
        }

        if (cardHolderInput.value.trim().length < 3) {
            e.preventDefault();
            alert('Введите имя держателя карты');
            cardHolderInput.focus();
            return false;
        }
    });
});
</script>
@endsection