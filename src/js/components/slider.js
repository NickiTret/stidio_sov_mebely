// Подключение свайпера
// import Swiper, { Navigation, Pagination } from "swiper";
// Swiper.use([Navigation, Pagination]);


const sliders = Array.from(document.querySelectorAll('.swiper'));

if (sliders) {
    sliders.forEach((slider) => {
        new Swiper(slider, {
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        })
    });
}

// const swiper2 = new Swiper(".mySwiper2", {
//     spaceBetween: 10,
//     navigation: {
//         nextEl: ".swiper-button-next",
//         prevEl: ".swiper-button-prev",
//     },
// });
