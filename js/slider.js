// Инициализируем Swiper
new Swiper('.slider', {
    navigation: {
        nextEl: '.feedback__next',
        prevEl: '.feedback__prev'
    },
    // Количество слайдов для показа
    slidesPerView: 1,
    slidesPerGroup: 1,
    // если слайдов меньше чем нужно
    watchOverflow: true,
    // Бесконечный слайдер
    loop: true,
    breakpoints: {
        900: {
            slidesPerView: 2,
            slidesPerGroup: 2,
        },
    },
});