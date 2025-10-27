<<<<<<< HEAD
const left = document.querySelector('.left');
const right = document.querySelector('.right');
const slider = document.querySelector('.slider');
const images = document.querySelectorAll('.image');

let slideNumber = 1;
const length = images.length;

const nextSlide = () => {
    slider.style.transform = `translateX(-${slideNumber*800}px)`;
    slideNumber++;
};

const prevSlide = () => {
    slider.style.transform = `translateX(-${(slideNumber-2)*800}px)`;
    slideNumber--;
};

const getFirstSlide = () => {
    slider.style.transform = `translateX(0px)`;
    slideNumber = 1;
};

const getLastSlide = () => {
    slider.style.transform = `translateX(-${(length-1)*800}px)`;
    slideNumber = length;
}

right.addEventListener('click', () => {
    slideNumber < length ? nextSlide() : getFirstSlide();
});
left.addEventListener('click', () => {
    slideNumber > 1 ? prevSlide() : getLastSlide ();
});
=======
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

/* auto play carousel */
let slideInterval;
const startSlideShow = () => {
  slideInterval = setInterval(() => {
    TrandingSlider.slideNext();
  }, 4000);
};
const stopSlideShow = () => {
  clearInterval(slideInterval);
};

startSlideShow();
TrandingSlider.el.addEventListener('mouseover', stopSlideShow);
TrandingSlider.el.addEventListener('mouseout', startSlideShow);
TrandingSlider.nextEl.addEventListener('mouseover', stopSlideShow);
TrandingSlider.nextEl.addEventListener('mouseout', startSlideShow);
TrandingSlider.prevEl.addEventListener('mouseover', stopSlideShow);
TrandingSlider.prevEl.addEventListener('mouseout', startSlideShow);
TrandingSlider.pagination.addEventListener('mouseover', stopSlideShow);
TrandingSlider.pagination.addEventListener('mouseout', startSlideShow);
/* end auto play carousel */
>>>>>>> main
