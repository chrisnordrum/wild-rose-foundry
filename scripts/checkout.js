checkout.addEventListener("input", (e) => {
    const focus = e.target;

    if (focus.nodeName == "INPUT") {
        // Change Label
        if (focus.value.length !== 0) {
            focusClasses = focus.parentNode.classList;
            focusClasses.remove("ol-center");
            focusClasses.add("ol-top");
        } else {
            focusClasses.remove("ol-top");
            focusClasses.add("ol-center");
        }
    }
})