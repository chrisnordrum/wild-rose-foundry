// Declare Variables
const swatches = document.querySelectorAll(".swatches");

// When Swatches are Clicked
swatches.forEach(swatch => {
    swatch.addEventListener("click", (e) => {
        // Declare Variables
        const focus = e.target;
        let mainImg = focus.parentElement.parentElement.children[0].children[0];
        let variantValue = focus.parentElement.parentElement.children[0].children[2].children[0];
        if (mainImg.classList.contains("featured")) {
            mainImg = focus.parentElement.parentElement.children[0].children[1];
            variantValue = focus.parentElement.parentElement.children[0].children[3].children[0];
        }

        // Change Main Image to Swatch Image
        mainImg.src = focus.dataset.variantImg;
        variantValue.innerHTML = focus.alt;

        // Remove Selected Class from all Swatches
        swatch.querySelectorAll(".swatches img").forEach(img => {
            img.classList.remove("is-selected");
        })
        // Add Selected Class to Focus Swatch
        focus.classList.add("is-selected");
    })
})

// Change Main Image on Mouseover (Desktop Only)
swatches.forEach(swatch => {
    swatch.addEventListener("mouseover", (e) => {
        const focus = e.target;
        let mainImg = focus.parentElement.parentElement.children[0].children[0];
        if (mainImg.classList.contains("featured")) {
            mainImg = focus.parentElement.parentElement.children[0].children[1];
        }

        mainImg.src = focus.dataset.variantImg;
    })
})

// Set Main Image back to original on Mouseout (Desktop Only)
swatches.forEach(swatch => {
    swatch.addEventListener("mouseout", (e) => {
        const focus = e.target;
        let mainImg = focus.parentElement.parentElement.children[0].children[0];
        if (mainImg.classList.contains("featured")) {
            mainImg = focus.parentElement.parentElement.children[0].children[1];
        }

        const currentVariant = swatch.querySelector("img.is-selected");
        mainImg.src = currentVariant.dataset.variantImg;
    })
})