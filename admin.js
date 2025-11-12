document.addEventListener("DOMContentLoaded", function () {
    
    const navLinks = document.querySelectorAll(".header_button");
    
    const contentSections = document.querySelectorAll(".page-content");
    
    navLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            navLinks.forEach(nav => nav.classList.remove("active"));
            this.classList.add("active");
            contentSections.forEach(section => section.classList.remove("active"));
            const targetContentId = this.getAttribute("href"); 
            const newActiveContent = document.querySelector(targetContentId);
            if (newActiveContent) {
                newActiveContent.classList.add("active");
            }
        });
    });
});