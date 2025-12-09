// ===== BASE DE DONNÉES LOCAL =====
class FrontendDB {
    constructor() {
        this.loadData();
    }

    loadData() {
        // Charger les formations depuis localStorage ou utiliser les données par défaut
        if (!localStorage.getItem('oneworld_courses')) {
            const defaultCourses = [
                {
                    id: 1,
                    title: "JavaScript Avancé - De Zéro à Expert",
                    description: "Maîtrisez JavaScript avec des projets pratiques, des concepts avancés et les dernières fonctionnalités ES6+.",
                    category: "web",
                    price: 99,
                    duration: "40 heures",
                    level: "Intermédiaire/Avancé",
                    rating: 4.8,
                    students: 1245,
                    instructor: "John Smith",
                    image: "https://images.unsplash.com/photo-1627398242454-45a1465c2479?auto=format&fit=crop&w=600&h=400",
                    status: "active",
                    objectives: [
                        "Maîtriser JavaScript ES6+",
                        "Développer des applications web modernes",
                        "Utiliser Node.js et Express",
                        "Créer des APIs RESTful"
                    ],
                    prerequisites: [
                        "Connaissances de base en HTML/CSS",
                        "Ordinateur avec connexion internet",
                        "Navigateur web moderne"
                    ]
                },
                {
                    id: 2,
                    title: "UI/UX Design - Principes et Pratiques",
                    description: "Apprenez à créer des interfaces utilisateur intuitives et esthétiques avec Figma et Adobe XD.",
                    category: "design",
                    price: 79,
                    duration: "35 heures",
                    level: "Tous niveaux",
                    rating: 4.9,
                    students: 892,
                    instructor: "Sarah Johnson",
                    image: "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=600&h=400",
                    status: "active",
                    objectives: [
                        "Créer des maquettes professionnelles",
                        "Comprendre les principes UX",
                        "Utiliser Figma et Adobe XD",
                        "Designer des interfaces responsive"
                    ],
                    prerequisites: [
                        "Intérêt pour le design",
                        "Ordinateur avec Figma/XD",
                        "Connexion internet"
                    ]
                }
            ];
            localStorage.setItem('oneworld_courses', JSON.stringify(defaultCourses));
        }

        // Charger les étudiants inscrits
        if (!localStorage.getItem('oneworld_students')) {
            localStorage.setItem('oneworld_students', JSON.stringify([]));
        }

        // Charger le panier
        if (!localStorage.getItem('oneworld_cart')) {
            localStorage.setItem('oneworld_cart', JSON.stringify([]));
        }

        // Charger les catégories
        if (!localStorage.getItem('oneworld_categories')) {
            const defaultCategories = [
                { id: 1, name: "Développement Web", color: "#2e7d32", icon: "fas fa-code" },
                { id: 2, name: "Design Graphique", color: "#388e3c", icon: "fas fa-palette" },
                { id: 3, name: "Marketing Digital", color: "#4caf50", icon: "fas fa-chart-line" },
                { id: 4, name: "Business", color: "#81c784", icon: "fas fa-briefcase" }
            ];
            localStorage.setItem('oneworld_categories', JSON.stringify(defaultCategories));
        }
    }

    getCourses() {
        return JSON.parse(localStorage.getItem('oneworld_courses')) || [];
    }

    getActiveCourses() {
        const courses = this.getCourses();
        return courses.filter(course => course.status === 'active');
    }

    getCategories() {
        return JSON.parse(localStorage.getItem('oneworld_categories')) || [];
    }

    getStudents() {
        return JSON.parse(localStorage.getItem('oneworld_students')) || [];
    }

    getCart() {
        return JSON.parse(localStorage.getItem('oneworld_cart')) || [];
    }

    saveStudent(studentData) {
        const students = this.getStudents();
        const newId = students.length > 0 ? Math.max(...students.map(s => s.id)) + 1 : 1;
        
        const newStudent = {
            id: newId,
            ...studentData,
            registrationDate: new Date().toISOString(),
            status: 'active'
        };
        
        students.push(newStudent);
        localStorage.setItem('oneworld_students', JSON.stringify(students));
        return newId;
    }

    updateCart(cart) {
        localStorage.setItem('oneworld_cart', JSON.stringify(cart));
    }

    getStats() {
        const courses = this.getActiveCourses();
        const students = this.getStudents();
        
        return {
            totalCourses: courses.length,
            totalStudents: students.length,
            totalCountries: new Set(students.map(s => s.country || 'Non spécifié')).size,
            successRate: 98 // Pour l'exemple
        };
    }
}

