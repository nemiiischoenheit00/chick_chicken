document.addEventListener("DOMContentLoaded", () => {

  // --- Disclaimer close ---
  const closeBtn = document.getElementById("closeBtn");
  if (closeBtn) {
    closeBtn.addEventListener("click", () => {
      document.getElementById("disclaimer").style.display = "none";
    });
  }

  // --- Branch selector ---
  const branchSelect = document.getElementById("branchSelect");
  const branchInfoCard = document.getElementById("branchInfoCard");
  const branchAddress = document.getElementById("branchAddress");
  const branchPhone = document.getElementById("branchPhone");
  const branchHours = document.getElementById("branchHours");

  if (branchSelect) {
    branchSelect.addEventListener("change", () => {
      const selected = branchSelect.options[branchSelect.selectedIndex];
      const address = selected.dataset.address;
      const phone = selected.dataset.phone;
      const hours = selected.dataset.hours;

      if (address) {
        branchAddress.textContent = "📍 " + address;
        branchPhone.textContent = "📞 " + phone;
        branchHours.textContent = "🕐 " + hours;
        branchInfoCard.classList.add("visible");
      } else {
        branchInfoCard.classList.remove("visible");
      }
    });
  }

  // --- Phone number auto-format (Philippine: +63 XXX XXX XXXX) ---
  const phoneInput = document.getElementById("phoneInput");

  if (phoneInput) {
    phoneInput.addEventListener("input", (e) => {
      // Strip everything except digits and leading +
      let raw = phoneInput.value.replace(/[^\d]/g, "");

      // If user starts with 0 (local), convert to +63
      if (raw.startsWith("0")) {
        raw = "63" + raw.slice(1);
      }

      // Remove country code prefix if present so we format just the digits
      if (raw.startsWith("63")) {
        raw = raw.slice(2);
      }

      // Cap to 10 digits (after country code)
      raw = raw.substring(0, 10);

      // Format: XXX XXX XXXX
      let formatted = "";
      if (raw.length > 0) formatted = raw.substring(0, 3);
      if (raw.length > 3) formatted += " " + raw.substring(3, 6);
      if (raw.length > 6) formatted += " " + raw.substring(6, 10);

      phoneInput.value = raw.length > 0 ? "+63 " + formatted : "";
    });

    // Allow backspace to work cleanly — if user deletes into "+63 ", clear it
    phoneInput.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && phoneInput.value === "+63 ") {
        phoneInput.value = "";
      }
    });
  }

  // --- Card number formatting ---
  const cardInput = document.getElementById("cardnumber");
  const cardField = document.getElementById("card-field");

  if (cardInput) {
    cardInput.addEventListener("input", () => {
      let val = cardInput.value.replace(/\D/g, "").substring(0, 16);
      cardInput.value = val.replace(/(.{4})/g, "$1 ").trim();
    });
  }

  // --- Show/hide card field ---
  const paymentRadios = document.querySelectorAll('input[name="payment"]');

  function toggleCard() {
    if (!cardField) return;
    const selected = document.querySelector('input[name="payment"]:checked');
    cardField.style.display = (selected?.value === "online") ? "block" : "none";
  }

  paymentRadios.forEach(r => r.addEventListener("change", toggleCard));
  toggleCard();

  // --- Form submit ---
  const form = document.querySelector(".checkout-form");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Branch validation
    const branch = branchSelect?.value;
    if (!branch) {
      alert("Please select a branch.");
      branchSelect?.focus();
      return;
    }

    const payment = document.querySelector('input[name="payment"]:checked')?.value;
    if (!payment) {
      alert("Please select a payment method.");
      return;
    }

    if (payment === "online" && cardInput?.value.replace(/\s/g, "").length < 16) {
      alert("Please enter a valid 16-digit card number.");
      return;
    }

    // Phone: strip formatting before sending, keep +63XXXXXXXXXX
    const rawPhone = phoneInput?.value.replace(/\s/g, "") ?? "";

    // Replace the payload block and fetch call in your submit handler:

    const formData = new FormData();
    formData.append("name", form.querySelector('input[name="name"]')?.value.trim());
    formData.append("phone", rawPhone);
    formData.append("email", form.querySelector('input[name="email"]')?.value.trim());
    formData.append("address", form.querySelector('input[name="address"]')?.value.trim());
    formData.append("payment_method", payment);
    formData.append("branch", branch);
    formData.append("discount_type", form.querySelector('input[name="discount_type"]')?.value ?? '');
    formData.append("discount_rate", form.querySelector('input[name="discount_rate"]')?.value ?? 0);

    if (payment === "gcash") {
      const proof = document.getElementById("gcash-proof");
      if (proof.files.length) formData.append("gcash_proof", proof.files[0]);
    }

    try {
      const res = await fetch("place-order.php", {
        method: "POST",
        body: formData   // ← No Content-Type header; browser sets it with boundary automatically
      });
      const data = await res.json();

      if (data.success) {
        const customerName = encodeURIComponent(form.querySelector('input[name="name"]')?.value.trim() || '');
        window.location.href = `feedback.php?order_id=${data.order_id}&name=${customerName}`;
      } else if (data.error === "not_logged_in") {
        alert("Please sign in to place an order.");
        window.location.href = "login.php";
      } else {
        alert("Something went wrong: " + data.error);
      }
    } catch (err) {
      alert("Network error. Please try again.");
      console.error(err);
    }
  });

  loadOrderSummary();
});


async function loadOrderSummary() {
  try {
    const res = await fetch("get_cart.php");
    const items = await res.json();

    // Add this guard:
    if (!Array.isArray(items)) {
      console.error("Cart response is not an array:", items);
      return;
    }

    const container = document.getElementById("summary-items");
    const heading = document.querySelector(".order-summary h3");
    let subtotal = 0;

    if (!items.length) {
      container.innerHTML = "<p style='color:#888; font-size:14px;'>Your cart is empty.</p>";
      return;
    }

    const totalQty = items.reduce((sum, i) => sum + parseInt(i.quantity), 0);
    heading.innerHTML += `<span class="summary-item-count">${totalQty} item${totalQty !== 1 ? 's' : ''}</span>`;

    items.forEach(item => {
      const lineTotal = parseFloat(item.price) * parseInt(item.quantity);
      subtotal += lineTotal;

      const meta = [item.option_selected, item.sauce, item.extra_flavor, item.mix_preference]
        .filter(Boolean).join(" · ");

      container.innerHTML += `
        <div class="summary-item">
          <span class="summary-qty">×${item.quantity}</span>
          <div class="summary-details">
            <p class="summary-name">${item.name}</p>
            ${meta ? `<p class="summary-meta">${meta}</p>` : ""}
          </div>
          <span class="summary-price">₱${lineTotal.toFixed(2)}</span>
        </div>`;
    });

    document.getElementById("summary-subtotal").textContent = `₱${subtotal.toFixed(2)}`;
    if (typeof window.applyDiscount === 'function') {
      window.applyDiscount();
    } else {
      document.getElementById("summary-total").textContent = `₱${subtotal.toFixed(2)}`;
    }
  } catch (err) {
    console.error("Failed to load cart:", err);
  }
}