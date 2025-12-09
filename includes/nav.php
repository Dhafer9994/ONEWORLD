<?php
// formation_apprentissage/includes/nav.php
$current_page = basename($_SERVER['PHP_SELF']);
$current_action = $_GET['action'] ?? 'front_list';
$current_controller = $_GET['controller'] ?? 'formation';
?>
<header class="site-header">
    <div class="container">
        <nav class="main-nav">
            <!-- Logo -->
            <div class="nav-brand">
                <a href="../index.php" class="logo-link">
                    <div class="logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="logo-text">
                        <span class="logo-title">OneWorld</span>
                        <span class="logo-subtitle">Formation</span>
                    </div>
                </a>
            </div>
            
            <!-- Menu mobile toggle -->
            <button class="mobile-menu-toggle" aria-label="Menu mobile" aria-expanded="false">
                <span class="hamburger"></span>
            </button>
            
            <!-- Navigation principale -->
            <div class="nav-menu">
                <ul class="nav-list">
                    <!-- Liens communs -->
                    <li class="nav-item">
                        <a href="../index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="index.php?controller=formation&action=front_list" 
                           class="nav-link <?php echo ($current_controller == 'formation' && $current_action == 'front_list') ? 'active' : ''; ?>">
                            <i class="fas fa-book"></i>
                            <span>Formations</span>
                        </a>
                    </li>
                    
                    <!-- Liens selon le rôle -->
                    <?php if (isset($_SESSION['admin_id'])): ?>
                        <!-- Admin -->
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle">
                                <i class="fas fa-user-shield"></i>
                                <span>Administration</span>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="index.php?controller=formation&action=admin_list" 
                                       class="dropdown-item <?php echo ($current_controller == 'formation' && $current_action == 'admin_list') ? 'active' : ''; ?>">
                                        <i class="fas fa-list"></i> Gestion Formations
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?controller=inscription&action=admin_list" 
                                       class="dropdown-item <?php echo ($current_controller == 'inscription' && $current_action == 'admin_list') ? 'active' : ''; ?>">
                                        <i class="fas fa-clipboard-list"></i> Gestion Inscriptions
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a href="../../backoffice/admin_dashboard.php" class="dropdown-item">
                                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                    <?php elseif (isset($_SESSION['refugie_id'])): ?>
                        <!-- Réfugié -->
                        <li class="nav-item">
                            <a href="index.php?controller=inscription&action=mes_inscriptions" 
                               class="nav-link <?php echo ($current_controller == 'inscription' && $current_action == 'mes_inscriptions') ? 'active' : ''; ?>">
                                <i class="fas fa-clipboard-check"></i>
                                <span>Mes inscriptions</span>
                            </a>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle">
                                <i class="fas fa-user"></i>
                                <span>Mon compte</span>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="../../frontoffice/dashboard.php" class="dropdown-item">
                                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                                    </a>
                                </li>
                                <li>
                                    <a href="../../frontoffice/mon_profil.php" class="dropdown-item">
                                        <i class="fas fa-id-card"></i> Mon profil
                                    </a>
                                </li>
                                <li>
                                    <a href="../../frontoffice/mes_documents.php" class="dropdown-item">
                                        <i class="fas fa-file-alt"></i> Mes documents
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a href="../../frontoffice/deconnexion.php" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                    <?php else: ?>
                        <!-- Visiteur non connecté -->
                        <li class="nav-item">
                            <a href="../../frontoffice/connexion.php" class="nav-link">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Connexion</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="../../frontoffice/inscription_form.php" class="nav-link btn-register">
                                <i class="fas fa-user-plus"></i>
                                <span>S'inscrire</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Liens supplémentaires -->
                    <li class="nav-item">
                        <a href="#" class="nav-link" id="themeToggle" title="Changer le thème">
                            <i class="fas fa-moon"></i>
                            <span class="theme-text">Sombre</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- User info (si connecté) -->
            <?php if (isset($_SESSION['refugie_id']) || isset($_SESSION['admin_id'])): ?>
                <div class="user-nav">
                    <div class="user-dropdown">
                        <button class="user-toggle" aria-label="Menu utilisateur">
                            <div class="user-avatar">
                                <?php 
                                $initials = '';
                                if (isset($_SESSION['refugie_nom'])) {
                                    $initials = substr($_SESSION['refugie_nom'], 0, 1) . substr($_SESSION['refugie_prenom'], 0, 1);
                                } elseif (isset($_SESSION['admin_nom'])) {
                                    $initials = substr($_SESSION['admin_nom'], 0, 1) . substr($_SESSION['admin_prenom'], 0, 1);
                                }
                                ?>
                                <span><?php echo strtoupper($initials); ?></span>
                            </div>
                            <div class="user-info">
                                <div class="user-name">
                                    <?php 
                                    if (isset($_SESSION['refugie_nom'])) {
                                        echo htmlspecialchars($_SESSION['refugie_prenom'] . ' ' . $_SESSION['refugie_nom']);
                                    } elseif (isset($_SESSION['admin_nom'])) {
                                        echo htmlspecialchars($_SESSION['admin_prenom'] . ' ' . $_SESSION['admin_nom']);
                                    }
                                    ?>
                                </div>
                                <div class="user-role">
                                    <?php echo isset($_SESSION['admin_id']) ? 'Administrateur' : 'Réfugié'; ?>
                                </div>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <div class="user-menu">
                            <div class="user-menu-header">
                                <div class="user-avatar large">
                                    <span><?php echo strtoupper($initials); ?></span>
                                </div>
                                <div>
                                    <div class="user-name"><?php 
                                        if (isset($_SESSION['refugie_nom'])) {
                                            echo htmlspecialchars($_SESSION['refugie_prenom'] . ' ' . $_SESSION['refugie_nom']);
                                        } elseif (isset($_SESSION['admin_nom'])) {
                                            echo htmlspecialchars($_SESSION['admin_prenom'] . ' ' . $_SESSION['admin_nom']);
                                        }
                                    ?></div>
                                    <div class="user-email">
                                        <?php 
                                        echo isset($_SESSION['refugie_email']) 
                                            ? htmlspecialchars($_SESSION['refugie_email'])
                                            : (isset($_SESSION['admin_email'])
                                                ? htmlspecialchars($_SESSION['admin_email'])
                                                : '');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="user-menu-links">
                                <?php if (isset($_SESSION['refugie_id'])): ?>
                                    <a href="../../frontoffice/mon_profil.php" class="user-menu-link">
                                        <i class="fas fa-user-edit"></i> Modifier mon profil
                                    </a>
                                    <a href="../../frontoffice/mes_demandes.php" class="user-menu-link">
                                        <i class="fas fa-question-circle"></i> Mes demandes
                                    </a>
                                    <a href="../../frontoffice/parametres.php" class="user-menu-link">
                                        <i class="fas fa-cog"></i> Paramètres
                                    </a>
                                <?php else: ?>
                                    <a href="../../backoffice/admin_dashboard.php" class="user-menu-link">
                                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                                    </a>
                                    <a href="../../backoffice/utilisateurs.php" class="user-menu-link">
                                        <i class="fas fa-users"></i> Gestion utilisateurs
                                    </a>
                                    <a href="../../backoffice/statistiques.php" class="user-menu-link">
                                        <i class="fas fa-chart-bar"></i> Statistiques
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="user-menu-footer">
                                <a href="../../frontoffice/deconnexion.php" class="btn-logout">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </nav>
    </div>