// ===== INITIALISATION =====
const db = new FrontendDB();
let cart = db.getCart();

document.addEventListener('DOMContentLoaded', function() {
    // Initialiser l'interface
    initNavigation();
    loadFormations();
    loadStats();
    loadCategoriesInForm();
    setupEventListeners();
    updateCartUI();
});

// ===== NAVIGATION =====
function initNavigation() {
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 70,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// ===== AFFICHAGE DES FORMATIONS =====
function loadFormations() {
    const courses = db.getActiveCourses();
    const container = document.getElementById('formationsList');
    const noCoursesMessage = document.getElementById('noCoursesMessage');
    
    if (!container) return;
    
    container.innerHTML = '';
    
    if (courses.length === 0) {
        if (noCoursesMessage) {
            noCoursesMessage.style.display = 'block';
        }
        return;
    }
    
    if (noCoursesMessage) {
        noCoursesMessage.style.display = 'none';
    }
    
    courses.forEach(course => {
        const col = createCourseCard(course);
        container.appendChild(col);
    });
}

function createCourseCard(course) {
    const col = document.createElement('div');
    col.className = 'col-lg-4 col-md-6 mb-4';
    col.setAttribute('data-category', course.category);
    
    const ratingStars = getRatingStars(course.rating);
    const categoryInfo = getCategoryInfo(course.category);
    
    col.innerHTML = `
        <div class="card h-100 course-card">
            <div class="position-relative">
                <img src="${course.image}" class="card-img-top" alt="${course.title}" 
                     style="height: 200px; object-fit: cover;">
                <span class="badge position-absolute top-0 end-0 m-2" 
                      style="background-color: ${categoryInfo.color}">
                    ${categoryInfo.name}
                </span>
                <span class="badge bg-success position-absolute top-0 start-0 m-2">
                    ${course.price}€
                </span>
            </div>
            
            <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold">${course.title}</h5>
                <p class="card-text text-muted flex-grow-1">${truncateText(course.description, 100)}</p>
                
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock text-success me-2"></i>
                        <span>${course.duration}</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-signal text-success me-2"></i>
                        <span>${getLevelName(course.level)}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chalkboard-teacher text-success me-2"></i>
                        <span>${course.instructor}</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-warning">
                        ${ratingStars}
                        <span class="ms-1">(${course.rating})</span>
                    </div>
                    <span class="text-muted">
                        <i class="fas fa-users me-1"></i>${course.students}
                    </span>
                </div>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-success view-details-btn" data-id="${course.id}">
                        <i class="fas fa-info-circle me-2"></i>Voir détails
                    </button>
                    <button class="btn btn-success add-to-cart-btn" data-id="${course.id}">
                        <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                    </button>
                </div>
            </div>
        </div>
    `;
    
    return col;
}

// ===== DÉTAILS FORMATION =====
function showCourseDetails(courseId) {
    const courses = db.getActiveCourses();
    const course = courses.find(c => c.id === courseId);
    
    if (!course) {
        alert('Formation non trouvée');
        return;
    }
    
    const categoryInfo = getCategoryInfo(course.category);
    const ratingStars = getRatingStars(course.rating);
    
    document.getElementById('courseModalTitle').textContent = course.title;
    
    const modalBody = document.getElementById('courseModalBody');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <img src="${course.image}" class="img-fluid rounded mb-3" alt="${course.title}">
                <div class="d-flex justify-content-between mb-3">
                    <span class="badge" style="background-color: ${categoryInfo.color}">
                        ${categoryInfo.name}
                    </span>
                    <span class="badge bg-success">${course.price}€</span>
                </div>
            </div>
            
            <div class="col-md-6">
                <h5 class="fw-bold">Description</h5>
                <p class="mb-4">${course.description}</p>
                
                <div class="row mb-4">
                    <div class="col-6">
                        <p><i class="fas fa-clock text-success me-2"></i><strong>Durée:</strong> ${course.duration}</p>
                    </div>
                    <div class="col-6">
                        <p><i class="fas fa-signal text-success me-2"></i><strong>Niveau:</strong> ${getLevelName(course.level)}</p>
                    </div>
                    <div class="col-6">
                        <p><i class="fas fa-chalkboard-teacher text-success me-2"></i><strong>Formateur:</strong> ${course.instructor}</p>
                    </div>
                    <div class="col-6">
                        <p><i class="fas fa-users text-success me-2"></i><strong>Étudiants:</strong> ${course.students}</p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="fw-bold">Note moyenne</h6>
                    <div class="d-flex align-items-center">
                        ${ratingStars}
                        <span class="ms-2 fw-bold">${course.rating}/5</span>
                    </div>
                </div>
                
                ${course.objectives ? `
                <div class="mb-4">
                    <h6 class="fw-bold">Objectifs d'apprentissage</h6>
                    <ul class="list-unstyled">
                        ${course.objectives.map(obj => `<li><i class="fas fa-check text-success me-2"></i>${obj}</li>`).join('')}
                    </ul>
                </div>
                ` : ''}
                
                ${course.prerequisites ? `
                <div class="mb-4">
                    <h6 class="fw-bold">Prérequis</h6>
                    <ul class="list-unstyled">
                        ${course.prerequisites.map(pre => `<li><i class="fas fa-circle text-success me-2" style="font-size: 0.5rem"></i>${pre}</li>`).join('')}
                    </ul>
                </div>
                ` : ''}
            </div>
        </div>
    `;
    
    // Afficher le modal
    const modal = new bootstrap.Modal(document.getElementById('courseModal'));
    modal.show();
}

// ===== INSCRIPTION =====
function loadCategoriesInForm() {
    const categories = db.getCategories();
    const select = document.getElementById('selectedCourse');
    
    if (!select) return;
    
    // Vider les options sauf la première
    while (select.options.length > 1) {
        select.remove(1);
    }
    
    // Ajouter les formations actives
    const courses = db.getActiveCourses();
    courses.forEach(course => {
        const option = document.createElement('option');
        option.value = course.id;
        option.textContent = `${course.title} - ${course.price}€`;
        select.appendChild(option);
    });
}

function handleInscription(event) {
    event.preventDefault();
    
    const form = document.getElementById('inscriptionForm');
    const courseId = document.getElementById('selectedCourse').value;
    const courses = db.getActiveCourses();
    const course = courses.find(c => c.id === parseInt(courseId));
    
    if (!course) {
        alert('Veuillez sélectionner une formation');
        return;
    }
    
    // Récupérer les données du formulaire
    const formData = new FormData(form);
    const studentData = {
        firstName: formData.get('firstName') || '',
        lastName: formData.get('lastName') || '',
        email: formData.get('email') || '',
        phone: formData.get('phone') || '',
        courseId: courseId,
        courseTitle: course.title,
        coursePrice: course.price
    };
    
    // Sauvegarder l'étudiant
    const studentId = db.saveStudent(studentData);
    
    // Afficher la confirmation
    const modal = new bootstrap.Modal(document.getElementById('inscriptionModal'));
    modal.show();
    
    // Réinitialiser le formulaire
    form.reset();
    
    // Mettre à jour les statistiques
    loadStats();
    
    // Envoyer une notification (simulation)
    console.log(`Nouvel étudiant inscrit: ${studentData.firstName} ${studentData.lastName}`);
}

// ===== PANIER =====
function addToCart(courseId) {
    const courses = db.getActiveCourses();
    const course = courses.find(c => c.id === courseId);
    
    if (!course) return;
    
    const existingItem = cart.find(item => item.id === courseId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: course.id,
            title: course.title,
            price: course.price,
            image: course.image,
            quantity: 1
        });
    }
    
    db.updateCart(cart);
    updateCartUI();
    showNotification('Formation ajoutée au panier !', 'success');
}

function updateCartUI() {
    const cartCount = document.getElementById('cartCount');
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'inline' : 'none';
    }
    
    if (cartItems && cartTotal) {
        cartItems.innerHTML = '';
        
        if (cart.length === 0) {
            cartItems.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h5>Votre panier est vide</h5>
                    <p class="text-muted">Ajoutez des formations pour commencer</p>
                </div>
            `;
            cartTotal.textContent = '0€';
            return;
        }
        
        cart.forEach(item => {
            const itemElement = document.createElement('div');
            itemElement.className = 'cart-item d-flex align-items-center mb-3 pb-3 border-bottom';
            itemElement.innerHTML = `
                <img src="${item.image}" class="rounded me-3" width="60" height="40" style="object-fit: cover;">
                <div class="flex-grow-1">
                    <h6 class="mb-1">${item.title}</h6>
                    <p class="text-success mb-0 fw-bold">${item.price}€ × ${item.quantity}</p>
                </div>
                <button class="btn btn-link text-danger remove-from-cart" data-id="${item.id}">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            cartItems.appendChild(itemElement);
        });
        
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        cartTotal.textContent = `${total}€`;
    }
}

function removeFromCart(courseId) {
    cart = cart.filter(item => item.id !== courseId);
    db.updateCart(cart);
    updateCartUI();
    showNotification('Formation retirée du panier', 'warning');
}

// ===== STATISTIQUES =====
function loadStats() {
    const stats = db.getStats();
    
    document.getElementById('statsCourses').textContent = stats.totalCourses + '+';
    document.getElementById('statsStudents').textContent = stats.totalStudents + '+';
    document.getElementById('statsCountries').textContent = stats.totalCountries + '+';
    document.getElementById('statsSuccess').textContent = stats.successRate + '%';
}

// ===== UTILITAIRES =====
function getRatingStars(rating) {
    let stars = '';
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }
    
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }
    
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
    }
    
    return stars;
}

function getCategoryInfo(categoryCode) {
    const categories = db.getCategories();
    const category = categories.find(c => c.name.toLowerCase().includes(categoryCode.toLowerCase()) ||
                                        categoryCode.toLowerCase().includes(c.name.toLowerCase()));
    
    return {
        name: category ? category.name : categoryCode,
        color: category ? category.color : '#2e7d32',
        icon: category ? category.icon : 'fas fa-folder'
    };
}

function getLevelName(levelCode) {
    const levels = {
        'débutant': 'Débutant',
        'intermédiaire': 'Intermédiaire',
        'avancé': 'Avancé'
    };
    return levels[levelCode] || levelCode;
}

function truncateText(text, maxLength) {
    if (!text) return '';
    if (text.length <= maxLength) return text;
    return text.substr(0, maxLength) + '...';
}

function showNotification(message, type = 'success') {
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    `;
    
    const icon = type === 'success' ? 'check-circle' : 
                 type === 'danger' ? 'exclamation-circle' : 'info-circle';
    
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${icon} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Ajouter au DOM
    document.body.appendChild(notification);
    
    // Supprimer automatiquement après 3 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }
    }, 3000);
}

