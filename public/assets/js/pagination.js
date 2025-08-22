document.addEventListener("DOMContentLoaded", () => {
    const gotoInput = document.querySelector("input[name='goto_page']");
    const gotoButton = document.querySelector("button[name='go']");

    if (gotoInput && gotoButton) {
        gotoInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                gotoButton.click();
            }
        });
    }
});
