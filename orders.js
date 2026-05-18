document.addEventListener("DOMContentLoaded", () => {

  /* ─── PRICE CALCULATOR ─────────────────────────────────────────── */
  function updatePopupPrice(popup) {
    const basePrice = parseFloat(popup.dataset.basePrice) || 0;
    const optionBtn = popup.querySelector('[data-group="option"] .selected');
    const isDouble  = optionBtn ? optionBtn.textContent.trim().toLowerCase().startsWith("double") : false;
    const hasExtra  = !!popup.querySelector('[data-group="extra"] .selected');
    const total     = basePrice + (isDouble ? 100 : 0) + (hasExtra ? 20 : 0);
    const priceEl   = popup.querySelector(".popup-price");
    if (priceEl) priceEl.textContent = `₱${total.toLocaleString("en-PH")}`;
  }

  /* ─── POPUP OPEN ──────────────────────────────────────────────── */
  document.querySelectorAll(".menu-card").forEach(card => {
    card.addEventListener("click", () => {
      const popup = document.getElementById(card.dataset.popup);
      if (!popup) return;
      resetPopup(popup);
      openPopup(popup);
    });
  });

  function resetPopup(popup) {
    popup.querySelectorAll(".opt-btn").forEach(b => b.classList.remove("selected"));
    popup.querySelector(".qty-val").textContent = "1";
    updatePopupPrice(popup);
    // Restore "Add to Cart" mode
    setPopupMode(popup, "add", null);
  }

  function openPopup(popup) {
    popup.classList.add("open");
    document.body.style.overflow = "hidden";
  }

  function closePopup(popup) {
    popup.classList.remove("open");
    document.body.style.overflow = "";
    // Always restore add mode on close
    setPopupMode(popup, "add", null);
  }

  /* ─── SET POPUP MODE: "add" | "edit" ─────────────────────────── */
  // editData: { cart_id, quantity, option, sauce, extra, mix }
  function setPopupMode(popup, mode, editData) {
    const btn = popup.querySelector(".btn-add");
    if (mode === "edit" && editData) {
      btn.textContent       = "Update Cart";
      btn.dataset.mode      = "edit";
      btn.dataset.cartId    = editData.cart_id;
    } else {
      btn.textContent       = "Add to Cart";
      btn.dataset.mode      = "add";
      delete btn.dataset.cartId;
    }
  }

  /* ─── PRE-FILL POPUP WITH EXISTING SELECTIONS ─────────────────── */
  function prefillPopup(popup, editData) {
    popup.querySelectorAll(".opt-btn").forEach(b => b.classList.remove("selected"));

    // Match option (size)
    if (editData.option) {
      popup.querySelectorAll('[data-group="option"] .opt-btn').forEach(b => {
        if (b.textContent.trim().toLowerCase().startsWith(editData.option.toLowerCase().split(" ")[0])) {
          b.classList.add("selected");
        }
      });
    }
    // Match sauce
    if (editData.sauce) {
      popup.querySelectorAll('[data-group="sauce"] .opt-btn').forEach(b => {
        if (b.textContent.trim() === editData.sauce) b.classList.add("selected");
      });
    }
    // Match extra
    if (editData.extra) {
      popup.querySelectorAll('[data-group="extra"] .opt-btn').forEach(b => {
        if (b.textContent.trim() === editData.extra) b.classList.add("selected");
      });
    }
    // Match mix preference
    if (editData.mix) {
      popup.querySelectorAll('[data-group="mix"] .opt-btn').forEach(b => {
        if (b.textContent.trim() === editData.mix) b.classList.add("selected");
      });
    }
    // Set quantity
    popup.querySelector(".qty-val").textContent = editData.quantity || 1;

    updatePopupPrice(popup);
    setPopupMode(popup, "edit", editData);
  }

  /* ─── WIRE UP POPUP EVENTS ────────────────────────────────────── */
  document.querySelectorAll(".popup-backdrop").forEach(backdrop => {
    backdrop.addEventListener("click", e => {
      if (e.target === backdrop) closePopup(backdrop);
    });
    backdrop.querySelector(".popup-close").addEventListener("click", () => closePopup(backdrop));

    backdrop.querySelectorAll("[data-group]").forEach(group => {
      group.querySelectorAll(".opt-btn").forEach(btn => {
        btn.addEventListener("click", () => {
          const wasSelected = btn.classList.contains("selected");
          group.querySelectorAll(".opt-btn").forEach(b => b.classList.remove("selected"));
          if (!wasSelected) btn.classList.add("selected");
          updatePopupPrice(backdrop);
        });
      });
    });

    const qtyVal = backdrop.querySelector(".qty-val");
    backdrop.querySelector(".qty-minus").addEventListener("click", () => {
      const v = parseInt(qtyVal.textContent);
      if (v > 1) qtyVal.textContent = v - 1;
    });
    backdrop.querySelector(".qty-plus").addEventListener("click", () => {
      qtyVal.textContent = parseInt(qtyVal.textContent) + 1;
    });

    backdrop.querySelector(".btn-add").addEventListener("click", () => {
      const btn = backdrop.querySelector(".btn-add");
      if (btn.dataset.mode === "edit") {
        updateCartItem(backdrop, parseInt(btn.dataset.cartId));
      } else {
        addToCart(backdrop);
      }
    });
  });

  /* ─── CART DRAWER ─────────────────────────────────────────────── */
  const fabCart      = document.getElementById("fab-cart");
  const cartDrawer   = document.getElementById("cart-drawer");
  const cartBackdrop = document.getElementById("cart-backdrop");
  const cartClose    = document.getElementById("cart-close");

  fabCart.addEventListener("click", () => { openCart(); loadCart(); });
  cartClose.addEventListener("click", closeCart);
  cartBackdrop.addEventListener("click", closeCart);

  function openCart()  { cartDrawer.classList.add("open"); cartBackdrop.classList.add("open"); document.body.style.overflow="hidden"; }
  function closeCart() { cartDrawer.classList.remove("open"); cartBackdrop.classList.remove("open"); document.body.style.overflow=""; }

  /* ─── TOAST ───────────────────────────────────────────────────── */
  function toast(msg, type="success") {
    const wrap = document.getElementById("toast-wrap");
    const el   = document.createElement("div");
    el.className   = `toast ${type}`;
    el.textContent = msg;
    wrap.appendChild(el);
    setTimeout(() => el.remove(), 3000);
  }

  /* ─── ADD TO CART ─────────────────────────────────────────────── */
  async function addToCart(popup) {
    const dbId  = parseInt(popup.dataset.dbId);
    const qty   = parseInt(popup.querySelector(".qty-val").textContent);
    const opts  = getPopupSelections(popup);

    try {
      const res  = await fetch("add_to_cart.php", {
        method:  "POST",
        headers: {"Content-Type": "application/json"},
        body:    JSON.stringify({ product_id: dbId, quantity: qty, ...opts })
      });
      const data = await res.json();

      if (data.error === "not_logged_in") {
        toast("Please sign in to add items.", "error");
        setTimeout(() => window.location.href = "login.php", 1400);
        return;
      }
      if (data.success) {
        closePopup(popup);
        toast("Added to cart!");
        updateBadge();
      } else {
        toast(data.error || "Something went wrong.", "error");
      }
    } catch(e) {
      toast("Network error. Try again.", "error");
    }
  }

  /* ─── UPDATE CART ITEM (from popup) ──────────────────────────── */
  async function updateCartItem(popup, cartId) {
    const qty  = parseInt(popup.querySelector(".qty-val").textContent);
    const opts = getPopupSelections(popup);

    try {
      const res  = await fetch("update_cart.php", {
        method:  "POST",
        headers: {"Content-Type": "application/json"},
        body:    JSON.stringify({ cart_id: cartId, quantity: qty, ...opts })
      });
      const data = await res.json();
      if (data.success) {
        closePopup(popup);
        toast("Cart updated!");
        loadCart();
        updateBadge();
      } else {
        toast(data.error || "Update failed.", "error");
      }
    } catch(e) {
      toast("Network error. Try again.", "error");
    }
  }

  /* ─── QUICK QTY UPDATE (inline in cart) ──────────────────────── */
  async function quickUpdateQty(cartId, newQty, currentData) {
    try {
      await fetch("update_cart.php", {
        method:  "POST",
        headers: {"Content-Type": "application/json"},
        body:    JSON.stringify({ cart_id: cartId, quantity: newQty, ...currentData })
      });
      loadCart();
      updateBadge();
    } catch(e) {
      toast("Network error.", "error");
    }
  }

  /* ─── HELPER: read popup selections ──────────────────────────── */
  function getPopupSelections(popup) {
    const optionBtn = popup.querySelector('[data-group="option"] .selected');
    const sauceBtn  = popup.querySelector('[data-group="sauce"] .selected');
    const extraBtn  = popup.querySelector('[data-group="extra"] .selected');
    const mixBtn    = popup.querySelector('[data-group="mix"] .selected');
    return {
      option: optionBtn ? optionBtn.textContent.trim().replace(/\s+/g, ' ') : "",
      sauce:  sauceBtn  ? sauceBtn.textContent.trim()  : "",
      extra:  extraBtn  ? extraBtn.textContent.trim()  : "",
      mix:    mixBtn    ? mixBtn.textContent.trim()    : "",
    };
  }

  /* ─── LOAD CART ITEMS ─────────────────────────────────────────── */
  async function loadCart() {
    const wrap    = document.getElementById("cart-items-wrap");
    const totalEl = document.getElementById("cart-total");

    wrap.innerHTML = '<div style="padding:40px;text-align:center;color:#bbb;font-family:var(--oswald);">Loading…</div>';

    try {
      const res   = await fetch("get_cart.php");
      const items = await res.json();

      if (!items.length) {
        wrap.innerHTML      = `<div class="cart-empty"><div class="empty-icon">🛒</div><p>Your cart is empty</p></div>`;
        totalEl.textContent = "₱0";
        updateBadge(0);
        return;
      }

      let total = 0;
      wrap.innerHTML = "";

      items.forEach(item => {
        const lineTotal = parseFloat(item.price) * parseInt(item.quantity);
        total += lineTotal;

        const meta = [item.option_selected, item.sauce, item.extra_flavor, item.mix_preference]
                       .filter(Boolean).join(" · ");

        const currentData = {
          option: item.option_selected || "",
          sauce:  item.sauce           || "",
          extra:  item.extra_flavor    || "",
          mix:    item.mix_preference  || "",
        };

        const div     = document.createElement("div");
        div.className = "cart-item";
        div.innerHTML = `
          <div class="cart-item-img">
            <img src="${item.image || 'menuassets/Chick_Rice.png'}" alt="${item.name}" loading="lazy"/>
          </div>
          <div class="cart-item-info">
            <div class="cart-item-name">${item.name}</div>
            ${meta ? `<div class="cart-item-meta">${meta}</div>` : ""}
            <div class="cart-item-qty-row">
              <button class="cart-qty-btn cart-qty-minus" data-id="${item.id}">−</button>
              <span class="cart-qty-display">${item.quantity}</span>
              <button class="cart-qty-btn cart-qty-plus" data-id="${item.id}">+</button>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
            <div class="cart-item-price">₱${lineTotal.toFixed(0)}</div>
            <div style="display:flex;gap:6px;">
              <button class="cart-item-edit" data-id="${item.id}" data-product-id="${item.product_id}" title="Edit item">✏️</button>
              <button class="cart-item-del"  data-id="${item.id}" title="Remove">✕</button>
            </div>
          </div>
        `;

        // Inline qty controls
        const qty = parseInt(item.quantity);
        div.querySelector(".cart-qty-minus").addEventListener("click", () => {
          if (qty > 1) quickUpdateQty(item.id, qty - 1, currentData);
          else removeItem(item.id);
        });
        div.querySelector(".cart-qty-plus").addEventListener("click", () => {
          quickUpdateQty(item.id, qty + 1, currentData);
        });

        // Edit button — open popup pre-filled
        div.querySelector(".cart-item-edit").addEventListener("click", () => {
          // Find the popup that matches this product
          const popup = document.querySelector(`.popup-backdrop[data-db-id="${item.product_id}"]`);
          if (!popup) { toast("Product not found.", "error"); return; }

          closeCart();
          prefillPopup(popup, {
            cart_id:  item.id,
            quantity: item.quantity,
            option:   item.option_selected || "",
            sauce:    item.sauce           || "",
            extra:    item.extra_flavor    || "",
            mix:      item.mix_preference  || "",
          });
          openPopup(popup);
        });

        // Remove button
        div.querySelector(".cart-item-del").addEventListener("click", () => removeItem(item.id));

        wrap.appendChild(div);
      });

      totalEl.textContent = `₱${total.toFixed(0)}`;
      updateBadge(items.reduce((s, i) => s + parseInt(i.quantity), 0));
    } catch(e) {
      wrap.innerHTML = `<div style="padding:30px;text-align:center;color:#bbb;">Could not load cart.</div>`;
    }
  }

  /* ─── REMOVE ITEM ─────────────────────────────────────────────── */
  async function removeItem(cartId) {
    try {
      await fetch("remove_from_cart.php", {
        method:  "POST",
        headers: {"Content-Type": "application/json"},
        body:    JSON.stringify({ cart_id: cartId })
      });
      loadCart();
    } catch(e) { toast("Error removing item.", "error"); }
  }

  /* ─── BADGE COUNT ─────────────────────────────────────────────── */
  async function updateBadge(count) {
    const badge = document.getElementById("fab-badge");
    if (count === undefined) {
      try {
        const res   = await fetch("get_cart.php");
        const items = await res.json();
        count = items.reduce((s, i) => s + parseInt(i.quantity), 0);
      } catch(e) { return; }
    }
    badge.textContent = count;
    badge.classList.toggle("show", count > 0);
  }

  /* ─── CHECKOUT ────────────────────────────────────────────────── */
  document.getElementById("btn-checkout").addEventListener("click", () => {
    window.location.href = "checkout.php";
  });

  /* ─── SIDEBAR CLICK → SCROLL ──────────────────────────────────── */
  const mainContent = document.querySelector(".main-content");
  const sections    = document.querySelectorAll(".menu-section");
  const sideLinks   = document.querySelectorAll(".sidebar a");

  sideLinks.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();
      const section = document.getElementById(link.dataset.target);
      if (!section) return;
      mainContent.scrollTo({ top: section.offsetTop - 32, behavior: "smooth" });
      sideLinks.forEach(a => a.classList.remove("active"));
      link.classList.add("active");
    });
  });

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        sideLinks.forEach(a => a.classList.remove("active"));
        const link = document.querySelector(`.sidebar a[data-target="${entry.target.id}"]`);
        if (link) link.classList.add("active");
      }
    });
  }, { root: mainContent, rootMargin: "-30% 0px -60% 0px" });
  sections.forEach(s => observer.observe(s));

  /* ─── MOBILE CATEGORY BAR ─────────────────────────────────────── */
  (function () {
    const mobileBar  = document.getElementById('mobile-cat-bar');
    if (!mobileBar) return;
    const mobileLinks = mobileBar.querySelectorAll('a');

    mobileLinks.forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const section = document.getElementById(link.dataset.target);
        if (!section) return;
        const isMobile = window.innerWidth <= 900;
        if (isMobile) {
          const navbarH = document.querySelector('header')?.offsetHeight || 65;
          const barH    = mobileBar.offsetHeight || 48;
          const offset  = section.getBoundingClientRect().top + window.scrollY - navbarH - barH - 12;
          window.scrollTo({ top: offset, behavior: 'smooth' });
        } else {
          const mc = document.querySelector('.main-content');
          mc?.scrollTo({ top: section.offsetTop - 32, behavior: 'smooth' });
        }
        mobileLinks.forEach(a => a.classList.remove('active'));
        link.classList.add('active');
      });
    });

    function onScroll() {
      if (window.innerWidth > 900) return;
      const navbarH = document.querySelector('header')?.offsetHeight || 65;
      const barH    = mobileBar.offsetHeight || 48;
      const scrollY = window.scrollY + navbarH + barH + 20;
      let current = null;
      sections.forEach(sec => { if (sec.offsetTop <= scrollY) current = sec.id; });
      if (current) {
        mobileLinks.forEach(a => a.classList.toggle('active', a.dataset.target === current));
        const activeLink = mobileBar.querySelector('a.active');
        if (activeLink) activeLink.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
      }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
  })();

  /* ─── LOCK LAYOUT (desktop only) ─────────────────────────────── */
  function lockLayout() {
    const header = document.querySelector('header');
    const hero   = document.querySelector('.page-hero');
    const layout = document.querySelector('.shop-layout');
    if (!layout) return;
    if (window.innerWidth <= 900) {
      layout.style.height = '';
      layout.style.minHeight = '';
      layout.style.width = '100%';
      return;
    }
    const headerH = header ? header.offsetHeight : 65;
    const heroH   = hero   ? hero.offsetHeight   : 0;
    const h       = window.innerHeight - headerH - heroH;
    layout.style.height    = h + 'px';
    layout.style.minHeight = h + 'px';
  }
  lockLayout();
  window.addEventListener('resize', lockLayout);

  /* ─── INIT ────────────────────────────────────────────────────── */
  updateBadge();
});