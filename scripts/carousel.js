// Declare Variables
const carousel = document.querySelector("#carousel");
const images = carousel.querySelectorAll("li");
let pos = 0;

// Update heading in Javascript
const h2 = document.querySelector("section:has(#carousel) h2");
h2.innerText = "Made for Gifting";
h2.style.marginBottom = "-1.55rem";

document.querySelectorAll("span.featured").forEach(tag => {
    tag.innerHTML = "Gift Ready";
})

// Create Carousel Navigator
const carouselNavigator = document.createElement("ul");
carouselNavigator.id = "carouselNavigator";

const arrows = ["backwards", "forwards"];
arrows.forEach((id, index) => {
    const li = document.createElement("li");
    const btn = document.createElement("button");
    btn.id = id;
    const div = document.createElement("div"); // Chevron
    btn.appendChild(div);
    li.appendChild(btn);
    carouselNavigator.appendChild(li);
});

// Insert navigator above carousel
carousel.parentNode.insertBefore(carouselNavigator, carousel);

const backwards = document.getElementById("backwards");
const forwards = document.getElementById("forwards");

backwards.setAttribute("aria-label", "Previous");
forwards.setAttribute("aria-label", "Next");
backwards.classList.add("is-disabled");

// Calculations
function getLastIndex() {
    const itemWidth = images[0].getBoundingClientRect().width;
    const visibleItems = Math.round(carousel.clientWidth / itemWidth);
    return Math.max(0, images.length - visibleItems);
}
function updateButtons() {
    backwards.classList.toggle("is-disabled", pos === 0);
    forwards.classList.toggle("is-disabled", pos >= getLastIndex());
}

// Track Scroll Position
carousel.addEventListener("scrollsnapchange", (e) => {
    const snappedElement = e.snapTargetInline;
    pos = Array.from(images).indexOf(snappedElement);
    updateButtons();
});

// Arrow Button Clicks
backwards.addEventListener("click", () => {
    if (pos > 0) {
        images[pos - 1].scrollIntoView({
            behavior: "smooth",
            inline: "start"
        });
    }
});
forwards.addEventListener("click", () => {
    if (pos < getLastIndex()) {
        images[pos + 1].scrollIntoView({
            behavior: "smooth",
            inline: "start"
        });
    }
});

// Update Disabled Class on Window Resize
window.addEventListener("resize", updateButtons);

// Call Function
updateButtons();