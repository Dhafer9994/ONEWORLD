<?php
// formation_apprentissage/views/admin/inscriptions_list.php
require_once __DIR__ . '/../template.php';
?>

<div class="content-header">
    <div>
        <h1 class="content-title">📋 Gestion des Inscriptions</h1>
        <p class="subtitle">Validez et gérez les inscriptions aux formations</p>
    </div>
    <div class="header-actions">
        <a href="index.php?controller=formation&action=admin_list" class="btn btn-secondary">
            ← Retour aux formations
        </a>
    </div>
</div>

<div class="filters-section">
    <div class="filters">
        <a href="index.php?controller=inscription&action=admin_list&filter=all" 
           class="filter-btn <?php echo (!isset($_GET['filter']) || $_GET['filter'] == 'all') ? 'active' : ''; ?>">
            Toutes (<?php echo count($inscriptions); ?>)
        </a>
        <a href="index.php?controller=inscription&action=admin_list&filter=en_attente" 
           class="filter-btn <?php echo ($_GET['filter'] ?? '') == 'en_attente' ? 'active' : ''; ?>">
            ⏳ En attente (<?php echo count(array_filter($inscriptions, fn($i) => $i['statut'] == 'en_attente')); ?>)
        </a>
        <a href="index.php?controller=inscription&action=admin_list&filter=confirme" 
           class="filter-btn <?php echo ($_GET['filter'] ?? '') == 'confirme' ? 'active' : ''; ?>">
            ✅ Confirmées (<?php echo count(array_filter($inscriptions, fn($i) => $i['statut'] == 'confirme')); ?>)
        </a>
        <a href="index.php?controller=inscription&action=admin_list&filter=refuse" 
           class="filter-btn <?php echo ($_GET['filter'] ?? '') == 'refuse' ? 'active' : ''; ?>">
            ❌ Refusées (<?php echo count(array_filter($inscriptions, fn($i) => $i['statut'] == 'refuse')); ?>)
        </a>
        <a href="index.php?controller=inscription&action=admin_list&filter=termine" 
           class="filter-btn <?php echo ($_GET['filter'] ?? '') == 'termine' ? 'active' : ''; ?>">
            🎓 Terminées (<?php echo count(array_filter($inscriptions, fn($i) => $i['statut'] == 'termine')); ?>)
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Réfugié</th>
                <th>Formation</th>
                <th>Date inscription</th>
                <th>Progression</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Filtrer les inscriptions
            $filteredInscriptions = $inscriptions;
            if (isset($_GET['filter']) && $_GET['filter'] != 'all') {
                $filteredInscriptions = array_filter($inscriptions, fn($i) => $i['statut'] == $_GET['filter']);
            }
            ?>
            
            <?php if (empty($filteredInscriptions)): ?>
                <tr>
                    <td colspan="7" class="text-center">
                        <div class="empty-table">
                            📭 Aucune inscription trouvée.
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($filteredInscriptions as $inscription): ?>
                    <tr>
                        <td><?php echo $inscription['id']; ?></td>
                        <td>
                            <div class="user-cell">
                                <strong><?php echo htmlspecialchars($inscription['refugie_nom']); ?></strong>
                                <small>ID: <?php echo $inscription['refugie_id']; ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="formation-cell">
                                <strong><?php echo htmlspecialchars($inscription['formation_titre']); ?></strong>
                                <small>Formateur: <?php echo htmlspecialchars($inscription['formateur_nom']); ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="date-cell">
                                <div><?php echo date('d/m/Y', strtotime($inscription['date_inscription'])); ?></div>
                                <small><?php echo date('H:i', strtotime($inscription['date_inscription'])); ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="progression-cell">
                                <div class="progression-wrapper">
                                    <div class="progression-bar">
                                        <div class="progression-fill" style="width: <?php echo $inscription['progression']; ?>%;"></div>
                                    </div>
                                    <span class="progression-text"><?php echo $inscription['progression']; ?>%</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge <?php 
                                switch($inscription['statut']) {
                                    case 'confirme': echo 'badge-success'; break;
                                    case 'en_attente': echo 'badge-warning'; break;
                                    case 'refuse': echo 'badge-danger'; break;
                                    case 'termine': echo 'badge-info'; break;
                                    default: echo '';
                                }
                            ?>">
                                <?php echo ucfirst($inscription['statut']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="inscription-actions">
                                <?php if ($inscription['statut'] == 'en_attente'): ?>
                                    <form method="POST" action="index.php?controller=inscription&action=update_status" 
                                          class="inline-form">
                                        <input type="hidden" name="id" value="<?php echo $inscription['id']; ?>">
                                        <input type="hidden" name="statut" value="confirme">
                                        <button type="submit" class="btn-action confirm"
                                                onclick="return confirm('Confirmer cette inscription ?')">
                                            ✅
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="index.php?controller=inscription&action=update_status" 
                                          class="inline-form">
                                        <input type="hidden" name="id" value="<?php echo $inscription['id']; ?>">
                                        <input type="hidden" name="statut" value="refuse">
                                        <button type="submit" class="btn-action refuse"
                                                onclick="return confirm('Refuser cette inscription ?')">
                                            ❌
                                        </button>
                                    </form>
                                <?php elseif ($inscription['statut'] == 'confirme'): ?>
                                    <form method="POST" action="index.php?controller=inscription&action=update_status" 
                                          class="inline-form">
                                        <input type="hidden" name="id" value="<?php echo $inscription['id']; ?>">
                                        <input type="hidden" name="statut" value="termine">
                                        <button type="submit" class="btn-action complete"
                                                onclick="return confirm('Marquer comme terminée ?')">
                                            🎓
                                        </button>
                                    </form>
                                <?php endif; ?>
                                
                                <button class="btn-action progression"
                                        onclick="openProgressionModal(<?php echo $inscription['id']; ?>, <?php echo $inscription['progression']; ?>)">
                                    📈
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal pour la progression -->
<div id="progressionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Mettre à jour la progression</h3>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        
        <div class="modal-body">
            <div class="progression-slider-container">
                <label>Progression (%)</label>
                <input type="range" id="progressionSlider" min="0" max="100" step="1" 
                       class="slider">
                <div class="slider-values">
                    <span>0%</span>
                    <span id="progressionValue" class="slider-value">50%</span>
                    <span>100%</span>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button onclick="closeModal()" class="btn btn-secondary">
                Annuler
            </button>
            <button onclick="saveProgression()" class="btn btn-primary">
                💾 Enregistrer
            </button>
        </div>
    </div>
</div>

<script>
let currentInscriptionId = null;

function openProgressionModal(inscriptionId, currentProgression) {
    currentInscriptionId = inscriptionId;
    const slider = document.getElementById('progressionSlider');
    const value = document.getElementById('progressionValue');
    
    slider.value = currentProgression;
    value.textContent = currentProgression + '%';
    
    document.getElementById('progressionModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('progressionModal').style.display = 'none';
}

function saveProgression() {
    const progression = document.getElementById('progressionSlider').value;
    
    fetch('index.php?controller=inscription&action=update_progression', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + currentInscriptionId + '&progression=' + progression
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Progression mise à jour avec succès !');
            closeModal();
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour: ' + (data.error || ''));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur de connexion');
    });
}

