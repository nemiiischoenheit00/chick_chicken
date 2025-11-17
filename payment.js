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