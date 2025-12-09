<?php
// formation_apprentissage/views/admin/formation_form.php
$isEdit = isset($formation) && $formation;
$title = $isEdit ? 'Modifier la formation' : 'Créer une nouvelle formation';
$action = $isEdit ? "index.php?controller=formation&action=admin_edit&id=" . ($formation['id'] ?? '') : "index.php?controller=formation&action=admin_create";

require_once __DIR__ . '/../template.php';
?>

<div class="content-header">
    <h1 class="content-title"><?php echo $isEdit ? '✏️ Modifier' : '➕ Créer'; ?> une formation</h1>
    <a href="index.php?controller=formation&action=admin_list" class="btn btn-secondary">
        ← Retour à la liste
    </a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <h4 style="margin-top: 0;">Erreurs de validation :</h4>
        <ul style="margin: 10px 0 0 20px;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo $action; ?>" id="formationForm" class="form-container">
    <div class="form-section">
        <h3 class="form-section-title">📝 Informations générales</h3>
        
        <div class="form-group">
            <label class="form-label" for="titre">Titre de la formation *</label>
            <input type="text" 
                   class="form-control" 
                   id="titre" 
                   name="titre" 
                   value="<?php echo htmlspecialchars($formation['titre'] ?? ''); ?>"
                   placeholder="Ex: Développement Web pour débutants"
                   required>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="description">Description *</label>
            <textarea class="form-control" 
                      id="description" 
                      name="description" 
                      rows="6"
                      placeholder="Décrivez en détail le contenu de la formation, les objectifs, les prérequis..."
                      required><?php echo htmlspecialchars($formation['description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="duree">Durée</label>
                <input type="text" 
                       class="form-control" 
                       id="duree" 
                       name="duree" 
                       value="<?php echo htmlspecialchars($formation['duree'] ?? '40 heures'); ?>"
                       placeholder="Ex: 40 heures, 2 semaines...">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="niveau">Niveau</label>
                <select class="form-control" id="niveau" name="niveau">
                    <option value="débutant" <?php echo ($formation['niveau'] ?? 'débutant') == 'débutant' ? 'selected' : ''; ?>>Débutant</option>
                    <option value="intermédiaire" <?php echo ($formation['niveau'] ?? '') == 'intermédiaire' ? 'selected' : ''; ?>>Intermédiaire</option>
                    <option value="avancé" <?php echo ($formation['niveau'] ?? '') == 'avancé' ? 'selected' : ''; ?>>Avancé</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="form-section">
        <h3 class="form-section-title">💰 Tarification et capacité</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="prix">Prix (€)</label>
                <input type="number" 
                       class="form-control" 
                       id="prix" 
                       name="prix" 
                       value="<?php echo htmlspecialchars($formation['prix'] ?? 0); ?>"
                       min="0" 
                       step="0.01"
                       placeholder="0 pour gratuit">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="places_max">Places maximum *</label>
                <input type="number" 
                       class="form-control" 
                       id="places_max" 
                       name="places_max" 
                       value="<?php echo htmlspecialchars($formation['places_max'] ?? 20); ?>"
                       min="1" 
                       required>
            </div>
        </div>
    </div>
    
    <div class="form-section">
        <h3 class="form-section-title">📅 Planning</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="date_debut">Date de début *</label>
                <input type="date" 
                       class="form-control" 
                       id="date_debut" 
                       name="date_debut" 
                       value="<?php echo htmlspecialchars($formation['date_debut'] ?? date('Y-m-d')); ?>"
                       required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="date_fin">Date de fin (optionnel)</label>
                <input type="date" 
                       class="form-control" 
                       id="date_fin" 
                       name="date_fin" 
                       value="<?php echo htmlspecialchars($formation['date_fin'] ?? ''); ?>">
            </div>
        </div>
    </div>
    
    <div class="form-section">
        <h3 class="form-section-title">⚙️ Paramètres</h3>
        
        <div class="form-group">
            <label class="form-label" for="statut">Statut</label>
            <select class="form-control" id="statut" name="statut">
                <option value="actif" <?php echo ($formation['statut'] ?? 'actif') == 'actif' ? 'selected' : ''; ?>>Actif</option>
                <option value="inactif" <?php echo ($formation['statut'] ?? '') == 'inactif' ? 'selected' : ''; ?>>Inactif</option>
                <option value="complet" <?php echo ($formation['statut'] ?? '') == 'complet' ? 'selected' : ''; ?>>Complet</option>
            </select>
        </div>
    </div>
    
    <div class="form-actions">
        <a href="index.php?controller=formation&action=admin_list" class="btn btn-secondary">
            Annuler
        </a>
        <button type="submit" class="btn btn-primary">
            <?php echo $isEdit ? '💾 Mettre à jour' : '✅ Créer la formation'; ?>
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formationForm');
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    const placesMax = document.getElementById('places_max');
    
    // Définir min date à aujourd'hui
    const today = new Date().toISOString().split('T')[0];
    dateDebut.min = today;
    
    // Mettre à jour min de date_fin quand date_debut change
    dateDebut.addEventListener('change', function() {
        dateFin.min = this.value;
        if (dateFin.value && dateFin.value < this.value) {
            dateFin.value = this.value;
        }
    });
    
    // Limiter les places max
    placesMax.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value > 100) {
            this.value = 100;
            alert("Le nombre maximum de places est limité à 100");
        }
        if (value < 1) {
            this.value = 1;
        }
    });
    
    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        const titre = document.getElementById('titre').value.trim();
        const description = document.getElementById('description').value.trim();
        
        let errors = [];
        
        if (titre.length < 5) {
            errors.push("Le titre doit contenir au moins 5 caractères");
        }
        
        if (description.length < 20) {
            errors.push("La description doit contenir au moins 20 caractères");
        }
        
        if (errors.length > 0) {
            e.preventDefault();
            alert("Erreurs de validation :\n\n" + errors.join("\n"));
        }
    });
});
</script>

<style>
.form-container {
    max-width: 800px;
    margin: 0 auto;
}

.form-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 2px solid #f0f0f0;
}

.form-section:last-child {
    border-bottom: none;
}

.form-section-title {
    color: #333;
    margin-bottom: 25px;
    font-size: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 40px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}
</style>

<?php require_once __DIR__ . '/../template_footer.php'; ?>