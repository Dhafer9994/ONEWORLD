<?php
// formation_apprentissage/includes/footer.php
?>
            </div> <!-- Fermeture du .container -->
        </main> <!-- Fermeture du main-content -->
        
        <!-- Footer -->
        <footer class="site-footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-section">
                        <h3 class="footer-title">📚 Formation & Apprentissage</h3>
                        <p>Une plateforme dédiée à l'intégration professionnelle et sociale des réfugiés.</p>
                        <div class="social-links">
                            <a href="#" class="social-link" aria-label="Facebook">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="LinkedIn">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="footer-section">
                        <h4 class="footer-subtitle">Liens rapides</h4>
                        <ul class="footer-links">
                            <li><a href="../index.php">Accueil</a></li>
                            <li><a href="index.php?controller=formation&action=front_list">Formations</a></li>
                            <li><a href="../../frontoffice/connexion.php">Connexion</a></li>
                            <li><a href="../../frontoffice/inscription_form.php">Inscription</a></li>
                            <li><a href="../../backoffice/admin_login.php">Administration</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h4 class="footer-subtitle">Contact</h4>
                        <ul class="contact-info">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>123 Rue de la Formation, Tunis 1000</span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span>+216 27 678 649</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span>formation@oneworld.tn</span>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Lun - Ven: 9h - 17h</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h4 class="footer-subtitle">Newsletter</h4>
                        <p>Inscrivez-vous pour recevoir les dernières formations.</p>
                        <form class="newsletter-form" id="newsletterForm">
                            <input type="email" 
                                   class="newsletter-input" 
                                   placeholder="Votre email" 
                                   required
                                   aria-label="Email pour newsletter">
                            <button type="submit" class="newsletter-btn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <div class="copyright">
                        &copy; <?php echo date('Y'); ?> OneWorld - Formation & Apprentissage. Tous droits réservés.
                    </div>
                    <div class="footer-bottom-links">
                        <a href="privacy.php">Politique de confidentialité</a>
                        <a href="terms.php">Conditions d'utilisation</a>
                        <a href="sitemap.php">Plan du site</a>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Back to top button -->
        <button id="backToTop" class="back-to-top" aria-label="Retour en haut">
            <i class="fas fa-chevron-up"></i>
        </button>
    </div> <!-- Fermeture du .page-wrapper -->
    
    <!-- Scripts JavaScript -->
    <script src="../assets/js/validation.js"></script>
    <script src="../assets/js/main.js"></script>
    
    <!-- Scripts additionnels selon la page -->
    <?php if (isset($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
            <script src="<?php echo htmlspecialchars($script); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Scripts inline -->
    <script>
        // Gestion du back to top
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });
        
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Gestion de la newsletter
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            fetch('../includes/newsletter_subscribe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('success', 'Merci pour votre inscription à la newsletter !');
                    this.reset();
                } else {
                    showMessage('error', data.message || 'Erreur lors de l\'inscription');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showMessage('error', 'Erreur de connexion');
            });
        });
        
        // Gestion des sessions expirées
        setInterval(() => {
            fetch('../includes/session_check.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.valid) {
                        showMessage('warning', 'Votre session va expirer. Veuillez rafraîchir la page.', 10000);
                    }
                });
        }, 300000); // Vérifier toutes les 5 minutes
        
        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter une classe au body pour les animations
            document.body.classList.add('loaded');
            
            // Gestion des formulaires avec confirmation
            document.querySelectorAll('form[data-confirm]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const message = this.getAttribute('data-confirm');
                    if (message && !confirm(message)) {
                        e.preventDefault();
                    }
                });
            });
            
            // Mettre en surbrillance les champs invalides
            document.querySelectorAll('.form-control:invalid').forEach(input => {
                input.addEventListener('blur', function() {
                    this.classList.add('touched');
                });
            });
        });
        
        // Fonction pour formater les dates
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
        
        // Fonction pour télécharger des fichiers
        function downloadFile(url, filename) {
            const a = document.createElement('a');
            a.href = url;
            a.download = filename || url.split('/').pop();
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script>
    
    <!-- Google Analytics (exemple) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-XXXXX-Y"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-XXXXX-Y');
    </script>
    
    <!-- Styles footer -->
    <style>
        .site-footer {
            background: linear-gradient(135deg, #2c3e50 0%, #4a6491 100%);
            color: white;
            padding: 60px 0 30px;
            margin-top: 50px;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: white;
        }
        
        .footer-subtitle {
            font-size: 18px;
            margin-bottom: 20px;
            color: white;
            border-bottom: 2px solid rgba(255,255,255,0.1);
            padding-bottom: 10px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s, padding-left 0.3s;
            display: inline-block;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .contact-info {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .contact-info li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            color: rgba(255,255,255,0.8);
        }
        
        .contact-info i {
            margin-right: 10px;
            margin-top: 5px;
            color: #667eea;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            transition: all 0.3s;
        }
        
        .social-link:hover {
            background: #667eea;
            transform: translateY(-3px);
        }
        
        .newsletter-form {
            display: flex;
            margin-top: 15px;
        }
        
        .newsletter-input {
            flex: 1;
            padding: 12px 15px;
            border: none;
            border-radius: 5px 0 0 5px;
            font-size: 14px;
        }
        
        .newsletter-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .newsletter-btn:hover {
            background: #764ba2;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .copyright {
            color: rgba(255,255,255,0.6);
            font-size: 14px;
        }
        
        .footer-bottom-links {
            display: flex;
            gap: 20px;
        }
        
        .footer-bottom-links a {
            color: rgba(255,255,255,0.6);
            font-size: 14px;
            text-decoration: none;
        }
        
        .footer-bottom-links a:hover {
            color: white;
        }
        
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .back-to-top:hover {
            background: #764ba2;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #667eea;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            z-index: 1001;
            transition: top 0.3s;
        }
        
        .skip-link:focus {
            top: 0;
        }
        
        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .footer-bottom-links {
                justify-content: center;
            }
            
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
            }
        }
    </style>
</body>
</html>
<?php
// Vider le buffer et envoyer le contenu
ob_end_flush();
?>