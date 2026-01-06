/* | NAVIGATION */
const nav = document.querySelector("nav");
const search = document.querySelector("form");
const searchIcon = navbar.querySelector("form svg");

/* Burger Menu */
burger.addEventListener("click", function() {
    this.classList.toggle("is-active");
    nav.classList.toggle("is-expanded");
    
    if (nav.classList.value == "is-expanded" && search.classList.value == "is-expanded") {
        nav.style.top = "calc(100% + 4.75rem)";
    } else {
        nav.style.top = "100%";
    }
})

/* Active Nav Link */
const page = window.location.href.split("/").pop().split("?").shift();
const navItems = document.querySelectorAll("nav a");

navItems.forEach(link => {
    if (link.getAttribute("href") === page) {
        link.classList.add("is-selected");
    } else if (page === "") {
        document.querySelector("nav a").classList.add("is-selected");
    }
})

/* Search */
/* **Commented out for later. Need to implement Search Functionality still.**
const searchBTN = document.querySelector("menu button");
searchBTN.addEventListener("click", function() {
    search.classList.toggle("is-expanded");
    searchIcon.classList.toggle("is-visible");

    if (nav.classList.value == "is-expanded" && search.classList.value == "is-expanded") {
        nav.style.top = "calc(100% + 4.75rem)";
    } else {
        nav.style.top = "100%";
    }
})
*/