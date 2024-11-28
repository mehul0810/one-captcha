document.addEventListener('DOMContentLoaded', function () {
    const captchaTypeDropdown = document.querySelector('#onecaptcha_captcha_type');
    const siteKeyField = document.querySelector('#onecaptcha_site_key');
    const secretKeyField = document.querySelector('#onecaptcha_secret_key');

    if (captchaTypeDropdown && siteKeyField && secretKeyField) {
        captchaTypeDropdown.addEventListener('change', function () {
            const selectedType = this.value;

            // Update the `name` attributes based on the selected type
            siteKeyField.setAttribute('name', `onecaptcha_settings[${selectedType}_site_key]`);
            secretKeyField.setAttribute('name', `onecaptcha_settings[${selectedType}_secret_key]`);
        });

        // Trigger change event on page load to set initial values
        captchaTypeDropdown.dispatchEvent(new Event('change'));
    }
});
