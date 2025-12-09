// Données simulées pour le backend
let formations = JSON.parse(localStorage.getItem('formations')) || [
    {
        id: 1,
        name: "Développeur Full-Stack JavaScript",
        category: "web",
        price: 499,
        duration: 80,
        students: 245,
        status: "active",
        description: "Formation complète pour devenir développeur full-stack avec React, Node.js et MongoDB",
        level: "intermediaire",
        certified: true,
        createdAt: "2023-10-15",
        objectives: "Maîtriser le développement full-stack, créer des applications web complètes",
        language: "fr",
        image: "https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=400"
    },
    {
        id: 2,
        name: "Designer UI/UX Professionnel",
        category: "design",
        price: 449,
        duration: 60,
        students: 189,
        status: "active",
        description: "Apprenez à créer des interfaces utilisateur exceptionnelles et des expériences utilisateur mémorables",
        level: "debutant",
        certified: true,
        createdAt: "2023-11-02",
        objectives: "Concevoir des interfaces intuitives, maîtriser Figma et Adobe XD",
        language: "fr",
        image: "https://images.unsplash.com/photo-1561070791-2526d30994b5?w-400"
    },
    {
        id: 3,
        name: "Marketing Digital Complet",
        category: "marketing",
        price: 399,
        duration: 50,
        students: 312,
        status: "active",
        description: "Maîtrisez toutes les techniques du marketing digital de A à Z",
        level: "debutant",
        certified: true,
        createdAt: "2023-09-20",
        objectives: "SEO, réseaux sociaux, email marketing, Google Ads",
        language: "fr",
        image: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400"
    },
    {
        id: 4,
        name: "Data Analyst",
        category: "data",
        price: 549,
        duration: 70,
        students: 156,
        status: "active",
        description: "Devenez expert en analyse de données avec Python et SQL",
        level: "intermediaire",
        certified: true,
        createdAt: "2023-12-01",
        objectives: "Analyser des datasets, créer des dashboards, faire des prédictions",
        language: "en",
        image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400"
    },
    {
        id: 5,
        name: "Anglais Professionnel",
        category: "langue",
        price: 299,
        duration: 40,
        students: 421,
        status: "active",
        description: "Améliorez votre anglais pour le monde professionnel",
        level: "debutant",
        certified: true,
        createdAt: "2023-08-15",
        objectives: "Communication professionnelle, rédaction d'emails, présentations",
        language: "en",
        image: "https://images.unsplash.com/photo-1544717305-99670f9c28f4?w=400"
    }
];

let inscriptions = JSON.parse(localStorage.getItem('inscriptions')) || [
    {
        id: 1,
        student: "Jean Dupont",
        email: "jean.dupont@email.com",
        phone: "0612345678",
        course: "Développeur Full-Stack JavaScript",
        courseId: 1,
        date: "2023-10-20",
        price: 499,
        status: "paid",
        paymentMethod: "carte"
    },
    {
        id: 2,
        student: "Marie Martin",
        email: "marie.martin@email.com",
        phone: "0623456789",
        course: "Designer UI/UX Professionnel",
        courseId: 2,
        date: "2023-11-05",
        price: 449,
        status: "paid",
        paymentMethod: "paypal"
    },
    {
        id: 3,
        student: "Pierre Durand",
        email: "pierre.durand@email.com",
        phone: "0634567890",
        course: "Marketing Digital Complet",
        courseId: 3,
        date: "2023-09-25",
        price: 399,
        status: "pending",
        paymentMethod: "carte"
    }
];

let categories = JSON.parse(localStorage.getItem('categories')) || [
    { id: 1, name: "Développement Web", description: "Formations en développement web et mobile", count: 6, color: "#1565c0", icon: "fas fa-code" },
    { id: 2, name: "Design", description: "Design graphique, UI/UX, motion design", count: 4, color: "#7b1fa2", icon: "fas fa-paint-brush" },
    { id: 3, name: "Marketing Digital", description: "SEO, réseaux sociaux, publicité en ligne", count: 5, color: "#2e7d32", icon: "fas fa-bullhorn" },
    { id: 4, name: "Business", description: "Gestion, leadership, entrepreneuriat", count: 3, color: "#ef6c00", icon: "fas fa-briefcase" },
    { id: 5, name: "Data & Cybersécurité", description: "Data science, analyse de données, sécurité informatique", count: 2, color: "#0277bd", icon: "fas fa-database" },
    { id: 6, name: "Langues", description: "Formations linguistiques professionnelles", count: 3, color: "#c2185b", icon: "fas fa-language" }
];

