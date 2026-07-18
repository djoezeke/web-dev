document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form[data-validate="true"]');

    const setFieldError = (field, message) => {
        field.classList.add('invalid');

        let helper = field.parentElement.querySelector('.field-error');
        if (!helper) {
            helper = document.createElement('small');
            helper.className = 'field-error';
            field.parentElement.appendChild(helper);
        }

        helper.textContent = message;
    };

    const clearFieldError = (field) => {
        field.classList.remove('invalid');
        const helper = field.parentElement.querySelector('.field-error');
        if (helper) {
            helper.remove();
        }
    };

    const validateField = (field) => {
        if (field.disabled) {
            clearFieldError(field);
            return true;
        }

        const value = typeof field.value === 'string' ? field.value.trim() : field.value;

        if (field.required && value === '') {
            setFieldError(field, 'This field is required.');
            return false;
        }

        if (field.type === 'email' && value !== '' && !field.checkValidity()) {
            setFieldError(field, 'Enter a valid email address.');
            return false;
        }

        if (field.type === 'number' && value !== '') {
            const num = Number(value);
            if (Number.isNaN(num)) {
                setFieldError(field, 'Enter a valid number.');
                return false;
            }

            if (field.min !== '' && num < Number(field.min)) {
                setFieldError(field, `Minimum allowed value is ${field.min}.`);
                return false;
            }

            if (field.max !== '' && num > Number(field.max)) {
                setFieldError(field, `Maximum allowed value is ${field.max}.`);
                return false;
            }
        }

        if (field.pattern && value !== '' && !field.checkValidity()) {
            setFieldError(field, 'Please match the required format.');
            return false;
        }

        clearFieldError(field);
        return true;
    };

    forms.forEach((form) => {
        const fields = form.querySelectorAll('input, select, textarea');

        fields.forEach((field) => {
            field.addEventListener('blur', () => validateField(field));
            field.addEventListener('input', () => {
                if (field.classList.contains('invalid')) {
                    validateField(field);
                }
            });
        });

        form.addEventListener('submit', (event) => {
            let isValid = true;

            fields.forEach((field) => {
                const fieldValid = validateField(field);
                if (!fieldValid) {
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
