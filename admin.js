document.addEventListener("DOMContentLoaded", function () {

    const navLinks = document.querySelectorAll(".header_button");
    const contentSections = document.querySelectorAll(".page-content");

    navLinks.forEach(link => {
        link.addEventListener("click", function (e) {

            const targetContentId = this.getAttribute("href");

            if (targetContentId && targetContentId.startsWith("#")) {

                e.preventDefault();


                navLinks.forEach(nav => nav.classList.remove("active"));
                this.classList.add("active");

                contentSections.forEach(section => section.classList.remove("active"));
                const newActiveContent = document.querySelector(targetContentId);

                if (newActiveContent) {
                    newActiveContent.classList.add("active");
                }

            }


        });
    });
});



document.addEventListener('DOMContentLoaded', function () {

    // Define the color mapping
    const statusColors = {
        'Pending': 'btn-secondary',
        'Accepted': 'btn-success',
        'Denied': 'btn-danger',
        'Canceled': 'btn-dark',
        // Changed to 'btn-primary' to distinguish Delivered from Accepted/Success
        'Delivered': 'btn-primary'
    };

    // --- NEW FUNCTION: Applies saved status and color to a button ---
    function applySavedStatus(button, orderId, savedStatus) {
        // 1. Update Button Text
        button.textContent = savedStatus;

        // 2. Update Button Color

        // a. Remove all old color classes
        Object.values(statusColors).forEach(colorClass => {
            button.classList.remove(colorClass);
        });

        // b. Add the saved color class
        const savedColorClass = statusColors[savedStatus];
        if (savedColorClass) {
            button.classList.add(savedColorClass);
        }
    }
    // --- END NEW FUNCTION ---


    // 1. Select all dropdown containers
    const dropdowns = document.querySelectorAll('.dropdown');

    // 2. Loop through EACH dropdown separately
    dropdowns.forEach(dropdown => {

        // Find the specific button AND list items INSIDE this specific dropdown
        const button = dropdown.querySelector('.dropdown-toggle');
        const items = dropdown.querySelectorAll('.status-item');

        const orderId = button.getAttribute('data-order-id');

        // Only proceed if a valid Order ID is found
        if (orderId) {
            const statusKey = 'order_status_' + orderId;
            const savedStatus = localStorage.getItem(statusKey);

            // --- NEW LOGIC: LOAD SAVED STATUS ON PAGE LOAD ---
            if (savedStatus) {
                applySavedStatus(button, orderId, savedStatus);
            }
            // --- END NEW LOGIC ---

            // Add click events only to the items in this specific dropdown
            items.forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    const newStatus = this.textContent.trim();

                    // 1. SAVE the status to localStorage
                    localStorage.setItem(statusKey, newStatus);

                    // 2. Apply the new status and color to the button
                    applySavedStatus(button, orderId, newStatus);

                    // Ensure fallback style exists (can be removed if Bootstrap styling is reliable)
                    if (!button.classList.contains('btn-secondary') && !statusColors[newStatus]) {
                        button.classList.add('btn-secondary');
                    }
                });
            });
        }
    });
});

