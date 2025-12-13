@extends('head')
@section('title', 'Служба поддержки')

@section('main_content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold" style="color: #fbbf24; font-family: Georgia, serif;">
                    СЛУЖБА ПОДДЕРЖКИ ТИТАНИК 2
                </h1>
                <p class="lead" style="color: #fcd34d; font-style: italic;">
                    Мы на связи. В отличие от оригинала.
                </p>
            </div>
            <div class="mb-4" style="background: #1e293b; border: 2px solid #fbbf24; border-radius: 12px; padding: 2rem;">
                <div class="text-center mb-4">
                    <h3 style="color: #fbbf24; font-family: Georgia, serif; margin-top: 1rem;">
                        ВАЖНОЕ ОБЪЯВЛЕНИЕ
                    </h3>
                    <p style="color: #fcd34d; font-size: 1.1rem; margin-top: 1rem;">
                        В отличие от событий 1912 года, мы <strong>ГАРАНТИРУЕМ</strong>:<br>
                        ✓ Достаточное количество спасательных шлюпок<br>
                        ✓ Наличие радара для обнаружения айсбергов<br>
                        ✓ GPS-навигацию (спутники появились после инцидента)<br>
                        ✓ Работающую рацию в любое время суток
                    </p>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div style="background: #334155; border: 2px solid #475569; border-radius: 12px; padding: 2rem; height: 100%; transition: all 0.3s ease;">
                        <h4 style="color: #fbbf24; text-align: center; margin-bottom: 1rem;">
                            Голубиная почта
                        </h4>
                        <p style="color: #94a3b8; text-align: center; line-height: 1.6;">
                            <strong style="color: #fcd34d;">Доступно:</strong> Когда корабль на берегу<br>
                            <strong style="color: #fcd34d;">Время доставки:</strong> 2-3 недели*<br>
                            <strong style="color: #fcd34d;">Надёжность:</strong> Зависит от погоды<br>
                            <em style="font-size: 0.85rem; color: #64748b;">*Если голубь не заблудился</em>
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div style="background: #334155; border: 2px solid #475569; border-radius: 12px; padding: 2rem; height: 100%; transition: all 0.3s ease;">
                        <h4 style="color: #fbbf24; text-align: center; margin-bottom: 1rem;">
                            Телеграф Морзе
                        </h4>
                        <p style="color: #94a3b8; text-align: center; line-height: 1.6;">
                            <strong style="color: #fcd34d;">Код SOS:</strong> • • • — — — • • •<br>
                            <strong style="color: #fcd34d;">Доступно:</strong> 24/7 (теперь!)<br>
                            <strong style="color: #fcd34d;">Примечание:</strong> Оператор не спит<br>
                            <em style="font-size: 0.85rem; color: #64748b;">Урок 1912 года усвоен</em>
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div style="background: #334155; border: 2px solid #475569; border-radius: 12px; padding: 2rem; height: 100%; transition: all 0.3s ease;">
                        <h4 style="color: #fbbf24; text-align: center; margin-bottom: 1rem;">
                            Сигнальные ракеты
                        </h4>
                        <p style="color: #94a3b8; text-align: center; line-height: 1.6;">
                            <strong style="color: #fcd34d;">Использовать:</strong> Только в крайнем случае<br>
                            <strong style="color: #fcd34d;">Цвет:</strong> Красный (белые не сработали в 1912)<br>
                            <strong style="color: #fcd34d;">Эффект:</strong> Видно за 50 км<br>
                            <em style="font-size: 0.85rem; color: #64748b;">На этот раз нас точно заметят</em>
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div style="background: #334155; border: 2px solid #475569; border-radius: 12px; padding: 2rem; height: 100%; transition: all 0.3s ease;">
                        <h4 style="color: #fbbf24; text-align: center; margin-bottom: 1rem;">
                            Email (XXI век!)
                        </h4>
                        <p style="color: #94a3b8; text-align: center; line-height: 1.6;">
                            <strong style="color: #fcd34d;">Адрес:</strong> support@titanic2.com<br>
                            <strong style="color: #fcd34d;">Время ответа:</strong> 24 часа<br>
                            <strong style="color: #fcd34d;">Интернет:</strong> Starlink на борту!<br>
                            <em style="font-size: 0.85rem; color: #64748b;">Прогресс не стоит на месте</em>
                        </p>
                    </div>
                </div>
            </div>


            <div style="background: #1e293b; border: 2px solid #334155; border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
                <h3 style="color: #fbbf24; text-align: center; margin-bottom: 2rem; font-family: Georgia, serif;">
                    ЧАСТО ЗАДАВАЕМЫЕ ВОПРОСЫ
                </h3>

                <div style="margin-bottom: 1.5rem;">
                    <h5 style="color: #fcd34d; margin-bottom: 0.5rem;">
                        А что насчёт айсбергов?
                    </h5>
                    <p style="color: #94a3b8; padding-left: 1.5rem;">
                        У нас есть: GPS, радар, сонар, спутниковая связь, и главное — мы СМОТРИМ ВПЕРЁД.
                        Плюс глобальное потепление помогло уменьшить их количество (единственный плюс).
                    </p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h5 style="color: #fcd34d; margin-bottom: 0.5rem;">
                        Хватит ли спасательных шлюпок?
                    </h5>
                    <p style="color: #94a3b8; padding-left: 1.5rem;">
                        Да. У нас места для 150% пассажиров. Мы учли ошибки прошлого.
                        И да, мы проводим учения, а не просто возим их для красоты.
                    </p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h5 style="color: #fcd34d; margin-bottom: 0.5rem;">
                        Будет ли оркестр играть до последнего?
                    </h5>
                    <p style="color: #94a3b8; padding-left: 1.5rem;">
                        Надеемся, что не придётся проверять. Но оркестр у нас есть, и они знают "Nearer, My God, to Thee"
                        (просто на всякий случай, для ностальгии).
                    </p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h5 style="color: #fcd34d; margin-bottom: 0.5rem;">
                        Можно ли стоять на носу корабля как в фильме?
                    </h5>
                    <p style="color: #94a3b8; padding-left: 1.5rem;">
                        Можно! Но только при наличии страховки. И мы не несём ответственности за вашу любовную историю.
                        P.S. Пожалуйста, не лезьте через перила.
                    </p>
                </div>

                <div>
                    <h5 style="color: #fcd34d; margin-bottom: 0.5rem;">
                        Что если я потеряю свой билет?
                    </h5>
                    <p style="color: #94a3b8; padding-left: 1.5rem;">
                        В отличие от 1912 года, у нас есть электронная база данных. И QR-коды. И дублирование на email.
                        И в облаке. Потерять билет практически невозможно.
                    </p>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, #1e293b, #334155); border: 3px solid #fbbf24; border-radius: 12px; padding: 2rem; text-align: center;">
                <h4 style="color: #fbbf24; font-family: Georgia, serif; margin-bottom: 1rem;">
                    ГАРАНТИЯ БЕЗОПАСНОСТИ
                </h4>
                <p style="color: #fcd34d; font-size: 1.2rem; line-height: 1.8; margin-bottom: 1rem;">
                    "Непотопляемый" — мы больше так не говорим.<br>
                    Но "Очень безопасный с кучей резервных систем" — звучит честнее!
                </p>
                <p style="color: #94a3b8; font-size: 0.9rem; font-style: italic;">
                    * Компания Титаник 2 не несёт ответственности за травмы, полученные во время попыток воссоздать сцены из фильма<br>
                    ** Айсберги в цену не включены<br>
                    *** История не повторится (мы обещаем)
                </p>
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('shop') }}" style="background: #fbbf24; color: #1e293b; padding: 1rem 2rem; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block; transition: all 0.3s ease;">
                        Забронировать безопасный рейс
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