</header>

<!-- Styles navigation -->
<style>
.site-header {
    background: white;
    box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.main-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 0;
}

.nav-brand {
    display: flex;
    align-items: center;
}

.logo-link {
    display: flex;
    align-items: center;
    text-decoration: none;
    gap: 15px;
}

.logo-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.logo-text {
    display: flex;
    flex-direction: column;
}

.logo-title {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    line-height: 1;
}

.logo-subtitle {
    font-size: 14px;
    color: #667eea;
    font-weight: 500;
}

.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px;
}

.hamburger {
    display: block;
    width: 25px;
    height: 3px;
    background: #333;
    position: relative;
    transition: background 0.3s;
}

.hamburger::before,
.hamburger::after {
    content: '';
    position: absolute;
    width: 25px;
    height: 3px;
    background: #333;
    left: 0;
    transition: transform 0.3s;
}

.hamburger::before {
    top: -8px;
}

.hamburger::after {
    bottom: -8px;
}

.nav-menu {
    flex: 1;
    margin: 0 30px;
}

.nav-list {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 5px;
}

.nav-item {
    position: relative;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    color: #555;
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.3s;
    font-weight: 500;
}

.nav-link:hover {
    background: #f0f0f0;
    color: #667eea;
}

.nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.nav-link i {
    font-size: 18px;
}

.btn-register {
    background: linear-gradient(135deg, #48aea7 0%, #6aa785 100%);
    color: white !important;
}

.btn-register:hover {
    background: linear-gradient(135deg, #3a8c87 0%, #55866b 100%);
}

.dropdown-toggle {
    position: relative;
    padding-right: 40px;
}

.dropdown-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
    transition: transform 0.3s;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    min-width: 200px;
    background: white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-radius: 10px;
    padding: 10px 0;
    margin-top: 10px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s;
    z-index: 1000;
}

.dropdown:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    color: #555;
    text-decoration: none;
    transition: all 0.3s;
}

.dropdown-item:hover {
    background: #f8f9fa;
    color: #667eea;
}

.dropdown-item.active {
    background: #f0f0f0;
    color: #667eea;
    font-weight: 500;
}

.dropdown-item i {
    width: 20px;
    text-align: center;
}

