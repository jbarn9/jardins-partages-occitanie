const passwordInput = document.querySelector(".password-field");
const meterSections = document.querySelectorAll(".meter-section");
const isVerified = document.getElementById("User_isVerified");

if (passwordInput) {
  passwordInput.addEventListener("input", updateMeter);
}
if (isVerified) {
  isVerified.addEventListener("change", switchVerified);
}

function switchVerified() {
  if (isVerified.checked) {
    isVerified.value = true;
  } else {
    isVerified.value = false;
  }
}

function updateMeter() {
  const password = passwordInput.value;
  let strength = calculatePasswordStrength(password);

  // Remove all strength classes
  meterSections.forEach((section) => {
    section.classList.remove("weak", "medium", "strong", "very-strong");
  });

  // Add the appropriate strength class based on the strength value
  if (strength >= 6) {
    meterSections[0].classList.add("weak");
  }
  if (strength >= 8) {
    meterSections[1].classList.add("medium");
  }
  if (strength >= 10) {
    meterSections[2].classList.add("strong");
  }
  if (strength >= 12) {
    meterSections[3].classList.add("very-strong");
  }
}

let strength = 0;
function calculatePasswordStrength(password) {
  const uppercaseWeight = 0.26;
  const lowercaseWeight = 0.26;
  const numberWeight = 0.1;
  const symbolWeight = 0.33;
  const other = 1.28;
  const passwordChars = Array.from(password).reduce((acc, char) => {
    const code = char.charCodeAt(0);
    switch (true) {
      case acc[code] < 32 || 127 === acc[code]:
        strength += symbolWeight;
        break;
      case 48 <= acc[code] && acc[code] <= 57:
        strength += numberWeight;
        break;
      case 65 <= acc[code] && acc[code] <= 90:
        strength += uppercaseWeight;
        break;
      case 97 <= acc[code] && acc[code] <= 122:
        strength += lowercaseWeight;
        break;
      case 128 <= acc[code]:
        strength += other;
        break;
    }
    if (password.length <= 4) {
      return 0;
    } else {
      return strength;
    }
  }, {});
}
