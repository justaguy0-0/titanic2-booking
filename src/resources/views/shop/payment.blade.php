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

            {{-- Заголовок страницы --}}
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
                                                    <input type="text" name="passengers[{{ $index }}][first_name]" class="passenger-input" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="passenger-label">Фамилия <span class="required-mark">*</span></label>
                                                    <input type="text" name="passengers[{{ $index }}][last_name]" class="passenger-input" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="passenger-label">Дата рождения <span class="required-mark">*</span></label>
                                                    <input
                                                        type="text"
                                                        class="passenger-input passenger-birthdate-display"
                                                        data-index="{{ $index }}"
                                                        placeholder="дд.мм.гггг"
                                                        maxlength="10"
                                                        autocomplete="off"
                                                        required>
                                                    <input
                                                        type="hidden"
                                                        name="passengers[{{ $index }}][birth_date]"
                                                        class="passenger-birthdate-hidden"
                                                        data-index="{{ $index }}">
                                                    <small class="age-display" id="age-display-{{ $index }}"></small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="passenger-label">Гражданство <span class="required-mark">*</span></label>
                                                    <input type="text" name="passengers[{{ $index }}][citizenship]" class="passenger-input" value="Россия" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="passenger-label">Серия паспорта <span class="required-mark">*</span></label>
                                                    <input type="text" name="passengers[{{ $index }}][passport_series]" class="passenger-input" placeholder="1234" maxlength="10" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="passenger-label">Номер паспорта <span class="required-mark">*</span></label>
                                                    <input type="text" name="passengers[{{ $index }}][passport_number]" class="passenger-input" placeholder="567890" maxlength="20" required>
                                                </div>
                                                <input type="hidden" name="passengers[{{ $index }}][ticket_id]" value="{{ $ticket->id }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Развлечения --}}
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

                                <div class="total-section">
                                    <div class="total-row">
                                        <div class="total-label">
                                            <i class="fas fa-calculator me-2"></i>Итого к оплате:
                                        </div>
                                        <div class="total-amount">
                                            {{ number_format($totalPrice, 0) }} ₽
                                        </div>
                                    </div>

                                    <div class="payment-actions">
                                        <button type="submit" class="btn-pay" id="pay-btn">
                                            <i class="fas fa-lock me-2"></i>Оплатить {{ number_format($totalPrice, 0) }} ₽
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

    // Функция для правильного склонения слова "лет"
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

    // Функция для валидации даты рождения
    function validateDate(input) {
        const index = input.dataset.index;
        const dateStr = input.value;
        const ageDisplay = document.getElementById(`age-display-${index}`);
        const hiddenInput = document.querySelector(`.passenger-birthdate-hidden[data-index="${index}"]`);

        // Проверяем формат ДД.ММ.ГГГГ
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

        // Проверяем корректность даты
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

        // Создаем дату (месяц в JS начинается с 0)
        const birthDate = new Date(year, month - 1, day);
        const today = new Date();

        // Проверка на корректность даты (например, 31.02 станет 03.03)
        if (birthDate.getDate() !== day || birthDate.getMonth() !== month - 1 || birthDate.getFullYear() !== year) {
            ageDisplay.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>Такой даты не существует</span>';
            input.setCustomValidity('Некорректная дата');
            hiddenInput.value = '';
            return;
        }

        // Проверка на будущую дату
        if (birthDate > today) {
            ageDisplay.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>Дата не может быть в будущем</span>';
            input.setCustomValidity('Дата в будущем');
            hiddenInput.value = '';
            return;
        }

        // Проверка на год
        if (year < 1920 || year > today.getFullYear()) {
            ageDisplay.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>Год должен быть от 1920 до ' + today.getFullYear() + '</span>';
            input.setCustomValidity('Неверный год');
            hiddenInput.value = '';
            return;
        }

        // Рассчитываем возраст
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        // Проверяем корректность возраста
        if (age < 0 || age > 120) {
            ageDisplay.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>Проверьте дату</span>';
            input.setCustomValidity('Некорректный возраст');
            hiddenInput.value = '';
            return;
        }

        // Форматируем дату для отправки на сервер (YYYY-MM-DD)
        const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        hiddenInput.value = formattedDate;

        // Сбрасываем ошибку валидации
        input.setCustomValidity('');

        // Отображаем возраст
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

    // Обработчик отправки формы
    form.addEventListener('submit', function(e) {
        // Проверяем все поля даты рождения
        const birthdateDisplays = document.querySelectorAll('.passenger-birthdate-display');
        let allDatesValid = true;

        birthdateDisplays.forEach(input => {
            const index = input.dataset.index;
            const hiddenInput = document.querySelector(`.passenger-birthdate-hidden[data-index="${index}"]`);

            // Если скрытое поле пустое - вызываем валидацию
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

        // Проверяем валидность формы
        if (!form.checkValidity()) {
            e.preventDefault();
            form.reportValidity();
            return false;
        }

        // Показываем индикатор загрузки
        payBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Обработка платежа...';
        payBtn.disabled = true;
    });

    // Автоформатирование даты рождения
    const birthdateInputs = document.querySelectorAll('.passenger-birthdate-display');

    birthdateInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, ''); // Удаляем все кроме цифр
            let formattedValue = '';

            // Форматируем как ДД.ММ.ГГГГ
            if (value.length > 0) {
                formattedValue = value.substring(0, 2); // День
            }
            if (value.length >= 3) {
                formattedValue += '.' + value.substring(2, 4); // Месяц
            }
            if (value.length >= 5) {
                formattedValue += '.' + value.substring(4, 8); // Год
            }

            this.value = formattedValue;

            // Автоматически валидируем когда введено 10 символов (ДД.ММ.ГГГГ)
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
});
</script>
@endsection