// Sauvegarder les données dans localStorage
function saveData() {
    localStorage.setItem('formations', JSON.stringify(formations));
    localStorage.setItem('inscriptions', JSON.stringify(inscriptions));
    localStorage.setItem('categories', JSON.stringify(categories));
}

// Générer un ID unique
function generateId(array) {
    return array.length > 0 ? Math.max(...array.map(item => item.id)) + 1 : 1;
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin panel initialisé');
    loadDashboard();
    loadFormations();
    loadInscriptions();
    loadCategories();
    setupEventListeners();
    initDataTables();
});

// Gestion des sections
function showSection(sectionId) {
    console.log('Changement de section:', sectionId);
    
    // Masquer toutes les sections
    document.getElementById('dashboardSection').style.display = 'none';
    document.getElementById('formationsSection').style.display = 'none';
    document.getElementById('inscriptionsSection').style.display = 'none';
    document.getElementById('categoriesSection').style.display = 'none';
    document.getElementById('statistiquesSection').style.display = 'none';
    
    // Afficher la section demandée
    document.getElementById(sectionId + 'Section').style.display = 'block';
    
    // Mettre à jour le titre
    const titles = {
        'dashboard': 'Tableau de bord',
        'formations': 'Gestion des Formations',
        'inscriptions': 'Gestion des Inscriptions',
        'categories': 'Gestion des Catégories',
        'statistiques': 'Statistiques'
    };
    document.getElementById('sectionTitle').textContent = titles[sectionId];
    
    // Mettre à jour le menu actif
    document.querySelectorAll('.sidebar-menu a').forEach(link => {
        link.classList.remove('active');
        if (link.dataset.section === sectionId) {
            link.classList.add('active');
        }
    });
    
    // Recharger les données si nécessaire
    if (sectionId === 'formations') {
        loadFormations();
    } else if (sectionId === 'inscriptions') {
        loadInscriptions();
    } else if (sectionId === 'categories') {
        loadCategories();
    }
}

