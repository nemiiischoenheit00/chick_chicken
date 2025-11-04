// --- Import Firebase Order Function ---
import { addOrder } from "./firebase_orders.js";

/* ============================================================
   POPUP LOGIC (For each menu item)
============================================================ */
document.addEventListener("DOMContentLoaded", () => {
  const menuItems = document.querySelectorAll(".menu-container");
  const popups = document.querySelectorAll(".popup-overlay");

  menuItems.forEach((menuItem, index) => {
    const popup = popups[index];
    if (!popup) return;

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

    // Close when clicking outside popup content
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

    // Option button selection (sauce, size, etc.)
    popup.querySelectorAll(".option").forEach((btn) => {
      btn.addEventListener("click", () => {
        const siblings = btn.parentElement.querySelectorAll(".option");
        siblings.forEach((b) => b.classList.remove("selected"));
        btn.classList.add("selected");
      });
    });
  });
});

/* ============================================================
   CART LOGIC + FIREBASE INTEGRATION
============================================================ */

// --- CART ELEMENTS ---
const cartBtn = document.querySelector(".cart-btn");
const cartPopup = document.getElementById("cartOverlay");
const closeCartBtn = document.querySelector(".close-cart");
const cartCountEl = document.querySelector(".cart-count");
const cartItemsEl = document.getElementById("cartItems");

// Cart storage (local only)
let localCart = [];
let cartCount = 0;

// --- FUNCTIONS ---

// Add to local cart
function addToLocalCart(itemData) {
  localCart.push(itemData);
  cartCount++;
  if (cartCountEl) cartCountEl.textContent = cartCount;
  updateCartPopup();
}

// Update cart popup items
function updateCartPopup() {
  cartItemsEl.innerHTML = "";

  localCart.forEach((item, index) => {
    const li = document.createElement("li");

    // Extract numeric value from price like '₱169+'
    const priceNumber = parseInt(item.total.replace(/[^\d]/g, ""));
    const totalCost = priceNumber * item.quantity;

    li.innerHTML = `
      <strong>${item.item}</strong>
      <button class="remove-item" data-index="${index}">✖</button><br>

      Option: ${item.option}<br>
      Quantity: ${item.quantity}x<br>
      Total: ₱${totalCost}<br>
      <small>${item.createdAt}</small>
      <hr>
    `;

    cartItemsEl.appendChild(li);
  });

  // Attach remove functionality
  document.querySelectorAll(".remove-item").forEach(btn => {
    btn.addEventListener("click", (e) => {
      const index = e.target.getAttribute("data-index");
      removeFromLocalCart(index);
    });
  });
}

function removeFromLocalCart(index) {
  localCart.splice(index, 1); // Remove the specific item
  cartCount--; // Decrease cart count
  cartCountEl.textContent = cartCount; // Update cart display
  updateCartPopup(); // Refresh cart view
}



// Open and close cart popup
cartBtn.addEventListener("click", () => {
  cartPopup.style.display = "flex";
});

closeCartBtn.addEventListener("click", () => {
  cartPopup.style.display = "none";
});

/* ============================================================
   ADD TO CART BUTTON (Popup)
============================================================ */
document.addEventListener("DOMContentLoaded", () => {
  const popups = document.querySelectorAll(".popup-overlay");

  popups.forEach((popup) => {
    const addToCartBtn = popup.querySelector(".add-cart");

    addToCartBtn?.addEventListener("click", () => {
      // Basic details
      const itemName = popup.querySelector("h1")?.textContent.trim() || "Unknown Item";
      const countValue = popup.querySelector("#countValue")?.textContent || 1;
      const selectedOption = popup.querySelector(".options .option.selected")?.textContent || "Default";

      // Optional flavor/extra/mix selections
      const selectedSauce = popup.querySelector(".flavors .option.selected")?.textContent || "None";
      const selectedExtra = popup.querySelector(".extra-flavor .option.selected")?.textContent || "None";
      const selectedMix = popup.querySelector(".mix .option.selected")?.textContent || "None";

      // Match name with menu container to find price
      const menuItem = [...document.querySelectorAll(".menu-container")]
        .find((m) => m.querySelector("h3")?.textContent.trim() === itemName);
      const totalPrice = menuItem?.querySelector("h2")?.textContent || "₱0";

      const orderData = {
        item: itemName,
        quantity: Number(countValue),
        option: selectedOption,
        sauce: selectedSauce,
        extra: selectedExtra,
        mix: selectedMix,
        total: totalPrice,
        createdAt: new Date().toLocaleString(),
      };

      // Save locally + send to Firebase
      addToLocalCart(orderData);
      addOrder(orderData);
      console.log(`✅ Sent ${itemName} to Firebase`);

      popup.style.display = "none";
    });
  });
});

/* ============================================================
   CHECKOUT ALL BUTTON
============================================================ */
document.querySelector(".checkout-all")?.addEventListener("click", () => {
  if (localCart.length === 0) return alert("🛒 Your cart is empty!");

  localCart.forEach((item) => addOrder(item));
  localCart = [];
  cartCount = 0;
  cartCountEl.textContent = 0;
  updateCartPopup();
  alert("✅ Checkout successful! All items sent to Firebase.");
});