// ===== EVENT LISTENERS =====
function setupEventListeners() {
    // Formulaire d'inscription
    const inscriptionForm = document.getElementById('inscriptionForm');
    if (inscriptionForm) {
        inscriptionForm.addEventListener('submit', handleInscription);
    }
    
    // Filtres des formations
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterCourses(this.getAttribute('data-filter'));
        });
    });
    
    // Recherche
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    
    if (searchInput && searchButton) {
        searchButton.addEventListener('click', searchCourses);
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') searchCourses();
        });
    }
    
    // Détails des formations
    document.addEventListener('click', function(e) {
        // Voir détails
        if (e.target.closest('.view-details-btn')) {
            const button = e.target.closest('.view-details-btn');
            const courseId = parseInt(button.getAttribute('data-id'));
            showCourseDetails(courseId);
        }
        
        // Ajouter au panier
        if (e.target.closest('.add-to-cart-btn')) {
            const button = e.target.closest('.add-to-cart-btn');
            const courseId = parseInt(button.getAttribute('data-id'));
            addToCart(courseId);
        }
        
        // Supprimer du panier
        if (e.target.closest('.remove-from-cart')) {
            const button = e.target.closest('.remove-from-cart');
            const courseId = parseInt(button.getAttribute('data-id'));
            removeFromCart(courseId);
        }
        
        // Inscription depuis modal
        if (e.target.closest('#inscriptionFromModal')) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('courseModal'));
            modal.hide();
            
            // Scroll vers le formulaire d'inscription
            const inscriptionSection = document.getElementById('comment-inscrire');
            if (inscriptionSection) {
                window.scrollTo({
                    top: inscriptionSection.offsetTop - 70,
                    behavior: 'smooth'
                });
            }
        }
    });
    
    // Panier
    const cartButton = document.getElementById('cartButton');
    if (cartButton) {
        cartButton.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('cartModal'));
            modal.show();
        });
    }
}

// ===== FONCTIONS DE FILTRAGE =====
function filterCourses(filter) {
    const cards = document.querySelectorAll('.course-card');
    
    cards.forEach(card => {
        const category = card.closest('[data-category]').getAttribute('data-category');
        
        if (filter === 'all' || category === filter) {
            card.closest('.col-lg-4').style.display = 'block';
        } else {
            card.closest('.col-lg-4').style.display = 'none';
        }
    });
}

function searchCourses() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.course-card');
    
    cards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        const description = card.querySelector('.card-text').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || description.includes(searchTerm)) {
            card.closest('.col-lg-4').style.display = 'block';
        } else {
            card.closest('.col-lg-4').style.display = 'none';
        }
    });
}

// ===== FONCTIONS GLOBALES =====
window.addToCart = addToCart;
window.removeFromCart = removeFromCart;
window.showCourseDetails = showCourseDetails;