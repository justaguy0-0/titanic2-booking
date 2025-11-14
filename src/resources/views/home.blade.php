@extends('head')

@section('main_content')

<section class="hero-section text-center py-5">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3" style="font-family: Georgia, serif; color: #fbbf24;">ТИТАНИК 2</h1>
        <p class="lead mb-4" style="font-family: Georgia, serif; color: #fcd34d; font-style: italic;">Легенда возвращается в будущее</p>


        <div class="company-info mb-4" style="max-width: 800px; margin: 0 auto;">
            <p class="fs-5 mb-3" style="color: #fcd34d;">
                Откройте для себя новый уровень морских путешествий - с возможностью вернуться домой.
            </p>
        </div>
     </div>
</section>


<section class="py-5" style="
    background: #0f172a;
    width: 100vw;
    margin-left: -50vw;
    left: 50%;
    position: relative;">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold" style="color: #fbbf24; font-family: Georgia, serif;">ЛЕГЕНДА И СОВРЕМЕННОСТЬ</h2>

        </div>
    </section>
</div>
        <div class="row g-4">

            <div class="col-md-6">
                <div class="cabin-comparison-card rounded" style="background: #1e293b; border: 2px solid #fbbf24; overflow: hidden;">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="p-3 text-center">
                                <h5 style="color: #fbbf24;">1912 ГОД</h5>
                                <div class="cabin-image mb-3" style="height: 200px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/rouz.webp'); background-size: cover; background-position: center; border: 1px solid #fbbf24;"></div>
                                <p style="color: #fcd34d; font-size: 0.9rem;">Каюта Роуз Дьюитт Бьюкейтер</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 text-center" style="background: #334155;">
                                <h5 style="color: #fbbf24;">2042 ГОД</h5>
                                <div class="cabin-image mb-3" style="height: 200px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/cabincost.jpg'); background-size: cover; background-position: center; border: 1px solid #fbbf24;"></div>
                                <p style="color: #fcd34d; font-size: 0.9rem;">Президентский люкс</p>
                                <div class="price-tag mb-2" style="color: #fbbf24; font-weight: bold;">650.000 руб.</div>
                                <ul class="list-unstyled" style="color: #94a3b8; font-size: 0.8rem;">
                                    <li>• Панорамные окна</li>
                                    <li>• Персональный дворецкий</li>
                                    <li>• Гидромассажная ванна</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="cabin-comparison-card rounded" style="background: #1e293b; border: 2px solid #fbbf24; overflow: hidden;">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="p-3 text-center">
                                <h5 style="color: #fbbf24;">1912 ГОД</h5>
                                <div class="cabin-image mb-3" style="height: 200px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/djekcabin.jpg'); background-size: cover; background-position: center; border: 1px solid #fbbf24;"></div>
                                <p style="color: #fcd34d; font-size: 0.9rem;">Каюта Джека Доусона</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 text-center" style="background: #334155;">
                                <h5 style="color: #fbbf24;">2042 ГОД</h5>
                                <div class="cabin-image mb-3" style="height: 200px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/cabinchip.png'); background-size: cover; background-position: center; border: 1px solid #fbbf24;"></div>
                                <p style="color: #fcd34d; font-size: 0.9rem;">Эконом класс</p>
                                <div class="price-tag mb-2" style="color: #fbbf24; font-weight: bold;">250.000 руб.</div>
                                <ul class="list-unstyled" style="color: #94a3b8; font-size: 0.8rem;">
                                    <li>• Удобные кровати</li>
                                    <li>• Собственный санузел</li>
                                    <li>• Wi-Fi и телевизор</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row g-4 mt-2">

            <div class="col-md-6">
                <div class="cabin-comparison-card rounded" style="background: #1e293b; border: 2px solid #fbbf24; overflow: hidden;">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="p-3 text-center">
                                <h5 style="color: #fbbf24;">1912 ГОД</h5>
                                <div class="cabin-image mb-3" style="height: 200px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/lestni.webp'); background-size: cover; background-position: center; border: 1px solid #fbbf24;"></div>
                                <p style="color: #fcd34d; font-size: 0.9rem;">Парадная лестница</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 text-center" style="background: #334155;">
                                <h5 style="color: #fbbf24;">2042 ГОД</h5>
                                <div class="cabin-image mb-3" style="height: 200px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/film.jpg'); background-size: cover; background-position: center; border: 1px solid #fbbf24;"></div>
                                <p style="color: #fcd34d; font-size: 0.9rem;">Главный атриум</p>
                                <div class="price-tag mb-2" style="color: #fbbf24; font-weight: bold;">Общественное</div>
                                <ul class="list-unstyled" style="color: #94a3b8; font-size: 0.8rem;">
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
                <div class="cabin-comparison-card rounded" style="background: #1e293b; border: 2px solid #fbbf24; overflow: hidden;">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="p-3 text-center">
                                <h5 style="color: #fbbf24;">1912 ГОД</h5>
                                <div class="cabin-image mb-3" style="height: 200px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/restfilm.webp'); background-size: cover; background-position: center; border: 1px solid #fbbf24;"></div>
                                <p style="color: #fcd34d; font-size: 0.9rem;">Ресторан 1 класса</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 text-center" style="background: #334155;">
                                <h5 style="color: #fbbf24;">2042 ГОД</h5>
                                <div class="cabin-image mb-3" style="height: 200px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/film2.jpg'); background-size: cover; background-position: center; border: 1px solid #fbbf24;"></div>
                                <p style="color: #fcd34d; font-size: 0.9rem;">Гранд Салон</p>
                                <div class="price-tag mb-2" style="color: #fbbf24; font-weight: bold;">Ресторан</div>
                                <ul class="list-unstyled" style="color: #94a3b8; font-size: 0.8rem;">
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
                <div class="p-4 rounded" style="background: #334155; border: 1px solid #fbbf24;">
                    <h5 style="color: #fbbf24;"> Сохраняя дух, внедряя инновации</h5>
                    <p style="color: #fcd34d;">
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
        <h2 class="text-center mb-5 fw-bold" style="color: #fbbf24; font-family: Georgia, serif;">Возможности круиза</h2>


        <div class="row align-items-center mb-5 g-4">
            <div class="col-md-6">
                <div class="entertainment-image rounded" style="height: 300px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/rest.jpg'); background-size: cover; background-position: center; border: 2px solid #fbbf24;"></div>
            </div>
            <div class="col-md-6">
                <h3 style="color: #fbbf24;">Вечерний ужин на палубе</h3>
                <p style="color: #fcd34d;">Роскошный ужин при свечах с видом на океан. Шеф-повар мирового класса.</p>
                <div class="price-tag" style="color: #fbbf24; font-size: 1.2rem; font-weight: bold;">
                    $150 на человека
                </div>
                <div class="mt-3">
                    <span class="badge" style="background: #fbbf24; color: #1e293b;">18:00-23:00</span>
                    <span class="badge ms-2" style="background: #334155; color: #fcd34d;">Романтическое</span>
                </div>
            </div>
        </div>


        <div class="row align-items-center mb-5 g-4">
            <div class="col-md-6 order-md-2">
                <div class="entertainment-image rounded" style="height: 300px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/spa.jpg'); background-size: cover; background-position: center; border: 2px solid #fbbf24;"></div>
            </div>
            <div class="col-md-6 order-md-1">
                <h3 style="color: #fbbf24;"> Спа-процедуры</h3>
                <p style="color: #fcd34d;">Премиум спа-комплекс с талассотерапией, массажем и косметическими процедурами.</p>
                <div class="price-tag" style="color: #fbbf24; font-size: 1.2rem; font-weight: bold;">
                    от $200
                </div>
                <div class="mt-3">
                    <span class="badge" style="background: #fbbf24; color: #1e293b;">09:00-21:00</span>
                    <span class="badge ms-2" style="background: #334155; color: #fcd34d;">Релакс</span>
                </div>
            </div>
        </div>


        <div class="row align-items-center mb-5 g-4">
            <div class="col-md-6">
                <div class="entertainment-image rounded" style="height: 300px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/mus.jpg'); background-size: cover; background-position: center; border: 2px solid #fbbf24;"></div>
            </div>
            <div class="col-md-6">
                <h3 style="color: #fbbf24;">Концерт оркестра</h3>
                <p style="color: #fcd34d;">Живая музыка в исполнении симфонического оркестра. Классические и современные произведения.</p>
                <div class="price-tag" style="color: #fbbf24; font-size: 1.2rem; font-weight: bold;">
                    Включено в стоимость
                </div>
                <div class="mt-3">
                    <span class="badge" style="background: #fbbf24; color: #1e293b;">20:00-22:00</span>
                    <span class="badge ms-2" style="background: #334155; color: #fcd34d;">Культурное</span>
                </div>
            </div>
        </div>


        <div class="row align-items-center mb-5 g-4">
            <div class="col-md-6 order-md-2">
                <div class="entertainment-image rounded" style="height: 300px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/child.jpg'); background-size: cover; background-position: center; border: 2px solid #fbbf24;"></div>
            </div>
            <div class="col-md-6 order-md-1">
                <h3 style="color: #fbbf24;">Детский клуб "Морские приключения"</h3>
                <p style="color: #fcd34d;">Анимационная программа, мастер-классы, игры и развлечения для детей всех возрастов под присмотром профессиональных воспитателей.</p>
                <div class="price-tag" style="color: #fbbf24; font-size: 1.2rem; font-weight: bold;">
                    Бесплатно для детей
                </div>
                <div class="mt-3">
                    <span class="badge" style="background: #fbbf24; color: #1e293b;">10:00-18:00</span>
                    <span class="badge ms-2" style="background: #334155; color: #fcd34d;">Детское</span>
                    <span class="badge ms-2" style="background: #dc2626; color: white;">3-12 лет</span>
                </div>
            </div>
        </div>


        <div class="row align-items-center mb-5 g-4">
            <div class="col-md-6">
                <div class="entertainment-image rounded" style="height: 300px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/akro.jpg'); background-size: cover; background-position: center; border: 2px solid #fbbf24;"></div>
            </div>
            <div class="col-md-6">
                <h3 style="color: #fbbf24;"> Шоу воздушных акробатов</h3>
                <p style="color: #fcd34d;">Захватывающее представление профессиональных акробатов под куполом главного атриума. Огни, музыка и невероятные трюки.</p>
                <div class="price-tag" style="color: #fbbf24; font-size: 1.2rem; font-weight: bold;">
                    $50 на человека
                </div>
                <div class="mt-3">
                    <span class="badge" style="background: #fbbf24; color: #1e293b;">21:00</span>
                    <span class="badge ms-2" style="background: #334155; color: #fcd34d;">Шоу</span>
                    <span class="badge ms-2" style="background: #dc2626; color: white;">Экстрим</span>
                </div>
            </div>
        </div>


        <div class="row align-items-center g-4">
            <div class="col-md-6 order-md-2">
                <div class="entertainment-image rounded" style="height: 300px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/dance.webp'); background-size: cover; background-position: center; border: 2px solid #fbbf24;"></div>
            </div>
            <div class="col-md-6 order-md-1">
                <h3 style="color: #fbbf24;">Танцевальный вечер в бальном зале</h3>
                <p style="color: #fcd34d;">Роскошный бал в стиле 20-х годов. Живой джаз-бэнд, профессиональные танцоры и уроки исторических танцев.</p>
                <div class="price-tag" style="color: #fbbf24; font-size: 1.2rem; font-weight: bold;">
                    $75 на человека
                </div>
                <div class="mt-3">
                    <span class="badge" style="background: #fbbf24; color: #1e293b;">19:00-01:00</span>
                    <span class="badge ms-2" style="background: #334155; color: #fcd34d;">Вечеринка</span>
                    <span class="badge ms-2" style="background: #7c3aed; color: white;">Винтаж</span>
                </div>
            </div>
        </div>

        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <div class="entertainment-image rounded" style="height: 300px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/ioga.jpg'); background-size: cover; background-position: center; border: 2px solid #fbbf24;"></div>
            </div>
            <div class="col-md-6 ">
                <h3 style="color: #fbbf24;">Йога для самых здоровых</h3>
                <p style="color: #fcd34d;">Дневные занятия на свежем воздухе. Под шум волн.</p>
                <div class="price-tag" style="color: #fbbf24; font-size: 1.2rem; font-weight: bold;">
                    $375 на человека(абонимент)
                </div>
                <div class="mt-3">
                    <span class="badge" style="background: #fbbf24; color: #1e293b;">9:00-10:30</span>
                    <span class="badge ms-2" style="background: #334155; color: #fcd34d;">Спорт</span>
                    <span class="badge ms-2" style="background: #7c3aed; color: white;">Здоровый образ жизни</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background: #1e293b;">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold" style="color: #fbbf24; font-family: Georgia, serif;">РЕСТОРАНЫ И ПИТАНИЕ</h2>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="restaurant-card text-center">
                    <div class="restaurant-image rounded mb-3" style="height: 250px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/rest1.webp'); background-size: cover; background-position: center; border: 2px solid #fbbf24;"></div>
                    <h4 style="color: #fbbf24;">"Палуба Нептуна"</h4>
                    <p style="color: #fcd34d;" class="small">Ресторан под открытым небом</p>
                    <p style="color: #94a3b8;" class="small">
                        Романтические ужины при свечах с панорамным видом на океан.
                        Свежие морепродукты и средиземноморская кухня.
                    </p>
                    <div class="mt-3">
                        <span class="badge" style="background: #fbbf24; color: #1e293b;">19:00-23:00</span>
                        <span class="badge ms-2" style="background: #334155; color: #fcd34d;">А la carte</span>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="restaurant-card text-center">
                    <div class="restaurant-image rounded mb-3" style="height: 250px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/rest2.jpg'); background-size: cover; background-position: center; border: 2px solid #fbbf24;"></div>
                    <h4 style="color: #fbbf24;">"Гранд Салон"</h4>
                    <p style="color: #fcd34d;" class="small">Премиум ресторан</p>
                    <p style="color: #94a3b8;" class="small">
                        Роскошный интерьер в стиле ар-деко. Европейская кухня от шеф-повара с мишленовскими звездами.
                        Винная карта премиум-класса.
                    </p>
                    <div class="mt-3">
                        <span class="badge" style="background: #fbbf24; color: #1e293b;">18:00-00:00</span>
                        <span class="badge ms-2" style="background: #334155; color: #fcd34d;">Премиум</span>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="restaurant-card text-center">
                    <div class="restaurant-image rounded mb-3" style="height: 250px; background: linear-gradient(rgba(30, 41, 59, 0.7), rgba(30, 41, 59, 0.7)), url('/images/Rest3_new.jpg'); background-size: cover; background-position: center; border: 2px solid #fbbf24;"></div>
                    <h4 style="color: #fbbf24;">"Океанский Фуршет"</h4>
                    <p style="color: #fcd34d;" class="small">Общая столовая</p>
                    <p style="color: #94a3b8;" class="small">
                        3-разовое питание по системе "все включено". Широкий выбор блюд международной кухни,
                        свежие салаты, десерты и напитки.
                    </p>
                    <div class="mt-3">
                        <span class="badge" style="background: #fbbf24; color: #1e293b;">07:00-22:00</span>
                        <span class="badge ms-2" style="background: #334155; color: #fcd34d;">Все включено</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
</div>
@endsection

