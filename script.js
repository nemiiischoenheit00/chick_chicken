var TrandingSlider = new Swiper('.tranding-slider', {
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    loop: true,
    slidesPerView: 'auto',
    coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 100,
        modifier: 2.5,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    }
});

/* Auto play carousel */
let slideInterval;
const startSlideShow = () => {
    slideInterval = setInterval(() => {
        TrandingSlider.slideNext();
    }, 4000);
};
const stopSlideShow = () => clearInterval(slideInterval);

startSlideShow();

// Pause autoplay when hovering over Swiper container
TrandingSlider.el.addEventListener('mouseover', stopSlideShow);
TrandingSlider.el.addEventListener('mouseout', startSlideShow);

/* FAQ DROPDOWN */
document.addEventListener("DOMContentLoaded", () => {
    const faqs = document.querySelectorAll(".faq");

    console.log("FAQs found:", faqs.length);

    faqs.forEach((faq) => {
        const question = faq.querySelector(".question");
        if (question) {
            question.addEventListener("click", () => {
                console.log("Clicked:", question.textContent.trim());
                faq.classList.toggle("active");
            });
        }
    });
});