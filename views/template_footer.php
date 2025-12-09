<?php
// formation_apprentissage/views/template_footer.php
?>
        </div> <!-- Fermeture du .content -->
    </div> <!-- Fermeture du .container -->
    
    <footer class="footer">
        <div class="footer-content">
            <p>© 2024 OneWorld - Formation & Apprentissage</p>
            <p class="footer-contact">
                📧 <a href="mailto:formation@oneworld.tn" style="color: white;">formation@oneworld.tn</a> 
                | 📞 +216 27 678 649
            </p>
        </div>
    </footer>
    
    <script>
    // Menu actif
    document.addEventListener('DOMContentLoaded', function() {
        const currentUrl = window.location.href;
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add('active');
            }
        });
        
        // Toast messages
        <?php if (isset($_GET['success']) || isset($_GET['error'])): ?>
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    setTimeout(() => {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-20px)';
                        setTimeout(() => alert.remove(), 300);
                    }, 3000);
                }
            }, 100);
        <?php endif; ?>
    });
    
    // Animations CSS
    const style = document.createElement('style');
    style.textContent = `
        .alert {
            transition: all 0.3s ease;
        }
        .formation-card {
            animation: fadeInUp 0.5s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
    </script>
</body>
</html>