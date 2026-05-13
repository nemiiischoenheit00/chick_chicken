<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="icon" type="image/png" href="assets/Logo.png" />

  <!-- Swiper -->
  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">

  <!-- Admin CSS -->
  <link rel="stylesheet" href="admin.css">

  <title>Chick Chicken - Reviews</title>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');

    /* ───────────────────────────────────── */
    /* MAIN CONTENT */
    /* ───────────────────────────────────── */

    .main-content {
      padding: 30px;
      background: #f8f9fb;
      min-height: 100vh;
    }

    h1 {
      font-family: "Oswald", sans-serif;
      font-size: 32px;
      color: #222;
      margin-bottom: 25px;
    }

    /* ───────────────────────────────────── */
    /* REVIEW CARD */
    /* ───────────────────────────────────── */

    #reviewsContainer {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .review-card {
      background: #fff;
      border-radius: 16px;
      padding: 22px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
      transition: 0.25s ease;
      border: 1px solid #f3f3f3;
    }

    .review-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
    }

    .review-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 14px;
      gap: 15px;
    }

    .review-info {
      display: flex;
      flex-direction: column;
    }

    .review-name {
      font-size: 16px;
      font-weight: 700;
      color: #222;
    }

    .review-rating {
      color: #f5b800;
      font-size: 18px;
      margin-top: 4px;
      letter-spacing: 1px;
    }

    .review-date {
      font-size: 12px;
      color: #999;
      margin-top: 4px;
    }

    .review-text {
      font-size: 14px;
      color: #555;
      line-height: 1.7;
      margin-top: 10px;
    }

    /* ───────────────────────────────────── */
    /* DELETE BUTTON */
    /* ───────────────────────────────────── */

    .btn-delete {
      background: #dc3545;
      color: #fff;
      border: none;
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.2s ease;
    }

    .btn-delete:hover {
      background: #bb2d3b;
      transform: scale(1.03);
    }

    /* ───────────────────────────────────── */
    /* EMPTY STATE */
    /* ───────────────────────────────────── */

    .no-reviews {
      text-align: center;
      color: #aaa;
      padding: 50px;
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    /* ───────────────────────────────────── */
    /* ERROR BANNER */
    /* ───────────────────────────────────── */

    .db-error {
      background: #fff3cd;
      border: 1px solid #ffc107;
      border-radius: 10px;
      padding: 14px 18px;
      font-size: 13px;
      color: #856404;
      margin-bottom: 20px;
      display: none;
    }

    /* ───────────────────────────────────── */
    /* SKELETON LOADING */
    /* ───────────────────────────────────── */

    .skeleton {
      display: inline-block;
      width: 100%;
      height: 1.2em;
      background: linear-gradient(
        90deg,
        #e0e0e0 25%,
        #f5f5f5 50%,
        #e0e0e0 75%
      );
      background-size: 200% 100%;
      animation: shimmer 1.2s infinite;
      border-radius: 6px;
      vertical-align: middle;
    }

    @keyframes shimmer {
      0% {
        background-position: 200% 0;
      }

      100% {
        background-position: -200% 0;
      }
    }

    /* ───────────────────────────────────── */
    /* RESPONSIVE */
    /* ───────────────────────────────────── */

    @media (max-width: 768px) {
      .review-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .btn-delete {
        width: 100%;
      }
    }
  </style>
</head>

<body>

  <!-- SIDEBAR -->
  <header>
    <div class="sidebar">

      <div class="logo">
        <h1>
          <a href="admin.php">
            <img src="assets/Logo2.png" alt="ChickChicken"
              style="width: auto; height: 55px" />
          </a>
        </h1>
      </div>

      <div class="navigation--admin">
        <nav>
          <ul>

            <li>
              <a href="admin.php" class="header_button">
                <ion-icon name="grid-outline"></ion-icon>
                <span>Dashboard</span>
              </a>
            </li>

            <li>
              <a href="orders--admin.php" class="header_button">
                <ion-icon name="bag-handle-outline"></ion-icon>
                <span>Orders</span>
              </a>
            </li>

            <li>
              <a href="menu--admin.php" class="header_button">
                <ion-icon name="book-outline"></ion-icon>
                <span>Menus</span>
              </a>
            </li>

            <li>
              <a href="inventory.php" class="header_button">
                <ion-icon name="clipboard-outline"></ion-icon>
                <span>Inventory</span>
              </a>
            </li>

            <li>
              <a href="admin-reviews.php" class="header_button active">
                <ion-icon name="chatbubbles-outline"></ion-icon>
                <span>Reviews</span>
              </a>
            </li>

          </ul>
        </nav>
      </div>

    </div>
  </header>

  <!-- MAIN CONTENT -->
  <main class="main-content">

    <section id="reviews--admin" class="page-content active">

      <h1>Customer Reviews</h1>

      <!-- DB ERROR -->
      <div class="db-error" id="dbError">
        ⚠️ Could not connect to the database.
        Make sure XAMPP is running and
        <code>reviews-api.php</code> is configured.
      </div>

      <!-- REVIEWS -->
      <div id="reviewsContainer">

        <!-- Skeleton Loader -->
        <div class="review-card">
          <p><span class="skeleton"></span></p>
          <p><span class="skeleton"></span></p>
          <p><span class="skeleton"></span></p>
        </div>

      </div>

    </section>

  </main>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

  <!-- Ionicons -->
  <script type="module"
    src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

  <script nomodule
    src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <!-- Swiper -->
  <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

  <!-- Reviews Script -->
  <script>

    async function loadReviews() {

      try {

        const res = await fetch('reviews-api.php?action=list');

        if (!res.ok) {
          throw new Error("HTTP " + res.status);
        }

        const reviews = await res.json();

        const container = document.getElementById('reviewsContainer');

        // NO REVIEWS
        if (!reviews.length) {

          container.innerHTML = `
            <p class="no-reviews">
              No reviews yet.
            </p>
          `;

          return;
        }

        // LOAD REVIEWS
        container.innerHTML = reviews.map(r => `

          <div class="review-card" data-id="${r.id}">

            <div class="review-header">

              <div class="review-info">

                <span class="review-name">
                  ${escapeHtml(r.name)}
                </span>

                <span class="review-rating">
                  ${'★'.repeat(r.rating)}
                  ${'☆'.repeat(5 - r.rating)}
                </span>

                <span class="review-date">
                  ${new Date(r.created_at).toLocaleDateString(
                    'en-PH',
                    {
                      month: 'short',
                      day: 'numeric',
                      year: 'numeric'
                    }
                  )}
                </span>

              </div>

              <button
                class="btn-delete"
                onclick="deleteReview(${r.id})">

                Delete

              </button>

            </div>

            <p class="review-text">
              ${escapeHtml(r.review)}
            </p>

          </div>

        `).join('');

      } catch (e) {

        console.error("Review load error:", e);

        document.getElementById('dbError').style.display = 'block';
      }
    }

    async function deleteReview(id) {

      if (!confirm('Delete this review?')) {
        return;
      }

      await fetch(`reviews-api.php?action=delete&id=${id}`);

      loadReviews();
    }

    function escapeHtml(text) {

      const div = document.createElement('div');

      div.textContent = text;

      return div.innerHTML;
    }

    loadReviews();

  </script>

</body>

</html>