.dropdown-divider {
    height: 1px;
    background: #e0e0e0;
    margin: 10px 0;
}

.user-nav {
    margin-left: 20px;
}

.user-dropdown {
    position: relative;
}

.user-toggle {
    display: flex;
    align-items: center;
    gap: 12px;
    background: none;
    border: none;
    padding: 8px 12px;
    border-radius: 50px;
    cursor: pointer;
    transition: background 0.3s;
}

.user-toggle:hover {
    background: #f8f9fa;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 16px;
}

.user-avatar.large {
    width: 60px;
    height: 60px;
    font-size: 24px;
}

.user-info {
    text-align: left;
}

.user-name {
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.user-role {
    font-size: 12px;
    color: #666;
}

.user-menu {
    position: absolute;
    top: 100%;
    right: 0;
    width: 300px;
    background: white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-radius: 15px;
    padding: 20px;
    margin-top: 10px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s;
    z-index: 1000;
}

.user-dropdown:hover .user-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.user-menu-header {
    display: flex;
    align-items: center;
    gap: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
    margin-bottom: 15px;
}

.user-email {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.user-menu-links {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 15px;
}

.user-menu-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    color: #555;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s;
    font-size: 14px;
}

.user-menu-link:hover {
    background: #f8f9fa;
    color: #667eea;
}

.user-menu-link i {
    width: 20px;
    text-align: center;
}

.user-menu-footer {
    padding-top: 15px;
    border-top: 1px solid #e0e0e0;
}

.btn-logout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 12px;
    background: #f8d7da;
    color: #721c24;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    text-decoration: none;
}

.btn-logout:hover {
    background: #f5c6cb;
}

#themeToggle {
    cursor: pointer;
}

@media (max-width: 992px) {
    .mobile-menu-toggle {
        display: block;
    }
    
    .nav-menu {
        position: fixed;
        top: 80px;
        left: 0;
        width: 100%;
        background: white;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transform: translateY(-100%);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
        z-index: 999;
    }
    
    .nav-menu.active {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }
    
    .nav-list {
        flex-direction: column;
        gap: 10px;
    }
    
    .dropdown-menu {
        position: static;
        box-shadow: none;
        margin: 10px 0 0 20px;
        padding: 0;
        opacity: 1;
        visibility: visible;
        transform: none;
        display: none;
    }
    
    .dropdown.active .dropdown-menu {
        display: block;
    }
    
    .user-menu {
        position: fixed;
        top: auto;
        bottom: 0;
        left: 0;
        width: 100%;
        border-radius: 20px 20px 0 0;
        max-height: 80vh;
        overflow-y: auto;
    }
}
</style>

<script>
// Gestion du menu mobile
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            navMenu.classList.toggle('active');
            
            // Animation hamburger
            this.querySelector('.hamburger').style.background = expanded ? '#333' : 'transparent';
            this.querySelector('.hamburger').style.transform = expanded ? 'rotate(0)' : 'rotate(45deg)';
            this.querySelector('.hamburger::before').style.transform = expanded ? 'translateY(0)' : 'translateY(8px) rotate(45deg)';
            this.querySelector('.hamburger::after').style.transform = expanded ? 'translateY(0)' : 'translateY(-8px) rotate(-45deg)';
        });
    }
    
    // Gestion des dropdowns sur mobile
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            if (window.innerWidth <= 992) {
                e.preventDefault();
                const dropdown = this.closest('.dropdown');
                dropdown.classList.toggle('active');
            }
        });
    });
    
    // Gestion du thème
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            const isDark = document.body.classList.toggle('dark-theme');
            this.querySelector('.theme-text').textContent = isDark ? 'Clair' : 'Sombre';
            this.querySelector('i').className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            
            // Sauvegarder dans localStorage
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
        
        // Charger le thème sauvegardé
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-theme');
            themeToggle.querySelector('.theme-text').textContent = 'Clair';
            themeToggle.querySelector('i').className = 'fas fa-sun';
        }
    }
    
    // Styles pour le thème sombre
    if (!document.querySelector('#dark-theme-styles')) {
        const style = document.createElement('style');
        style.id = 'dark-theme-styles';
        style.textContent = `
            .dark-theme {
                background: #1a1a1a;
                color: #e0e0e0;
            }
            
            .dark-theme .site-header {
                background: #2d2d2d;
                box-shadow: 0 2px 20px rgba(0,0,0,0.3);
            }
            
            .dark-theme .nav-link {
                color: #b0b0b0;
            }
            
            .dark-theme .nav-link:hover {
                background: #3a3a3a;
                color: #667eea;
            }
            
            .dark-theme .user-toggle {
                color: #e0e0e0;
            }
            
            .dark-theme .user-toggle:hover {
                background: #3a3a3a;
            }
        `;
        document.head.appendChild(style);
    }
});
</script>