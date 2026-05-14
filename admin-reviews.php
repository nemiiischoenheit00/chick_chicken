<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="assets/Logo.png" />
    <link rel="stylesheet" href="admin.css">
    <link href="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css)" rel="stylesheet">
    <title>Chick Chicken - Reviews</title>
    <style>
        .review-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .review-name {
            font-weight: 700;
            font-size: 16px;
        }
        .review-rating {
            color: #F5A623;
            font-size: 18px;
        }
        .review-date {
            font-size: 12px;
            color: #999;
        }
        .review-text {
            font-size: 14px;
            color: #555;
            line-height: 1.5;
        }
        .btn-delete {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .no-reviews {
            text-align: center;
            color: #aaa;
            padding: 40px;
        }
    </style>
</head>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<body>
    <header>
        <div class="sidebar">
            <div class="logo">
                <h1>
                    <a href="admin.php"><img src="assets/Logo2.png" alt="ChickChicken" style="width: auto; height: 55px" /></a>
                </h1>
            </div>
            <div class="navigation--admin">
                <nav>
                    <ul>
                        <li><a href="dashboard--admin.php" class="header_button"><ion-icon name="grid-outline"></ion-icon><span>Dashboard</span></a></li>
                        <li><a href="orders--admin.php" class="header_button"><ion-icon name="bag-handle-outline"></ion-icon><span>Orders</span></a></li>
                        <li><a href="menu--admin.php" class="header_button"><ion-icon name="book-outline"></ion-icon><span>Menus</span></a></li>
                        <li><a href="inventory.php" class="header_button"><ion-icon name="clipboard-outline"></ion-icon><span>Inventory</span></a></li>
                        <li><a href="reviews-admin.php" class="header_button active"><ion-icon name="chatbubbles-outline"></ion-icon><span>Reviews</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
        <section class="page-content active">
            <h1 style="margin-bottom: 25px;">Customer Reviews</h1>
            <div id="reviewsContainer">
                <p class="no-reviews">Loading reviews...</p>
            </div>
        </section>
    </main>

    <script type="module" src="[unpkg.com](https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js)"></script>
    <script>
    async function loadReviews() {
        try {
            const res = await fetch('reviews-api.php?action=list');
            const reviews = await res.json();
            const container = document.getElementById('reviewsContainer');

            if (!reviews.length) {
                container.innerHTML = '<p class="no-reviews">No reviews yet.</p>';
                return;
            }

            container.innerHTML = reviews.map(r => `
                <div class="review-card" data-id="${r.id}">
                    <div class="review-header">
                        <div>
                            <span class="review-name">${escapeHtml(r.name)}</span>
                            <span class="review-rating">${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}</span>
                        </div>
                        <div>
                            <span class="review-date">${new Date(r.created_at).toLocaleString()}</span>
                            <button class="btn-delete" onclick="deleteReview(${r.id})">Delete</button>
                        </div>
                    </div>
                    <p class="review-text">${escapeHtml(r.review)}</p>
                </div>
            `).join('');
        } catch (e) {
            document.getElementById('reviewsContainer').innerHTML = '<p class="no-reviews">Failed to load reviews.</p>';
        }
    }

    async function deleteReview(id) {
        if (!confirm('Delete this review?')) return;
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
