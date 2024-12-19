document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('registrationForm');
  const steps = document.querySelectorAll('.form-step');
  const progressSteps = document.querySelectorAll('.step');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const submitBtn = document.getElementById('submitBtn');

  let currentStep = 0;

  if (steps.length > 0) showStep(currentStep);

  prevBtn.addEventListener('click', handlePrev);
  nextBtn.addEventListener('click', handleNext);
  form.addEventListener('submit', handleSubmit);

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

  async function handleNext() {
      if (await validateStep(currentStep)) {
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

  async function validateStep(step) {
      const currentStepElement = steps[step];
      const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
      let isValid = true;

      for (const input of inputs) {
          const errorMessage = validateInput(input);
          if (errorMessage) {
              showError(input, errorMessage);
              isValid = false;
          } else {
              removeError(input);
          }
      }
      return isValid;
  }

  function validateInput(input) {
      const value = input.value.trim();
      if (!value) return 'Field ini wajib diisi.';
      if (input.type === 'email') {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(value)) return 'Format email tidak valid.';
      }
      if (input.id === 'password' && value.length < 8) {
          return 'Password minimal 8 karakter.';
      }
      if (input.id === 'confirmPassword') {
          const password = document.getElementById('password').value;
          if (value !== password) return 'Password tidak cocok.';
      }
      return null;
  }

  function showError(input, message) {
      let errorDiv = input.nextElementSibling;
      if (!errorDiv || !errorDiv.classList.contains('error-message')) {
          errorDiv = document.createElement('div');
          errorDiv.className = 'error-message';
          input.parentNode.appendChild(errorDiv);
      }
      errorDiv.textContent = message;
      input.style.borderColor = 'red';
  }

  function removeError(input) {
      const errorDiv = input.nextElementSibling;
      if (errorDiv && errorDiv.classList.contains('error-message')) {
          errorDiv.remove();
      }
      input.style.borderColor = '';
  }

  async function handleSubmit(e) {
      e.preventDefault();

      if (await validateStep(currentStep)) {
          const formData = new FormData(form);
          try {
              const response = await fetch('registration.php', {
                  method: 'POST',
                  body: formData
              });
              const result = await response.json();

              if (result.status === 'success') {
                  alert(result.message);
                  form.reset();
                  currentStep = 0;
                  showStep(currentStep);
              } else {
                  alert(result.message);
              }
          } catch (error) {
              alert('Terjadi masalah koneksi atau server tidak merespons. Silakan coba lagi.');
              console.error('Error:', error);
          }
      }
  }
});
