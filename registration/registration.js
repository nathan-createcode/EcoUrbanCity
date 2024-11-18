document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registrationForm');
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    let currentStep = 0;

    function fetchWithTimeout(resource, options = {}, timeout = 10000) {
        const controller = new AbortController();
        const id = setTimeout(() => controller.abort(), timeout);
        const fetchOptions = { ...options, signal: controller.signal };

        return fetch(resource, fetchOptions).finally(() => clearTimeout(id));
    }

    if (steps.length > 0) showStep(currentStep);

    prevBtn.addEventListener('click', handlePrev);
    nextBtn.addEventListener('click', handleNext);
    form.addEventListener('submit', handleSubmit);

    function handlePrev() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    }

    function handleNext() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    }

    async function handleSubmit(e) {
        e.preventDefault();
        if (validateStep(currentStep)) {
            const formData = new FormData(form);
            try {
                console.log('Sending data to registration.php');

                const response = await fetchWithTimeout('/ecourbancity/registration/registration.php', {
                    method: 'POST',
                    body: formData
                }, 10000);

                console.log('Response Status:', response.status);

                const textResponse = await response.text();
                console.log('Server Response:', textResponse);

                if (response.headers.get('Content-Type')?.includes('application/json')) {
                    const result = JSON.parse(textResponse);
                    if (result.status === 'success') {
                        alert('Pendaftaran berhasil!');
                        form.reset();
                        currentStep = 0;
                        showStep(currentStep);
                    } else {
                        alert(`Kesalahan: ${result.message}`);
                    }
                } else {
                    console.error('Respons non-JSON:', textResponse);
                    alert('Respons server tidak valid: ' + textResponse);
                }
            } catch (error) {
                console.error('Fetch Error:', error);
                alert('Terjadi masalah koneksi atau server tidak merespons. Silakan coba lagi nanti.');
            }
        }
    }

    function showStep(step) {
        steps.forEach((s, index) => {
            s.style.display = index === step ? 'block' : 'none';
        });

        progressSteps.forEach((s, index) => {
            s.classList.toggle('active', index <= step);
        });

        prevBtn.style.display = step === 0 ? 'none' : 'block';
        nextBtn.style.display = step === steps.length - 1 ? 'none' : 'block';
        submitBtn.style.display = step === steps.length - 1 ? 'block' : 'none';
    }

    function validateStep(step) {
        const currentStepElement = steps[step];
        const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            const errorMessage = validateInput(input);
            if (errorMessage) {
                isValid = false;
                showError(input, errorMessage);
            } else {
                removeError(input);
            }
        });

        return isValid;
    }

    function validateInput(input) {
        const value = input.value.trim();
        if (!value) return 'Field ini wajib diisi';

        if (input.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) return 'Format email tidak valid';
        }

        if (input.id === 'password') {
            if (value.length < 8) return 'Password minimal 8 karakter';
        }

        if (input.id === 'confirmPassword') {
            const password = document.getElementById('password').value;
            if (value !== password) return 'Password tidak cocok';
        }

        return null;
    }

    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        let errorDiv = formGroup.querySelector('.error-message');

        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = 'red';
            errorDiv.style.fontSize = '0.875rem';
            formGroup.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        input.style.borderColor = 'red';
    }

    function removeError(input) {
        const formGroup = input.closest('.form-group');
        const errorDiv = formGroup.querySelector('.error-message');
        if (errorDiv) errorDiv.remove();
        input.style.borderColor = '';
    }
});
