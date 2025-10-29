// --- Swiper Carousel ---
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

/* --- Auto Play Carousel --- */
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

// Stop autoplay when hovering over slider or controls
TrandingSlider.el.addEventListener('mouseover', stopSlideShow);
TrandingSlider.el.addEventListener('mouseout', startSlideShow);

// Safely handle nav & pagination hover events
const nextEl = document.querySelector('.swiper-button-next');
const prevEl = document.querySelector('.swiper-button-prev');
const paginationEl = document.querySelector('.swiper-pagination');

[nextEl, prevEl, paginationEl].forEach(el => {
  if (el) {
    el.addEventListener('mouseover', stopSlideShow);
    el.addEventListener('mouseout', startSlideShow);
  }
});
/* --- End Auto Play Carousel --- */

// --- Popup Logic ---
document.addEventListener("DOMContentLoaded", () => {
  const menuItems = document.querySelectorAll(".menu-container");
  const popups = document.querySelectorAll(".popup-overlay");

  menuItems.forEach((menuItem, index) => {
    const popup = popups[index];
    if (!popup) return; // Safety check if mismatch

    const closeBtn = popup.querySelector(".close-btn");
    const countValue = popup.querySelector("#countValue");
    const plusBtn = popup.querySelector("#plus");
    const minusBtn = popup.querySelector("#minus");

    // Open popup
    menuItem.addEventListener("click", () => {
      popup.style.display = "flex";
    });

    // Close popup
    closeBtn?.addEventListener("click", () => {
      popup.style.display = "none";
    });

    // Close when clicking outside content
    popup.addEventListener("click", (e) => {
      if (e.target === popup) popup.style.display = "none";
    });

    // Quantity controls
    let count = 1;
    plusBtn?.addEventListener("click", () => {
      count++;
      if (countValue) countValue.textContent = count;
    });

    minusBtn?.addEventListener("click", () => {
      if (count > 1) count--;
      if (countValue) countValue.textContent = count;
    });

    // Option button selection (sauce, mix, etc.)
    popup.querySelectorAll(".option").forEach(btn => {
      btn.addEventListener("click", () => {
        const siblings = btn.parentElement.querySelectorAll(".option");
        siblings.forEach(b => b.classList.remove("selected"));
        btn.classList.add("selected");
      });
    });
  });
});
