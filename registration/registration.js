document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registrationForm');
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const emailInput = document.getElementById('email');

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

    // Tambahkan event listener untuk memvalidasi email saat kehilangan fokus dan saat mengetik
    emailInput.addEventListener('blur', async function () {
        const errorMessage = await validateInput(emailInput);
        if (errorMessage) {
            showError(emailInput, errorMessage);
        } else {
            removeError(emailInput);
        }
    });

    emailInput.addEventListener('input', async function () {
        const errorMessage = await validateInput(emailInput);
        if (errorMessage) {
            showError(emailInput, errorMessage);
        } else {
            removeError(emailInput);
        }
    });

    // **Validasi Email secara otomatis setelah input pertama kali dimasukkan**
    emailInput.addEventListener('change', async function () {
        const errorMessage = await validateInput(emailInput);
        if (errorMessage) {
            showError(emailInput, errorMessage);
        } else {
            removeError(emailInput);
        }
    });

    // Pastikan validasi dilakukan jika halaman dimuat dan email sudah diisi
    (async () => {
        if (emailInput.value.trim()) { // hanya lakukan validasi jika kolom email sudah terisi
            const errorMessage = await validateInput(emailInput);
            if (errorMessage) {
                showError(emailInput, errorMessage);
            } else {
                removeError(emailInput);
            }
        }
    })();

    async function handleNext() {
        const isValid = await validateStep(currentStep);
        if (isValid) {
            currentStep++;
            showStep(currentStep);
        }
    }

    function handlePrev() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
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

    async function validateStep(step) {
        const currentStepElement = steps[step];
        const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
        let isValid = true;

        for (const input of inputs) {
            const errorMessage = await validateInput(input);
            if (errorMessage) {
                isValid = false;
                showError(input, errorMessage);
            } else {
                removeError(input);
            }
        }

        return isValid;
    }

    async function validateInput(input) {
        const value = input.value.trim();

        if (!value) return 'Field ini wajib diisi';

        if (input.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) return 'Format email tidak valid';

            // Memeriksa ketersediaan email
            const emailError = await checkEmailAvailability(value);
            if (emailError) {
                return emailError;  // Jika email sudah terdaftar, kembalikan pesan error
            }
        }

        if (input.id === 'password') {
            if (value.length < 8) return 'Password minimal 8 karakter';
        }

        if (input.id === 'confirmPassword') {
            const password = document.getElementById('password').value;
            if (value !== password) return 'Password tidak cocok';
        }

        return null; // Tidak ada error, kembalikan null
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

    async function checkEmailAvailability(email) {
        try {
            const response = await fetchWithTimeout('registration.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ check_email: true, email })
            });

            if (response.ok) {
                const result = await response.json();
                if (!result.available) {
                    return 'Email yang Anda masukkan sudah digunakan';  // Tampilkan pesan error langsung
                }
            }
        } catch (error) {
            console.error('Error checking email:', error);
        }
        return null;  // Jika email tersedia, kembalikan null
    }

    async function handleSubmit(e) {
        e.preventDefault();
        const isValid = await validateStep(currentStep);

        if (isValid) {
            const formData = new FormData(form);

            try {
                const response = await fetchWithTimeout('registration.php', {
                    method: 'POST',
                    body: formData
                }, 10000);

                const textResponse = await response.text();

                if (response.headers.get('Content-Type')?.includes('application/json')) {
                    const result = JSON.parse(textResponse);
                    if (result.status === 'success') {
                        alert('Pendaftaran berhasil!');
                        form.reset();
                        currentStep = 0;
                        showStep(currentStep);
                    } else {
                        alert(result.message);
                    }
                } else {
                    alert('Respons server tidak valid: ' + textResponse);
                }
            } catch (error) {
                alert('Terjadi masalah koneksi atau server tidak merespons. Silakan coba lagi nanti.');
            }
        }
    }
});