// Mettre à jour l'affichage de la valeur du slider
document.getElementById('progressionSlider').addEventListener('input', function() {
    document.getElementById('progressionValue').textContent = this.value + '%';
});

// Fermer la modal en cliquant en dehors
window.onclick = function(event) {
    const modal = document.getElementById('progressionModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>

<style>
.header-actions {
    display: flex;
    gap: 10px;
}

.filters-section {
    margin-bottom: 25px;
}

.filters {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-btn {
    display: inline-block;
    padding: 8px 16px;
    background: #f8f9fa;
    color: #333;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
    transition: all 0.2s;
    border: 1px solid #dee2e6;
}

.filter-btn:hover {
    background: #e9ecef;
}

.filter-btn.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.user-cell, .formation-cell {
    display: flex;
    flex-direction: column;
}

.user-cell small, .formation-cell small {
    color: #666;
    font-size: 12px;
    margin-top: 5px;
}

.date-cell {
    display: flex;
    flex-direction: column;
    font-size: 14px;
}

.date-cell small {
    color: #666;
    font-size: 12px;
}

.progression-cell {
    min-width: 120px;
}

.progression-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}

.progression-bar {
    flex: 1;
    background: #e9ecef;
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
}

.progression-fill {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    height: 100%;
}

.progression-text {
    font-weight: 600;
    min-width: 40px;
    text-align: right;
}

.inscription-actions {
    display: flex;
    gap: 5px;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-action.confirm {
    background: #28a745;
    color: white;
}

.btn-action.refuse {
    background: #dc3545;
    color: white;
}

.btn-action.complete {
    background: #6f42c1;
    color: white;
}

.btn-action.progression {
    background: #17a2b8;
    color: white;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.inline-form {
    display: inline;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 15px;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.modal-header h3 {
    margin: 0;
    color: #333;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}

.modal-body {
    margin: 20px 0;
}

.progression-slider-container {
    text-align: center;
}

.progression-slider-container label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #333;
}

.slider {
    width: 100%;
    margin: 20px 0;
}

.slider-values {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.slider-value {
    font-size: 24px;
    font-weight: 600;
    color: #667eea;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}
</style>

<?php require_once __DIR__ . '/../template_footer.php'; ?>