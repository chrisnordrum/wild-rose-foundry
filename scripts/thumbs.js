document.querySelector("span.featured").innerHTML = "Gift Ready";

// When Thumbnails are Clicked
thumbs.addEventListener("click", (e) => {
    const focus = e.target;
    if (focus.nodeName == "IMG") {
        // Change Button Value to Thumbnail ID
        buyBTN.value = focus.dataset.variantId;

        // Change Main Image to Thumbnail Image
        const productPic = productDetails.querySelector("img");
        productPic.src = focus.src;
        productPic.alt = focus.alt;
        productPic.title = focus.title;

        // Change Both Spans to Thumnail Title
        document.querySelector(".variantvalue").innerHTML = " - " + focus.title;
        variantvaluedesktop.innerText = focus.title;

        // Remove Selected Class from all Images
        thumbs.querySelectorAll("img").forEach(img => {
            img.classList.remove("is-selected");
        });
        // Add Selected Class to Focus Image
        focus.classList.add("is-selected");
    }
})

// Change Variant Name on Mouseover (Desktop Only)
thumbs.addEventListener("mouseover", (e) => {
    const focus = e.target;
    if (focus.nodeName == "IMG") {
        variantvaluedesktop.innerText = focus.title;
    }
})

// Set Variant Name back to original on Mouseout (Desktop Only)
thumbs.addEventListener("mouseout", (e) => {
    const focus = e.target;
    if (focus.nodeName == "IMG") {
        const currentVariant = document.querySelector("#thumbs img.is-selected");
        variantvaluedesktop.innerText = currentVariant.title;
    }
})