// Chargement du tableau de bord
function loadDashboard() {
    console.log('Chargement du dashboard...');
    
    // Mettre à jour les statistiques
    document.getElementById('totalFormations').textContent = formations.length;
    document.getElementById('totalInscriptions').textContent = inscriptions.length;
    
    const totalRevenue = inscriptions.reduce((sum, ins) => {
        return sum + (ins.status === 'paid' ? ins.price : 0);
    }, 0);
    document.getElementById('totalRevenue').textContent = totalRevenue + '€';
    
    document.getElementById('tauxSatisfaction').textContent = '98%';
    
    // Charger les formations récentes
    const recentCoursesBody = document.getElementById('recentCoursesBody');
    if (recentCoursesBody) {
        recentCoursesBody.innerHTML = '';
        
        // Trier par date de création (les plus récentes d'abord)
        const recentFormations = [...formations]
            .sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt))
            .slice(0, 5);
        
        recentFormations.forEach(formation => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${formation.name}</td>
                <td><span class="badge-category badge-${formation.category}">${getCategoryName(formation.category)}</span></td>
                <td class="fw-bold">${formation.price}€</td>
                <td>${formation.createdAt}</td>
                <td>
                    <span class="action-btn edit-btn" onclick="editFormation(${formation.id})" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </span>
                    <span class="action-btn delete-btn" onclick="confirmDelete(${formation.id}, '${formation.name}')" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </span>
                </td>
            `;
            recentCoursesBody.appendChild(row);
        });
    }
    
    // Créer le graphique des catégories
    createCategoryChart();
}

// Charger les formations
function loadFormations() {
    console.log('Chargement des formations...');
    const formationsBody = document.getElementById('formationsBody');
    if (!formationsBody) return;
    
    formationsBody.innerHTML = '';
    
    if (formations.length === 0) {
        formationsBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5>Aucune formation disponible</h5>
                    <p class="text-muted">Cliquez sur "Ajouter une formation" pour commencer</p>
                </td>
            </tr>
        `;
        return;
    }
    
    formations.forEach(formation => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>#${formation.id}</td>
            <td>
                <div class="d-flex align-items-center">
                    ${formation.image ? `<img src="${formation.image}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">` : ''}
                    <div>
                        <strong>${formation.name}</strong>
                        <br><small class="text-muted">${formation.description.substring(0, 50)}...</small>
                    </div>
                </div>
            </td>
            <td><span class="badge-category badge-${formation.category}">${getCategoryName(formation.category)}</span></td>
            <td class="fw-bold">${formation.price}€</td>
            <td>${formation.duration}h</td>
            <td>
                <span class="badge bg-success">${formation.students}</span>
            </td>
            <td>
                <span class="badge ${formation.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                    ${formation.status === 'active' ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td>
                <span class="action-btn view-btn" onclick="viewFormation(${formation.id})" title="Voir détails">
                    <i class="fas fa-eye"></i>
                </span>
                <span class="action-btn edit-btn" onclick="editFormation(${formation.id})" title="Modifier">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="action-btn delete-btn" onclick="confirmDelete(${formation.id}, '${formation.name}')" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </span>
            </td>
        `;
        formationsBody.appendChild(row);
    });
    
    // Initialiser/re-initialiser DataTable
    initFormationsDataTable();
}

// Initialiser DataTable pour les formations
function initFormationsDataTable() {
    if ($.fn.DataTable.isDataTable('#formationsTable')) {
        $('#formationsTable').DataTable().destroy();
    }
    
    $('#formationsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        pageLength: 10,
        responsive: true
    });
}

// Charger les inscriptions
function loadInscriptions() {
    console.log('Chargement des inscriptions...');
    const inscriptionsBody = document.getElementById('inscriptionsBody');
    if (!inscriptionsBody) return;
    
    inscriptionsBody.innerHTML = '';
    
    if (inscriptions.length === 0) {
        inscriptionsBody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5>Aucune inscription</h5>
                    <p class="text-muted">Aucun étudiant ne s'est encore inscrit</p>
                </td>
            </tr>
        `;
        return;
    }
    
    inscriptions.forEach(inscription => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>#${inscription.id}</td>
            <td>
                <strong>${inscription.student}</strong>
                <br><small class="text-muted">${inscription.email}</small>
            </td>
            <td>${inscription.course}</td>
            <td>${inscription.date}</td>
            <td class="fw-bold">${inscription.price}€</td>
            <td>
                <span class="badge ${inscription.status === 'paid' ? 'bg-success' : 'bg-warning'}">
                    ${inscription.status === 'paid' ? 'Payé' : 'En attente'}
                </span>
            </td>
            <td>
                <span class="action-btn view-btn" onclick="viewInscription(${inscription.id})" title="Voir détails">
                    <i class="fas fa-eye"></i>
                </span>
                <button class="btn btn-sm btn-outline-success" onclick="validatePayment(${inscription.id})" title="Valider le paiement">
                    <i class="fas fa-check"></i>
                </button>
            </td>
        `;
        inscriptionsBody.appendChild(row);
    });
    
    initInscriptionsDataTable();
}

// Initialiser DataTable pour les inscriptions
function initInscriptionsDataTable() {
    if ($.fn.DataTable.isDataTable('#inscriptionsTable')) {
        $('#inscriptionsTable').DataTable().destroy();
    }
    
    $('#inscriptionsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        pageLength: 10
    });
}

// Charger les catégories
function loadCategories() {
    console.log('Chargement des catégories...');
    const categoriesBody = document.getElementById('categoriesBody');
    if (!categoriesBody) return;
    
    categoriesBody.innerHTML = '';
    
    // Mettre à jour le nombre de formations par catégorie
    categories.forEach(category => {
        category.count = formations.filter(f => f.category === getCategoryCode(category.name)).length;
    });
    
    saveData();
    
    categories.forEach(category => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <span style="display: inline-block; width: 20px; height: 20px; background-color: ${category.color}; border-radius: 3px; margin-right: 10px;"></span>
                    <strong>${category.name}</strong>
                </div>
            </td>
            <td>${category.description || 'Aucune description'}</td>
            <td><span class="badge bg-primary">${category.count} formations</span></td>
            <td>
                ${category.color}
                <br><small><i class="${category.icon || 'fas fa-tag'}"></i></small>
            </td>
            <td>
                <span class="action-btn edit-btn" onclick="editCategory(${category.id})" title="Modifier">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="action-btn delete-btn" onclick="confirmDeleteCategory(${category.id}, '${category.name}')" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </span>
            </td>
        `;
        categoriesBody.appendChild(row);
    });
}

