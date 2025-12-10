@extends('head')

@section('main_content')

<section class="hero-section text-center py-5">
    <img src="/images/i.webp" alt="Титаник 2">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3 hero-title">ТИТАНИК 2</h1>
        <p class="lead mb-4 hero-subtitle">Легенда возвращается в будущее</p>


        <div class="company-info mb-4">
            <p class="fs-5 mb-3 company-text">
                Откройте для себя новый уровень морских путешествий - с возможностью вернуться домой.
            </p>
        </div>
     </div>
</section>


<section class="section-dark py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold section-title fs-3">ЛЕГЕНДА И СОВРЕМЕННОСТЬ</h2>

        <div class="row g-4">

            <div class="col-md-6">
                <div class="cabin-comparison-card rounded">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="p-3 text-center">
                                <h5 class="cabin-year">1912 ГОД</h5>
                                <img src="{{ asset('images/cabinaf.webp') }}" alt="Кабина капитана" class="cabin-image mb-3">
                                <p class="cabin-description">Кабина капитана</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 text-center cabin-modern">
                                <h5 class="cabin-year">2042 ГОД</h5>
                                <img src="{{ asset('images/cabinareal.jpg') }}" alt="Новое управление" class="cabin-image mb-3">
                                <p class="cabin-description">Новое управление</p>
                                <div class="price-tag mb-2">Обеспечение</div>
                                <ul class="list-unstyled cabin-features">
                                    <li>• Улучшенная версия</li>
                                    <li>• Динамичное движение</li>
                                    <li>• Воссоздание дизайна</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="cabin-comparison-card rounded">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="p-3 text-center">
                                <h5 class="cabin-year">1912 ГОД</h5>
                                <img src="{{ asset('images/nosfilm.webp') }}" alt="Нос корабля" class="cabin-image mb-3">
                                <p class="cabin-description">Нос корабля</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 text-center cabin-modern">
                                <h5 class="cabin-year">2042 ГОД</h5>
                                <img src="{{ asset('images/realnos.webp') }}" alt="Достопримечательность" class="cabin-image mb-3">
                                <p class="cabin-description">Достопримечательность</p>
                                <div class="price-tag mb-2">Место для ваших фото</div>
                                <ul class="list-unstyled cabin-features">
                                    <li>• Полная копия момента из фильма</li>
                                    <li>• Лучший вид</li>
                                    <li>• Конкурс лучшего фото</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row g-4 mt-2">

            <div class="col-md-6">
                <div class="cabin-comparison-card rounded">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="p-3 text-center">
                                <h5 class="cabin-year">1912 ГОД</h5>
                                <img src="{{ asset('images/lestni.webp') }}" alt="Парадная лестница" class="cabin-image mb-3">
                                <p class="cabin-description">Парадная лестница</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 text-center cabin-modern">
                                <h5 class="cabin-year">2042 ГОД</h5>
                                <img src="{{ asset('images/film.jpg') }}" alt="Главный атриум" class="cabin-image mb-3">
                                <p class="cabin-description">Главный атриум</p>
                                <div class="price-tag mb-2">Общественное</div>
                                <ul class="list-unstyled cabin-features">
                                    <li>• 3-уровневый атриум</li>
                                    <li>• Хрустальные люстры</li>
                                    <li>• Живая музыка</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="cabin-comparison-card rounded">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="p-3 text-center">
                                <h5 class="cabin-year">1912 ГОД</h5>
                                <img src="{{ asset('images/restfilm.webp') }}" alt="Ресторан 1 класса" class="cabin-image mb-3">
                                <p class="cabin-description">Ресторан 1 класса</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 text-center cabin-modern">
                                <h5 class="cabin-year">2042 ГОД</h5>
                                <img src="{{ asset('images/film2.jpg') }}" alt="Гранд Салон" class="cabin-image mb-3">
                                <p class="cabin-description">Гранд Салон</p>
                                <div class="price-tag mb-2">Ресторан</div>
                                <ul class="list-unstyled cabin-features">
                                    <li>• Шеф-повар с мишленом</li>
                                    <li>• Вид на океан</li>
                                    <li>• Винная карта</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-5">
            <div class="col-12 text-center">
                <div class="info-box p-4 rounded">
                    <h5 class="info-box-title"> Сохраняя дух, внедряя инновации</h5>
                    <p class="info-box-text">
                        Мы бережно воссоздали атмосферу оригинала, но добавили современный комфорт и технологии.
                        От классических интерьеров до умных кают - каждая деталь продумана для вашего удовольствия.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background: #0f172a;">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold section-title fs-3">ВОЗМОЖНОСТИ КРУИЗА</h2>

        @foreach($entertainmentForHome as $index => $entertainment)
        <div class="row align-items-center mb-5 g-4">
            <div class="col-md-6 {{ $index % 2 != 0 ? 'order-md-2' : '' }}">
                <img src="{{ asset('images/' . $entertainment->image) }}" alt="{{ $entertainment->name }}" class="entertainment-image rounded">
            </div>
            <div class="col-md-6 {{ $index % 2 != 0 ? 'order-md-1' : '' }}">
                <h3 class="entertainment-title">{{ $entertainment->name }}</h3>
                <p class="entertainment-text">{{ $entertainment->description }}</p>
                <div class="price-tag" style="font-size: 1.2rem;">
                    @if($entertainment->price == 0)
                        Бесплатно
                    @else
                        {{ number_format($entertainment->price, 0, ',', ' ') }} руб.
                    @endif
                </div>
                <div class="mt-3">
                    <span class="badge badge-dark ms-2">{{ $entertainment->category }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
<section class="py-5" style="background: #1e293b;">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold section-title">РЕСТОРАНЫ И ПИТАНИЕ</h2>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="restaurant-card text-center">
                    <img src="{{ asset('images/rest1.webp') }}" alt="Палуба Нептуна" class="restaurant-image rounded mb-3">
                    <h4 class="restaurant-title">"Палуба Нептуна"</h4>
                    <p class="restaurant-subtitle small">Ресторан под открытым небом</p>
                    <p class="restaurant-description small">
                        Романтические ужины при свечах с панорамным видом на океан.
                        Свежие морепродукты и средиземноморская кухня.
                    </p>
                    <div class="mt-3">
                        <span class="badge badge-gold">19:00-23:00</span>
                        <span class="badge badge-dark ms-2">А la carte</span>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="restaurant-card text-center">
                    <img src="{{ asset('images/rest2.jpg') }}" alt="Гранд Салон" class="restaurant-image rounded mb-3">
                    <h4 class="restaurant-title">"Гранд Салон"</h4>
                    <p class="restaurant-subtitle small">Премиум ресторан</p>
                    <p class="restaurant-description small">
                        Роскошный интерьер в стиле ар-деко. Европейская кухня от шеф-повара с мишленовскими звездами.
                        Винная карта премиум-класса.
                    </p>
                    <div class="mt-3">
                        <span class="badge badge-gold">18:00-00:00</span>
                        <span class="badge badge-dark ms-2">Премиум</span>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="restaurant-card text-center">
                    <img src="{{ asset('images/Rest3_new.jpg') }}" alt="Океанский Фуршет" class="restaurant-image rounded mb-3">
                    <h4 class="restaurant-title">"Океанский Фуршет"</h4>
                    <p class="restaurant-subtitle small">Общая столовая</p>
                    <p class="restaurant-description small">
                        3-разовое питание по системе "все включено". Широкий выбор блюд международной кухни,
                        свежие салаты, десерты и напитки.
                    </p>
                    <div class="mt-3">
                        <span class="badge badge-gold">07:00-22:00</span>
                        <span class="badge badge-dark ms-2">Все включено</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
</div>
@endsection
