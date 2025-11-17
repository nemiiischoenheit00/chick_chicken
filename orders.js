document.addEventListener("DOMContentLoaded", () => {
  console.log("orders.js loaded");

  // Elements
  const menuItems = Array.from(document.querySelectorAll(".menu-container"));
  const popups = Array.from(document.querySelectorAll(".popup-overlay"));
  const cartOverlay = document.getElementById("cart-overlay");
  const cartPanel = document.getElementById("cart-panel");
  const cartBtn = document.getElementById("cart-btn");
  const closeCartBtn = document.getElementById("close-cart");
  const cartItemsDiv = document.getElementById("cart-items");

  let cart = [];

  // --- Link each menu item to its popup by index ---
  menuItems.forEach((menuItem, idx) => {
    const popup = popups[idx];

    menuItem.addEventListener("click", (e) => {
      e.preventDefault();
      if (!popup) return;
      popup.classList.add("open");
      popup.style.display = "flex";
      resetPopup(popup);
      setupExtrasToggle(popup);
      updatePopupPriceDisplay(popup, 1);
    });
  });

  // --- Close popup actions ---
  popups.forEach((popup) => {
    const closeBtn = popup.querySelector(".close-btn");
    closeBtn?.addEventListener("click", () => closePopup(popup));
    popup.addEventListener("click", (e) => {
      if (e.target === popup) closePopup(popup);
    });
  });

  // --- Quantity handlers inside popups ---
  popups.forEach((popup, idx) => {
    const plus = popup.querySelector("#plus");
    const minus = popup.querySelector("#minus");
    const countSpan = popup.querySelector("#countValue");

    let count = 1;
    if (countSpan) countSpan.textContent = "1";

    plus?.addEventListener("click", () => {
      count++;
      if (countSpan) countSpan.textContent = count;
      updatePopupPriceDisplay(popup, count);
    });

    minus?.addEventListener("click", () => {
      if (count > 1) count--;
      if (countSpan) countSpan.textContent = count;
      updatePopupPriceDisplay(popup, count);
    });
  });

  // --- Option selection handlers ---
  popups.forEach((popup) => {
    popup.querySelectorAll(".options, .flavors, .mix").forEach((group) => {
      group.addEventListener("click", (ev) => {
        const btn = ev.target.closest(".option");
        if (!btn) return;
        const siblings = Array.from(group.querySelectorAll(".option"));
        siblings.forEach((s) => s.classList.remove("selected"));
        btn.classList.add("selected");
        const count = parseInt(popup.querySelector("#countValue")?.textContent || "1");
        updatePopupPriceDisplay(popup, count);
      });
    });
  });

  // --- Setup toggle for extras (can click to deselect) ---
  function setupExtrasToggle(popup) {
    const extraGroup = popup.querySelector(".extra-flavor");
    if (!extraGroup) return;
    extraGroup.querySelectorAll(".option").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const currentlySelected = btn.classList.contains("selected");
        extraGroup.querySelectorAll(".option").forEach((o) => o.classList.remove("selected"));
        if (!currentlySelected) btn.classList.add("selected");
        const cnt = parseInt(popup.querySelector("#countValue")?.textContent || "1");
        updatePopupPriceDisplay(popup, cnt);
      });
    });
  }

  // --- Add to cart event inside each popup ---
  popups.forEach((popup, idx) => {
    popup.querySelector(".add-cart")?.addEventListener("click", (ev) => {
      ev.preventDefault();

      const menuItem = menuItems[idx];
      const title = popup.querySelector("h1")?.textContent?.trim() || "Item";
      const quantity = parseInt(popup.querySelector("#countValue")?.textContent || "1");
      const size = popup.querySelector(".options .selected")?.textContent?.trim() || "";
      const sauce = popup.querySelector(".flavors .selected")?.textContent?.trim() || "";
      const mix = popup.querySelector(".mix .selected")?.textContent?.trim() || "";

      const extraBtn = popup.querySelector(".extra-flavor .selected");
      let extraName = "", extraPrice = 0;
      if (extraBtn) {
        extraName = extraBtn.textContent.split("₱")[0].trim();
        const parsed = extraBtn.textContent.match(/₱\s*([0-9]+)/);
        extraPrice = parsed ? parseInt(parsed[1], 10) : 0;
      }

      const menuPriceText = menuItem.querySelector(".menu-description h2")?.textContent || "";
      const basePrice = parseFloat(menuPriceText.replace(/[₱,+\s]/g, "")) || 0;

      const imgSrc = menuItem.querySelector("img")?.getAttribute("src") || "";
      const priceEach = basePrice + extraPrice;
      const totalPrice = priceEach * quantity;

      const cartItem = {
        title, size, sauce, mix,
        extra: extraName || "None",
        extraCost: extraPrice,
        quantity, priceEach, totalPrice, image: imgSrc
      };

      cart.push(cartItem);
      renderCart();
      closePopup(popup);
      cartOverlay?.classList.add("active");
    });
  });

  // --- Cart overlay logic ---
  cartBtn?.addEventListener("click", () => {
    cartOverlay.classList.add("active");
  });

  closeCartBtn?.addEventListener("click", () => {
    cartOverlay.classList.remove("active");
  });

  cartOverlay?.addEventListener("click", (e) => {
    if (e.target === cartOverlay) {
      cartOverlay.classList.remove("active");
    }
  });

  // --- Render Cart Items ---
  function renderCart() {
    if (!cartItemsDiv) return;
    cartItemsDiv.innerHTML = "";
    let grandTotal = 0;

    cart.forEach((it, i) => {
      grandTotal += it.totalPrice;
      const html = `
        <div class="cart-item" data-index="${i}">
          <button class="remove-btn">&times;</button>
          <div class="cart-item-left">
            ${it.image ? `<img src="${escapeHtml(it.image)}" alt="${escapeHtml(it.title)}" class="cart-item-img">` : ""}
            <div class="cart-item-details">
              <strong>${escapeHtml(it.title)}</strong><br>
              <small>${it.size ? escapeHtml(it.size) + " | " : ""}${it.sauce ? escapeHtml(it.sauce) + " | " : ""}${it.mix ? escapeHtml(it.mix) : ""}</small><br>
              <small>Extra: ${escapeHtml(it.extra)} ${it.extraCost ? `(+₱${it.extraCost})` : ""}</small>
            </div>
          </div>
          <div class="cart-item-right">
            <span>${it.quantity}x ₱${it.totalPrice}</span>
          </div>
        </div>
      `;
      cartItemsDiv.insertAdjacentHTML("beforeend", html);
    });

    const totalSpan = document.getElementById("cart-total");
    if (totalSpan) {
      totalSpan.textContent = `Total: ₱${grandTotal}`;
    }

    const removeButtons = cartItemsDiv.querySelectorAll(".remove-btn");
    removeButtons.forEach((btn, index) => {
      btn.addEventListener("click", (event) => {
        event.stopPropagation();
        cart.splice(index, 1);
        renderCart();
      });
    });
  }

  // --- Utils ---
  function closePopup(popup) {
    popup.classList.remove("open");
    popup.style.display = "none";
  }

  function resetPopup(popup) {
    popup.querySelectorAll(".option.selected").forEach((s) => s.classList.remove("selected"));
    const countSpan = popup.querySelector("#countValue");
    if (countSpan) countSpan.textContent = "1";
  }

  function updatePopupPriceDisplay(popup, countArg) {
    const count = typeof countArg === "number" ? countArg : parseInt(popup.querySelector("#countValue")?.textContent || "1");
    const index = popups.indexOf(popup);
    const relatedMenu = menuItems[index];
    const txt = relatedMenu?.querySelector(".menu-description h2")?.textContent || "";
    const basePrice = parseFloat(txt.replace(/[₱,+\s]/g, "")) || 0;

    const extraBtn = popup.querySelector(".extra-flavor .selected");
    const extraPrice = extraBtn ? parseInt(extraBtn.textContent.match(/₱\s*([0-9]+)/)?.[1] || 0) : 0;

    const final = (basePrice + extraPrice) * count;
    popup.querySelector(".popup-total-price").textContent = `₱${final}`;
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"'`=\/]/g, (s) => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;', '/': '&#47;', '`': '&#96;', '=': '&#61;'
    }[s]));
  }
});
