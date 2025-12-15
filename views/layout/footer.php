<!-- Contact Section -->
<section id="contact" class="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center">
                    <h3>Nous Contacter</h3>
                    <p class="white-text">Une question ? Écrivez-nous.</p>
                </div>
            </div>
        </div>
    </div>
    <footer class="style-1">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <span class="copyright">Copyright &copy; One World Formations 2025</span>
                </div>
            </div>
        </div>
    </footer>
</section>

<!-- Scripts -->
<script src="<?= ASSETS_URL ?>/js/jquery-2.1.1.min.js"></script>
<script src="<?= ASSETS_URL ?>/asset/js/bootstrap.min.js"></script>
<script src="<?= ASSETS_URL ?>/js/jquery.easing.1.3.js"></script>
<script src="<?= ASSETS_URL ?>/js/classie.js"></script>
<script src="<?= ASSETS_URL ?>/js/count-to.js"></script>
<script src="<?= ASSETS_URL ?>/js/jquery.appear.js"></script>
<script src="<?= ASSETS_URL ?>/js/cbpAnimatedHeader.js"></script>
<script src="<?= ASSETS_URL ?>/js/owl.carousel.min.js"></script>
<script src="<?= ASSETS_URL ?>/js/jquery.fitvids.js"></script>
<script src="<?= ASSETS_URL ?>/js/styleswitcher.js"></script>
<script src="<?= ASSETS_URL ?>/js/script.js"></script>

<script>
    const KEY_REFUGIES = "oneWorldRefugies";

    // Open Modal and Pre-fill
    function openInscription(id, title) {
        document.getElementById('formationId').value = id;
        document.getElementById('formationTitleDisplay').textContent = title;

        // Reset View
        document.getElementById('inscriptionForm').style.display = 'block';
        document.getElementById('successMessage').style.display = 'none';
        document.getElementById('inscriptionForm').reset();

        $('#inscriptionModal').modal('show');
    }

    // Handle Submission
    document.getElementById("inscriptionForm").onsubmit = function (e) {
        e.preventDefault();

        const nom = document.getElementById("nom").value.trim();
        const pays = document.getElementById("pays").value.trim();
        const idRefugie = document.getElementById("idRefugie").value.trim();
        const contact = document.getElementById("contact").value.trim();
        const formationId = document.getElementById("formationId").value; // Hidden input

        if (!nom || !pays || !contact) {
            alert("Veuillez remplir tous les champs obligatoires");
            return;
        }

        // Temporary local storage save
        let refugies = JSON.parse(localStorage.getItem(KEY_REFUGIES) || "[]");
        refugies.push({
            id: Date.now(),
            nom: nom,
            pays: pays,
            idRefugie: idRefugie || null,
            contact: contact,
            formationId: formationId, // Store ID
            date: new Date().toLocaleString('fr-FR')
        });
        localStorage.setItem(KEY_REFUGIES, JSON.stringify(refugies));

        // SHOW SUCCESS UI
        document.getElementById('inscriptionForm').style.display = 'none';
        document.getElementById('successName').textContent = nom;
        document.getElementById('successMessage').style.display = 'block';
        // Modal stays open to show success
    };
</script>
</body>

</html>