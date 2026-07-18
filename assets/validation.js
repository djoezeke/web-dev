document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form[data-validate="true"]');

    forms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            let isValid = true;
            const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');

            requiredFields.forEach((field) => {
                if (!field.value || (typeof field.value === 'string' && field.value.trim() === '')) {
                    field.classList.add('invalid');
                    isValid = false;
                } else {
                    field.classList.remove('invalid');
                }
            });

            const numericFields = form.querySelectorAll('input[type="number"]');
            numericFields.forEach((field) => {
                const value = Number(field.value);
                if (field.required && Number.isNaN(value)) {
                    field.classList.add('invalid');
                    isValid = false;
                    return;
                }

                if (field.min && value < Number(field.min)) {
                    field.classList.add('invalid');
                    isValid = false;
                }
                if (field.max && value > Number(field.max)) {
                    field.classList.add('invalid');
                    isValid = false;
                }
            });

            if (!isValid) {
                event.preventDefault();
                alert('Please correct the highlighted fields before submitting.');
            }
        });
    });
});
