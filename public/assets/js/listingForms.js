const form = document.querySelector('form');
let hasSubmitted = false;

form.addEventListener('submit', function (event) {
    event.preventDefault();
    let isValid = true;

    // Form fields
    const title = form.querySelector('#title');
    const imageFileInput = form.querySelector('#image_file');
    const file = imageFileInput.files[0];
    const price = form.querySelector('#price');
    const city = form.querySelector('#city');
    const description = form.querySelector('#description');
    const propertyType = form.querySelector('#property_type');
    const transactionType = form.querySelector('#transaction_type');

    // Error fields
    const titleError = form.querySelector('#titleError');
    const imgError = form.querySelector('#imgError');
    const priceError = form.querySelector('#priceError');
    const cityError = form.querySelector('#cityError');
    const descriptionError = form.querySelector('#descError');
    const proptypeError = form.querySelector('#proptypeError');
    const transtypeError = form.querySelector('#transtypeError');

    const submitButton = form.querySelector('button[type="submit"]');

    // Clear previous errors
    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    // Validation functions
    function isValidCityName(city) {
        const regex = /^[\p{L}\p{M}\s\'\.\-ʻ’]{1,150}$/u;
        return regex.test(city);
    }

    function isValidPrice(price) {
        const regex = /^\d+$/;
        return regex.test(price) && parseInt(price) > 0;
    }

    // Title validation
    if (!(title.value.trim().length >= 5 && title.value.trim().length <= 255)) {
        isValid = false;
        titleError.textContent = 'Le titre doit contenir entre 5 et 255 caractères';
    }

    // Image validation
    if (form.dataset.action === "add") {
        if (!file) {
            isValid = false;
            imgError.textContent = "Vous devez sélectionner une image.";
        }
    }

    if (file) {
        const allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (!allowedTypes.includes(file.type)) {
            isValid = false;
            imgError.textContent = "Seuls les fichiers JPG, PNG ou WEBP sont acceptés.";
        } else if (file.size > maxSize) {
            isValid = false;
            imgError.textContent = "L'image ne doit pas dépasser 5 Mo.";
        }
    }

    // Price validation
    if (!isValidPrice(price.value)) {
        isValid = false;
        priceError.textContent = 'Le prix doit être un nombre entier positif.';
    }

    // City validation
    if (!isValidCityName(city.value)) {
        isValid = false;
        cityError.textContent = 'Entrez un vrai nom de ville';
    }

    // Description validation
    if (!(description.value.trim().length >= 50 && description.value.trim().length <= 1000)) {
        isValid = false;
        descriptionError.textContent = 'La description doit contenir entre 50 et 1000 caractères';
    }

    // Property type validation
    if (propertyType.value !== "House" && propertyType.value !== "Apartment") {
        isValid = false;
        proptypeError.textContent = 'Le type de propriété doit être soit House soit Apartment';
    }

    // Transaction type validation
    if (transactionType.value !== "Rent" && transactionType.value !== "Sale") {
        isValid = false;
        transtypeError.textContent = 'Le type de transaction doit être soit Rent soit Sale';
    }

    // Submit if valid
    if (isValid && !hasSubmitted) {
        hasSubmitted = true;
        submitButton.disabled = true;
        alert("Formulaire envoyé");
        form.submit();
    }
});
