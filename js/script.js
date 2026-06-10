// Mobile menu toggle
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});

// Close menu when link is clicked
navLinks.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
        navLinks.classList.remove('active');
    });
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Load projects from backend
async function loadProjects() {
    try {
        const response = await fetch('backend/api/get_projects.php');
        const projects = await response.json();
        displayProjects(projects);
    } catch (error) {
        console.error('Error loading projects:', error);
        displaySampleProjects();
    }
}

// Display projects
function displayProjects(projects) {
    const projectsGrid = document.getElementById('projectsGrid');
    projectsGrid.innerHTML = '';

    projects.forEach(project => {
        const projectCard = document.createElement('div');
        projectCard.className = 'project-card';
        projectCard.innerHTML = `
            <img src="${project.image}" alt="${project.title}" class="project-image">
            <div class="project-content">
                <h3>${project.title}</h3>
                <p>${project.description}</p>
                <a href="${project.link}" class="project-link">View Project →</a>
            </div>
        `;
        projectsGrid.appendChild(projectCard);
    });
}

// Display sample projects (fallback)
function displaySampleProjects() {
    const sampleProjects = [
        {
            title: 'E-Commerce Platform',
            description: 'A full-stack e-commerce platform with payment integration and admin dashboard.',
            image: 'https://via.placeholder.com/300x200?text=E-Commerce',
            link: '#'
        },
        {
            title: 'Task Management App',
            description: 'Collaborative task management application with real-time updates.',
            image: 'https://via.placeholder.com/300x200?text=Task+Manager',
            link: '#'
        },
        {
            title: 'Weather Dashboard',
            description: 'Real-time weather dashboard using external API with beautiful UI.',
            image: 'https://via.placeholder.com/300x200?text=Weather',
            link: '#'
        }
    ];
    displayProjects(sampleProjects);
}

// Contact form submission
const contactForm = document.getElementById('contactForm');
const formResponse = document.getElementById('formResponse');

contactForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(contactForm);

    try {
        const response = await fetch('backend/api/send_message.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            formResponse.className = 'form-response success';
            formResponse.textContent = 'Message sent successfully!';
            contactForm.reset();
        } else {
            formResponse.className = 'form-response error';
            formResponse.textContent = result.message || 'Error sending message';
        }
    } catch (error) {
        formResponse.className = 'form-response error';
        formResponse.textContent = 'Error sending message. Please try again.';
        console.error('Error:', error);
    }
});

// Load projects on page load
document.addEventListener('DOMContentLoaded', loadProjects);

// Scroll animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.project-card, .skill-card').forEach(el => {
    observer.observe(el);
});