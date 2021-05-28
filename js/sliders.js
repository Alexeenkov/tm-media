// Инициализируем Swiper
new Swiper('.slider', {
    navigation: {
        nextEl: '.category-slider__next',
        prevEl: '.category-slider__prev'
    },
    // Количество слайдов для показа
    slidesPerView: '2',
    // если слайдов меньше чем нужно
    watchOverflow: true,
    // Отступ между слайдами
    spaceBetween: 11,
    breakpoints: {
        615: {
            slidesPerView: 2,
            spaceBetween: 11,
        },
        920: {
            slidesPerView: 4,
            spaceBetween: 19,
        },
        1217: {
            slidesPerView: 6,
            spaceBetween: 19,
        }
    },
    // Бесконечный слайдер
    loop: true,
});