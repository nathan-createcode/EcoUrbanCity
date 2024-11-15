document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    let currentStep = 0;

    // Inisialisasi
    showStep(currentStep);

    // Event Listeners
    prevBtn.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    nextBtn.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (validateStep(currentStep)) {
            const formData = new FormData(form);
            try {
                const response = await fetch('process.php', {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    alert('Pendaftaran berhasil!');
                    form.reset();
                    currentStep = 0;
                    showStep(currentStep);
                } else {
                    throw new Error('Terjadi kesalahan saat mendaftar');
                }
            } catch (error) {
                alert(error.message);
            }
        }
    });

    // Fungsi untuk menampilkan step
    function showStep(step) {
        steps.forEach((s, index) => {
            s.style.display = index === step ? 'block' : 'none';
        });

        progressSteps.forEach((s, index) => {
            if (index <= step) {
                s.classList.add('active');
            } else {
                s.classList.remove('active');
            }
        });

        // Update tombol navigasi
        prevBtn.style.display = step === 0 ? 'none' : 'block';
        if (step === steps.length - 1) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'block';
        } else {
            nextBtn.style.display = 'block';
            submitBtn.style.display = 'none';
        }
    }

    // Fungsi validasi setiap step
    function validateStep(step) {
        const currentStepElement = steps[step];
        const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                showError(input, 'Field ini wajib diisi');
            } else {
                removeError(input);

                // Validasi email
                if (input.type === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(input.value)) {
                        isValid = false;
                        showError(input, 'Format email tidak valid');
                    }
                }

                // Validasi password
                if (input.id === 'password') {
                    if (input.value.length < 8) {
                        isValid = false;
                        showError(input, 'Password minimal 8 karakter');
                    }
                }

                // Validasi konfirmasi password
                if (input.id === 'confirmPassword') {
                    const password = document.getElementById('password').value;
                    if (input.value !== password) {
                        isValid = false;
                        showError(input, 'Password tidak cocok');
                    }
                }
            }
        });

        return isValid;
    }

    // Fungsi untuk menampilkan error
    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        let errorDiv = formGroup.querySelector('.error-message');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = 'var(--error-color)';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.style.marginTop = '0.25rem';
            formGroup.appendChild(errorDiv);
        }
        
        errorDiv.textContent = message;
        input.style.borderColor = 'var(--error-color)';
    }

    // Fungsi untuk menghapus error
    function removeError(input) {
        const formGroup = input.closest('.form-group');
        const errorDiv = formGroup.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.style.borderColor = '#e5e7eb';
    }
});