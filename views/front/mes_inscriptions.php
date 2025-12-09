<?php
// formation_apprentissage/views/front/mes_inscriptions.php
require_once __DIR__ . '/../template.php';
?>

<div class="content-header">
    <div>
        <h1 class="content-title">📋 Mes Inscriptions</h1>
        <p class="subtitle">Suivez l'état de vos inscriptions aux formations</p>
    </div>
    <a href="index.php?controller=formation&action=front_list" class="btn btn-primary">
        🔍 Voir toutes les formations
    </a>
</div>

<?php if (empty($inscriptions)): ?>
    <div class="empty-state">
        <div class="empty-icon">📭</div>
        <h3>Aucune inscription</h3>
        <p>Vous n'êtes inscrit à aucune formation pour le moment.</p>
        <a href="index.php?controller=formation&action=front_list" class="btn btn-primary">
            Parcourir les formations disponibles
        </a>
    </div>
<?php else: ?>
    <div class="inscriptions-grid">
        <?php foreach ($inscriptions as $inscription): ?>
            <div class="inscription-card">
                <div class="inscription-header" style="background: 
                    <?php 
                    switch($inscription['statut']) {
                        case 'confirme': echo 'linear-gradient(135deg, #28a745 0%, #20c997 100%)'; break;
                        case 'en_attente': echo 'linear-gradient(135deg, #ffc107 0%, #fd7e14 100%)'; break;
                        case 'refuse': echo 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)'; break;
                        case 'termine': echo 'linear-gradient(135deg, #6f42c1 0%, #6610f2 100%)'; break;
                        default: echo 'linear-gradient(135deg, #6c757d 0%, #495057 100%)';
                    }
                    ?>;">
                    
                    <div class="inscription-header-top">
                        <h3 class="inscription-title"><?php echo htmlspecialchars($inscription['titre']); ?></h3>
                        
                        <span class="status-badge">
                            <?php 
                            $statusLabels = [
                                'en_attente' => '⏳ En attente',
                                'confirme' => '✅ Confirmée',
                                'refuse' => '❌ Refusée',
                                'termine' => '🎓 Terminée'
                            ];
                            echo $statusLabels[$inscription['statut']] ?? $inscription['statut'];
                            ?>
                        </span>
                    </div>
                    
                    <div class="inscription-meta">
                        <div class="meta-item">
                            <span class="meta-icon">👨‍🏫</span>
                            <span><?php echo htmlspecialchars($inscription['formateur_nom']); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="inscription-body">
                    <div class="inscription-dates">
                        <div class="date-item">
                            <div class="date-label">Date d'inscription</div>
                            <div class="date-value">
                                <?php echo date('d/m/Y H:i', strtotime($inscription['date_inscription'])); ?>
                            </div>
                        </div>
                        
                        <div class="date-item">
                            <div class="date-label">Dates formation</div>
                            <div class="date-value">
                                <?php echo date('d/m/Y', strtotime($inscription['date_debut'])); ?>
                                <?php if ($inscription['date_fin']): ?>
                                    - <?php echo date('d/m/Y', strtotime($inscription['date_fin'])); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="progression-section">
                        <div class="progression-header">
                            <span>Progression</span>
                            <span class="progression-value"><?php echo $inscription['progression']; ?>%</span>
                        </div>
                        <div class="progression-bar">
                            <div class="progression-fill" style="width: <?php echo $inscription['progression']; ?>%;"></div>
                        </div>
                    </div>
                    
                    <?php if ($inscription['statut'] == 'en_attente'): ?>
                        <div class="status-message warning">
                            ⏳ Votre inscription est en attente de validation par l'administrateur.
                        </div>
                    <?php elseif ($inscription['statut'] == 'confirme'): ?>
                        <div class="status-message success">
                            ✅ Votre inscription est confirmée ! Préparez-vous pour la formation.
                        </div>
                    <?php elseif ($inscription['statut'] == 'termine'): ?>
                        <div class="status-message info">
                            🎓 Formation terminée.
                            <?php if ($inscription['note']): ?>
                                Note: <?php echo $inscription['note']; ?>/20
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="inscription-actions">
                        <a href="index.php?controller=formation&action=front_show&id=<?php echo $inscription['formation_id']; ?>" 
                           class="btn btn-sm btn-outline">
                            Voir la formation
                        </a>
                        
                        <?php if ($inscription['statut'] == 'en_attente'): ?>
                            <form method="POST" action="index.php?controller=inscription&action=cancel" 
                                  class="inline-form">
                                <input type="hidden" name="id" value="<?php echo $inscription['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Annuler cette inscription ?')">
                                    Annuler
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
.inscriptions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 25px;
    margin-top: 20px;
}

.inscription-card {
    border: 1px solid #e0e0e0;
    border-radius: 15px;
    overflow: hidden;
    background: white;
    transition: all 0.3s;
}

.inscription-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.inscription-header {
    color: white;
    padding: 25px;
}

.inscription-header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.inscription-title {
    margin: 0;
    font-size: 20px;
    flex: 1;
}

.status-badge {
    background: rgba(255,255,255,0.2);
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    white-space: nowrap;
    margin-left: 15px;
}

.inscription-meta {
    font-size: 14px;
    opacity: 0.9;
}

.inscription-body {
    padding: 25px;
}

.inscription-dates {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.date-item {
    padding: 10px;
    border-radius: 8px;
    background: #f8f9fa;
}

.date-label {
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
}

.date-value {
    font-weight: 600;
    color: #333;
}

.progression-section {
    margin: 20px 0;
}

.progression-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 14px;
    color: #666;
}

.progression-value {
    font-weight: 600;
    color: #333;
}

.progression-bar {
    background: #e9ecef;
    height: 10px;
    border-radius: 5px;
    overflow: hidden;
}

.progression-fill {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    height: 100%;
    transition: width 0.5s ease;
}

.status-message {
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
    font-size: 14px;
}

.status-message.warning {
    background: #fff3cd;
    color: #856404;
}

.status-message.success {
    background: #d4edda;
    color: #155724;
}

.status-message.info {
    background: #e2d9f3;
    color: #6f42c1;
}

.inscription-actions {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.btn-outline {
    flex: 1;
    background: #f8f9fa;
    color: #333;
    border: 1px solid #dee2e6;
}

.btn-outline:hover {
    background: #e9ecef;
}

.inline-form {
    display: inline;
}
</style>

<?php require_once __DIR__ . '/../template_footer.php'; ?>