// Créer le graphique des catégories
function createCategoryChart() {
    const ctx = document.getElementById('categoryChart');
    if (!ctx) return;
    
    // Détruire le graphique existant
    if (window.categoryChartInstance) {
        window.categoryChartInstance.destroy();
    }
    
    const categoryData = categories.map(c => ({
        name: c.name,
        count: c.count,
        color: c.color
    }));
    
    window.categoryChartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(c => c.name),
            datasets: [{
                data: categoryData.map(c => c.count),
                backgroundColor: categoryData.map(c => c.color),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.raw} formations`;
                        }
                    }
                }
            }
        }
    });
}

// Obtenir le nom de la catégorie
function getCategoryName(categoryCode) {
    const categoryMap = {
        'web': 'Développement Web',
        'design': 'Design',
        'marketing': 'Marketing Digital',
        'business': 'Business',
        'data': 'Data & Cybersécurité',
        'langue': 'Langues'
    };
    return categoryMap[categoryCode] || categoryCode;
}

// Obtenir le code de la catégorie
function getCategoryCode(categoryName) {
    const categoryMap = {
        'Développement Web': 'web',
        'Design': 'design',
        'Marketing Digital': 'marketing',
        'Business': 'business',
        'Data & Cybersécurité': 'data',
        'Langues': 'langue'
    };
    return categoryMap[categoryName] || categoryName.toLowerCase();
}

// Écouteurs d'événements
function setupEventListeners() {
    console.log('Configuration des écouteurs d\'événements...');
    
    // Menu de navigation
    document.querySelectorAll('.sidebar-menu a[data-section]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.dataset.section === 'logout') {
                window.location.href = 'index.html';
            } else {
                showSection(this.dataset.section);
            }
        });
    });
    
    // Boutons de déconnexion
    const logoutBtns = ['logoutBtn', 'logoutBtn2'];
    logoutBtns.forEach(btnId => {
        const btn = document.getElementById(btnId);
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                    window.location.href = 'index.html';
                }
            });
        }
    });
    
    // Sauvegarder une nouvelle formation
    const saveFormationBtn = document.getElementById('saveFormationBtn');
    if (saveFormationBtn) {
        saveFormationBtn.addEventListener('click', function() {
            addFormation();
        });
    }
    
    // Mettre à jour une formation
    const updateFormationBtn = document.getElementById('updateFormationBtn');
    if (updateFormationBtn) {
        updateFormationBtn.addEventListener('click', function() {
            updateFormation();
        });
    }
    
    // Confirmer la suppression
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            deleteFormation();
        });
    }
    
    // Recherche de formations
    const searchBtn = document.getElementById('searchBtn');
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            searchFormations();
        });
    }
    
    // Export
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            exportFormations();
        });
    }
    
    // Recherche avec Entrée
    const searchInput = document.getElementById('searchFormation');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchFormations();
            }
        });
    }
}

// Initialiser les DataTables
function initDataTables() {
    console.log('Initialisation des DataTables...');
    
    // Formations table
    if (document.getElementById('formationsTable')) {
        initFormationsDataTable();
    }
    
    // Inscriptions table
    if (document.getElementById('inscriptionsTable')) {
        initInscriptionsDataTable();
    }
}

// Ajouter une nouvelle formation
function addFormation() {
    console.log('Ajout d\'une nouvelle formation...');
    
    const name = document.getElementById('formationName').value.trim();
    const category = document.getElementById('formationCategory').value;
    const price = parseFloat(document.getElementById('formationPrice').value);
    const duration = parseInt(document.getElementById('formationDuration').value);
    const description = document.getElementById('formationDescription').value.trim();
    const objectives = document.getElementById('formationObjectives').value.trim();
    const level = document.getElementById('formationLevel').value;
    const language = document.getElementById('formationLanguage').value;
    const image = document.getElementById('formationImage').value.trim();
    
    // Validation
    if (!name || !category || !price || !duration || !description) {
        alert('Veuillez remplir tous les champs obligatoires (*).');
        return;
    }
    
    if (price < 0) {
        alert('Le prix ne peut pas être négatif.');
        return;
    }
    
    if (duration < 1) {
        alert('La durée doit être d\'au moins 1 heure.');
        return;
    }
    
    const newFormation = {
        id: generateId(formations),
        name: name,
        category: category,
        price: price,
        duration: duration,
        students: 0,
        status: document.getElementById('formationActive').checked ? 'active' : 'inactive',
        description: description,
        objectives: objectives || "Aucun objectif spécifié",
        level: level,
        language: language,
        image: image || "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400",
        certified: document.getElementById('formationCertified').checked,
        createdAt: new Date().toISOString().split('T')[0]
    };
    
    formations.push(newFormation);
    saveData();
    
    // Fermer le modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addFormationModal'));
    modal.hide();
    
    // Réinitialiser le formulaire
    document.getElementById('addFormationForm').reset();
    
    // Recharger les données
    loadDashboard();
    loadFormations();
    
    // Afficher un message de succès
    showNotification('Formation ajoutée avec succès !', 'success');
    
    console.log('Nouvelle formation ajoutée:', newFormation);
}

// Éditer une formation
function editFormation(id) {
    console.log('Édition de la formation ID:', id);
    
    const formation = formations.find(f => f.id === id);
    if (!formation) {
        alert('Formation non trouvée !');
        return;
    }
    
    // Remplir le formulaire d'édition
    document.getElementById('editFormationId').value = formation.id;
    document.getElementById('editFormationName').value = formation.name;
    document.getElementById('editFormationCategory').value = formation.category;
    document.getElementById('editFormationPrice').value = formation.price;
    document.getElementById('editFormationDuration').value = formation.duration;
    document.getElementById('editFormationDescription').value = formation.description;
    document.getElementById('editFormationObjectives').value = formation.objectives;
    document.getElementById('editFormationLevel').value = formation.level;
    document.getElementById('editFormationLanguage').value = formation.language;
    document.getElementById('editFormationImage').value = formation.image;
    document.getElementById('editFormationCertified').checked = formation.certified;
    document.getElementById('editFormationActive').checked = formation.status === 'active';
    
    const modal = new bootstrap.Modal(document.getElementById('editFormationModal'));
    modal.show();
}

// Mettre à jour une formation
function updateFormation() {
    const id = parseInt(document.getElementById('editFormationId').value);
    const formationIndex = formations.findIndex(f => f.id === id);
    
    if (formationIndex === -1) {
        alert('Formation non trouvée !');
        return;
    }
    
    // Validation
    const name = document.getElementById('editFormationName').value.trim();
    const category = document.getElementById('editFormationCategory').value;
    const price = parseFloat(document.getElementById('editFormationPrice').value);
    const duration = parseInt(document.getElementById('editFormationDuration').value);
    const description = document.getElementById('editFormationDescription').value.trim();
    
    if (!name || !category || !price || !duration || !description) {
        alert('Veuillez remplir tous les champs obligatoires.');
        return;
    }
    
    formations[formationIndex] = {
        ...formations[formationIndex],
        name: name,
        category: category,
        price: price,
        duration: duration,
        description: description,
        objectives: document.getElementById('editFormationObjectives').value.trim(),
        level: document.getElementById('editFormationLevel').value,
        language: document.getElementById('editFormationLanguage').value,
        image: document.getElementById('editFormationImage').value.trim(),
        certified: document.getElementById('editFormationCertified').checked,
        status: document.getElementById('editFormationActive').checked ? 'active' : 'inactive'
    };
    
    saveData();
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('editFormationModal'));
    modal.hide();
    
    loadDashboard();
    loadFormations();
    
    showNotification('Formation mise à jour avec succès !', 'success');
    console.log('Formation mise à jour:', formations[formationIndex]);
}

// Confirmer la suppression
function confirmDelete(id, name) {
    console.log('Confirmation de suppression pour ID:', id);
    
    document.getElementById('deleteFormationId').value = id;
    document.getElementById('deleteFormationName').textContent = name;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteFormationModal'));
    modal.show();
}

// Supprimer une formation
function deleteFormation() {
    const id = parseInt(document.getElementById('deleteFormationId').value);
    console.log('Suppression de la formation ID:', id);
    
    const initialLength = formations.length;
    formations = formations.filter(f => f.id !== id);
    
    if (formations.length === initialLength) {
        alert('Formation non trouvée !');
        return;
    }
    
    // Mettre à jour les inscriptions liées à cette formation
    inscriptions = inscriptions.filter(ins => ins.courseId !== id);
    
    saveData();
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteFormationModal'));
    modal.hide();
    
    loadDashboard();
    loadFormations();
    loadInscriptions();
    
    showNotification('Formation supprimée avec succès !', 'success');
    console.log('Formation supprimée. Total formations:', formations.length);
}

// Voir les détails d'une formation
function viewFormation(id) {
    const formation = formations.find(f => f.id === id);
    if (!formation) {
        alert('Formation non trouvée !');
        return;
    }
    
    let details = `
        <div class="formation-details">
            <div class="row">
                <div class="col-md-4">
                    ${formation.image ? `<img src="${formation.image}" class="img-fluid rounded mb-3">` : ''}
                </div>
                <div class="col-md-8">
                    <h4>${formation.name}</h4>
                    <p><strong>Catégorie:</strong> ${getCategoryName(formation.category)}</p>
                    <p><strong>Prix:</strong> ${formation.price}€</p>
                    <p><strong>Durée:</strong> ${formation.duration} heures</p>
                    <p><strong>Niveau:</strong> ${formation.level}</p>
                    <p><strong>Langue:</strong> ${formation.language === 'fr' ? 'Français' : formation.language === 'en' ? 'Anglais' : 'Espagnol'}</p>
                    <p><strong>Étudiants inscrits:</strong> ${formation.students}</p>
                    <p><strong>Statut:</strong> ${formation.status === 'active' ? 'Active' : 'Inactive'}</p>
                    <p><strong>Certifiée:</strong> ${formation.certified ? 'Oui' : 'Non'}</p>
                    <hr>
                    <p><strong>Description:</strong></p>
                    <p>${formation.description}</p>
                    ${formation.objectives ? `<p><strong>Objectifs:</strong><br>${formation.objectives}</p>` : ''}
                    <p><strong>Date de création:</strong> ${formation.createdAt}</p>
                </div>
            </div>
        </div>
    `;
    
    // Créer un modal pour afficher les détails
    const modalHTML = `
        <div class="modal fade" id="viewFormationModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Détails de la formation</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${details}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" onclick="editFormation(${id})">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Ajouter le modal au DOM
    let modalContainer = document.getElementById('modalContainer');
    if (!modalContainer) {
        modalContainer = document.createElement('div');
        modalContainer.id = 'modalContainer';
        document.body.appendChild(modalContainer);
    }
    modalContainer.innerHTML = modalHTML;
    
    const modal = new bootstrap.Modal(document.getElementById('viewFormationModal'));
    modal.show();
}

// Voir les détails d'une inscription
function viewInscription(id) {
    const inscription = inscriptions.find(ins => ins.id === id);
    if (!inscription) return;
    
    alert(`Détails de l'inscription:\n\n` +
          `Étudiant: ${inscription.student}\n` +
          `Email: ${inscription.email}\n` +
          `Téléphone: ${inscription.phone || 'Non renseigné'}\n` +
          `Formation: ${inscription.course}\n` +
          `Date: ${inscription.date}\n` +
          `Prix: ${inscription.price}€\n` +
          `Statut: ${inscription.status === 'paid' ? 'Payé' : 'En attente'}\n` +
          `Moyen de paiement: ${inscription.paymentMethod === 'carte' ? 'Carte bancaire' : 'PayPal'}`);
}

// Valider un paiement
function validatePayment(id) {
    const inscriptionIndex = inscriptions.findIndex(ins => ins.id === id);
    if (inscriptionIndex === -1) return;
    
    if (confirm('Confirmer la validation du paiement ?')) {
        inscriptions[inscriptionIndex].status = 'paid';
        
        // Mettre à jour le nombre d'étudiants dans la formation
        const courseId = inscriptions[inscriptionIndex].courseId;
        const formationIndex = formations.findIndex(f => f.id === courseId);
        if (formationIndex !== -1) {
            formations[formationIndex].students++;
        }
        
        saveData();
        loadDashboard();
        loadFormations();
        loadInscriptions();
        
        showNotification('Paiement validé avec succès !', 'success');
    }
}

// Rechercher des formations
function searchFormations() {
    const searchTerm = document.getElementById('searchFormation').value.toLowerCase();
    const category = document.getElementById('filterCategory').value;
    
    // Filtrer les formations
    let filteredFormations = formations;
    
    if (searchTerm) {
        filteredFormations = filteredFormations.filter(f => 
            f.name.toLowerCase().includes(searchTerm) || 
            f.description.toLowerCase().includes(searchTerm)
        );
    }
    
    if (category) {
        filteredFormations = filteredFormations.filter(f => f.category === category);
    }
    
    // Afficher les résultats
    const formationsBody = document.getElementById('formationsBody');
    if (!formationsBody) return;
    
    formationsBody.innerHTML = '';
    
    if (filteredFormations.length === 0) {
        formationsBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>Aucun résultat trouvé</h5>
                    <p class="text-muted">Essayez avec d'autres critères de recherche</p>
                </td>
            </tr>
        `;
        return;
    }
    
    filteredFormations.forEach(formation => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>#${formation.id}</td>
            <td>
                <strong>${formation.name}</strong>
                <br><small class="text-muted">${formation.description.substring(0, 50)}...</small>
            </td>
            <td><span class="badge-category badge-${formation.category}">${getCategoryName(formation.category)}</span></td>
            <td class="fw-bold">${formation.price}€</td>
            <td>${formation.duration}h</td>
            <td>
                <span class="badge bg-success">${formation.students}</span>
            </td>
            <td>
                <span class="badge ${formation.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                    ${formation.status === 'active' ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td>
                <span class="action-btn view-btn" onclick="viewFormation(${formation.id})">
                    <i class="fas fa-eye"></i>
                </span>
                <span class="action-btn edit-btn" onclick="editFormation(${formation.id})">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="action-btn delete-btn" onclick="confirmDelete(${formation.id}, '${formation.name}')">
                    <i class="fas fa-trash"></i>
                </span>
            </td>
        `;
        formationsBody.appendChild(row);
    });
}

