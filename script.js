
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


window.addEventListener("load", () => {
  const disclaimer = document.getElementById("disclaimer");
  const closeBtn = document.getElementById("closeBtn");

  if (!disclaimer) {
    console.error("❌ Disclaimer element not found!");
    return;
  }

  disclaimer.classList.add("active");

  if (closeBtn) {
    closeBtn.addEventListener("click", () => {
      disclaimer.classList.remove("active");
    });
  } else {
    console.error("❌ Close button not found!");
  }
});
