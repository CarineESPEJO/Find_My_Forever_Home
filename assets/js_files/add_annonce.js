const form = document.querySelector('form');
let hasSubmitted = false;


form.addEventListener('submit', function (event) {
    event.preventDefault();

    let isValid = true;

    const title = form.querySelector('#title');
    const imageUpload = form.querySelector('#imageUpload');
    const price = form.querySelector('#price');
    const location = form.querySelector('#location');
    const description = form.querySelector('#description');
    const propertyType = form.querySelector('#property_type');
    const transactionType = form.querySelector('#transaction_type');

    const titleError = form.querySelector('#titleError');
    const imgUploadError = form.querySelector('#imgUploadError');
    const priceError = form.querySelector('#priceError');
    const locationError = form.querySelector('#locationError');
    const descriptionError = form.querySelector('#descError');
    const proptypeError = form.querySelector('#proptypeError');
    const transtypeError = form.querySelector('#transtypeError');

    const submitButton = form.querySelector('#submitButton');

    const allInputs = form.querySelectorAll("span");

    function isValidUrl(imageUrl) {
        try {
            const url = new URL(imageUrl.trim());
            if (!['http:', 'https:'].includes(url.protocol)) return false;
            return /\.(jpe?g|png|webp)$/i.test(url.pathname);
        } catch (e) {
            return false;
        }
    }

    function isValidLocName(location) {
        const regex = /^[\p{L}\p{M}\s\'\.\-ʻ’]{1,100}$/u;
        return regex.test(location);
    };

    function isValidPrice(price) {
        const regex = /^\d+$/;
        return regex.test(price);
    };

    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    if (!(title.value.trim().length >= 5 && title.value.trim().length <= 50)) {
        isValid = false;
        titleError.textContent = 'Le titre doit contenir entre 5 et 50 caractères';
    }


    if (!isValidUrl(imageUpload.value)) {
        isValid = false;
        document.getElementById('imgUploadError').textContent = 'L\'Url de l\'image n\'est pas bonne.';
    };

    if (!isValidPrice(price.value)) {
        isValid = false;
        document.getElementById('priceError').textContent = 'Le prix doit est un nombre positif.';
    };

    if (!isValidLocName(location.value)) {
        isValid = false;
        document.getElementById('locationError').textContent = 'Entrez un vrai nom de ville';
    };

    if (!(description.value.trim().length >= 50 && description.value.trim().length <= 255)) {
        isValid = false;
        document.getElementById('descError').textContent = 'La description doit contenir entre 50 et 255 caractères';
    };

    if ((propertyType.value !== "House" && propertyType.value !== "Appartement")) {
        isValid = false;
        proptypeError.textContent = 'Le type de location doit être soit House soit Appartement';
    }


    if ((transactionType.value != "Rent" && transactionType.value != "Sale")) {
        isValid = false;
        document.getElementById('transtypeError').textContent = 'Le type de transaction doit être soit Rent soit Sale';
    };

    if (isValid && !hasSubmitted) {
        hasSubmitted = true;
        submitButton.disabled = true;
        alert("Formulaire envoyé");
        form.submit();
    }

});


