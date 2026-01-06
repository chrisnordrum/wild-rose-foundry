document.getElementById("filterBTN").addEventListener("click", () => {
    document.querySelector("aside").classList.toggle("is-expanded");
})
featured.parentElement.children[1].innerHTML = "Made for Gifting";
document.querySelectorAll("span.featured").forEach(tag => {
    tag.innerHTML = "Gift Ready";
})
featured.addEventListener("click", function() {
    this.form.submit();
})
priceLow.addEventListener("click", function() {
    this.form.submit();
})
priceHigh.addEventListener("click", function() {
    this.form.submit();
})