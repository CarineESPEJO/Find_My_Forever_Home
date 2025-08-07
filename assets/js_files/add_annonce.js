const form = document.querySelector('form');
let hasSubmitted = false;


form.addEventListener('submit', function (event) {
    event.preventDefault();

    let isValid = true;

    const title = form.querySelector('#title');
    const imgURL = form.querySelector('#image_URL');
    const price = form.querySelector('#price');
    const city = form.querySelector('#city');
    const description = form.querySelector('#description');
    const propertyType = form.querySelector('#property_type');
    const transactionType = form.querySelector('#transaction_type');

    const titleError = form.querySelector('#titleError');
    const imgURLError = form.querySelector('#imgURLError');
    const priceError = form.querySelector('#priceError');
    const cityError = form.querySelector('#cityError');
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

    function isValidCityName(city) {
        const regex = /^[\p{L}\p{M}\s\'\.\-ʻ’]{1,150}$/u;
        return regex.test(city);
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

    if ((imgURL.value.length > 255)) {
         isValid = false;
        document.getElementById('imgURLError').textContent = 'L\'Url de l\'image doit faire 255 caractères maximum.';
    }
    else if (!isValidUrl(imgURL.value)) {
        isValid = false;
        document.getElementById('imgURLError').textContent = 'L\'Url de l\'image n\'est pas bonne.';
    };

    if (!isValidPrice(price.value)) {
        isValid = false;
        document.getElementById('priceError').textContent = 'Le prix doit est un nombre positif.';
    };

    if (!isValidCityName(city.value)) {
        isValid = false;
        document.getElementById('cityError').textContent = 'Entrez un vrai nom de ville';
    };

    if (!(description.value.trim().length >= 50 && description.value.trim().length <= 255)) {
        isValid = false;
        document.getElementById('descError').textContent = 'La description doit contenir entre 50 et 255 caractères';
    };

    if ((propertyType.value !== "House" && propertyType.value !== "Apartment")) {
        isValid = false;
        proptypeError.textContent = 'Le type de city doit être soit House soit Apartment';
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


