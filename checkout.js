document.addEventListener("DOMContentLoaded", () => {

  // --- Disclaimer close ---
  const closeBtn = document.getElementById("closeBtn");
  if (closeBtn) {
    closeBtn.addEventListener("click", () => {
      document.getElementById("disclaimer").style.display = "none";
    });
  }

  // --- Card number formatting ---
  const cardInput = document.getElementById("cardnumber");
  const cardField = document.getElementById("card-field"); // ← targets the div directly, no closest()

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
  toggleCard(); // hides card field on load until Online Payment is selected

  // --- Form submit ---
  const form = document.querySelector(".checkout-form");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const payment = document.querySelector('input[name="payment"]:checked')?.value;
    if (!payment) {
      alert("Please select a payment method.");
      return;
    }

    if (payment === "online" && cardInput?.value.replace(/\s/g, "").length < 16) {
      alert("Please enter a valid 16-digit card number.");
      return;
    }

    const payload = {
      name:           form.querySelector('input[name="name"]')?.value.trim(),
      phone:          form.querySelector('input[name="phone"]')?.value.trim(),
      email:          form.querySelector('input[name="email"]')?.value.trim(),
      address:        form.querySelector('input[name="address"]')?.value.trim(),
      payment_method: payment,
      card_number:    payment === "online" ? cardInput?.value.replace(/\s/g, "") : ""
    };

    try {
      const res  = await fetch("place-order.php", {
        method:  "POST",
        headers: { "Content-Type": "application/json" },
        body:    JSON.stringify(payload)
      });
      const data = await res.json();

      if (data.success) {
        window.location.href = `feedback.html?order_id=${data.order_id}`;
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
});

document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("userToggle");
  const popup = document.getElementById("userPopup");

  if (toggle && popup) {
    toggle.addEventListener("click", (e) => {
      e.stopPropagation();
      popup.style.display = popup.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", () => {
      popup.style.display = "none";
    });
  }
});


async function loadOrderSummary() {
  try {
    const res = await fetch("get-cart.php");
    const items = await res.json();

    const container = document.getElementById("summary-items");
    const heading = document.querySelector(".order-summary h3");
    let subtotal = 0;

    if (!items.length) {
      container.innerHTML = "<p style='color:#888; font-size:14px;'>Your cart is empty.</p>";
      return;
    }

    // badge showing item count
    const totalQty = items.reduce((sum, i) => sum + i.quantity, 0);
    heading.innerHTML += `<span class="summary-item-count">${totalQty} item${totalQty !== 1 ? 's' : ''}</span>`;

    items.forEach(item => {
      const lineTotal = item.price * item.quantity;
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
    document.getElementById("summary-total").textContent    = `₱${subtotal.toFixed(2)}`;

  } catch (err) {
    console.error("Failed to load cart:", err);
  }
}