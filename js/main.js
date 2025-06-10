// Smooth scrolling untuk navigasi
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
    } else {
        navbar.style.backgroundColor = '#ffffff';
    }
});

// Language selector
const languageSelector = document.querySelector('.language-selector select');
if (languageSelector) {
    languageSelector.addEventListener('change', function() {
        changeLanguage(this.value);
    });
}

// Function to change language
function changeLanguage(lang) {
    // Save selected language to localStorage
    localStorage.setItem('language', lang);
    
    // Get all elements with data-translate attribute
    const elements = document.querySelectorAll('[data-translate]');
    elements.forEach(element => {
        const key = element.getAttribute('data-translate');
        if (translations[lang] && translations[lang][key]) {
            element.textContent = translations[lang][key];
        }
    });

    // Handle array items (e.g., "documents.0")
    const arrayElements = document.querySelectorAll('[data-translate*="."]');
    arrayElements.forEach(element => {
        const key = element.getAttribute('data-translate');
        const [arrayKey, index] = key.split('.');
        if (translations[lang] && translations[lang][arrayKey] && translations[lang][arrayKey][parseInt(index)]) {
            element.textContent = translations[lang][arrayKey][parseInt(index)];
        }
    });

    // Handle placeholders
    const placeholderElements = document.querySelectorAll('[data-translate-placeholder]');
    placeholderElements.forEach(element => {
        const key = element.getAttribute('data-translate-placeholder');
        if (translations[lang] && translations[lang][key]) {
            element.placeholder = translations[lang][key];
        }
    });

    // Handle alt text for images
    const altElements = document.querySelectorAll('[data-translate-alt]');
    altElements.forEach(element => {
        const key = element.getAttribute('data-translate-alt');
        if (translations[lang] && translations[lang][key]) {
            element.alt = translations[lang][key];
        }
    });

    // Update page title
    const titleElement = document.querySelector('title[data-translate]');
    if (titleElement) {
        const key = titleElement.getAttribute('data-translate');
        if (translations[lang] && translations[lang][key]) {
            document.title = translations[lang][key];
        }
    }
}

// Service cards animation
const serviceCards = document.querySelectorAll('.service-card');
const observerOptions = {
    threshold: 0.1
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

serviceCards.forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    observer.observe(card);
}); 