// Exporter les formations
function exportFormations() {
    const data = formations.map(f => ({
        ID: f.id,
        Nom: f.name,
        Catégorie: getCategoryName(f.category),
        Prix: f.price + '€',
        Durée: f.duration + 'h',
        Étudiants: f.students,
        Statut: f.status === 'active' ? 'Active' : 'Inactive',
        Description: f.description,
        'Date création': f.createdAt
    }));
    
    // Créer un CSV
    const headers = Object.keys(data[0]);
    const csv = [
        headers.join(','),
        ...data.map(row => headers.map(header => `"${row[header]}"`).join(','))
    ].join('\n');
    
    // Télécharger le fichier
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `formations_oneworld_${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    
    showNotification('Export CSV terminé !', 'success');
}

// Éditer une catégorie
function editCategory(id) {
    const category = categories.find(c => c.id === id);
    if (!category) return;
    
    alert(`Édition de la catégorie "${category.name}"\n\nCette fonctionnalité sera disponible dans la prochaine version.`);
}

// Confirmer la suppression d'une catégorie
function confirmDeleteCategory(id, name) {
    if (confirm(`Supprimer la catégorie "${name}" ?\n\nAttention : Cette action ne peut être annulée.`)) {
        // Vérifier si des formations utilisent cette catégorie
        const categoryName = categories.find(c => c.id === id)?.name;
        const categoryCode = getCategoryCode(categoryName);
        const formationsUsingCategory = formations.filter(f => f.category === categoryCode);
        
        if (formationsUsingCategory.length > 0) {
            alert(`Impossible de supprimer cette catégorie. ${formationsUsingCategory.length} formation(s) l'utilisent encore.`);
            return;
        }
        
        categories = categories.filter(c => c.id !== id);
        saveData();
        loadDashboard();
        loadCategories();
        
        showNotification('Catégorie supprimée avec succès !', 'success');
    }
}

// Afficher une notification
function showNotification(message, type = 'info') {
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    notification.innerHTML = `
        <strong>${type === 'success' ? 'Succès' : type === 'error' ? 'Erreur' : 'Info'}:</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Initialiser au chargement
console.log('Script admin.js chargé avec succès !');