// Инициализируем Swiper
new Swiper('.slider', {
    navigation: {
        nextEl: '.feedback__next',
        prevEl: '.feedback__prev'
    },
    // Количество слайдов для показа
    slidesPerView: 2,
    slidesPerGroup: 2,
    // если слайдов меньше чем нужно
    watchOverflow: true,
    // Бесконечный слайдер
    loop: true,
});