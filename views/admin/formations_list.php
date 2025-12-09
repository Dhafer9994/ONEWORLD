<?php
// formation_apprentissage/views/admin/formations_list.php
require_once __DIR__ . '/../template.php';
?>

<div class="content-header">
    <div>
        <h1 class="content-title">👨‍🏫 Gestion des Formations</h1>
        <p class="subtitle">Créez et gérez les formations disponibles</p>
    </div>
    <a href="index.php?controller=formation&action=admin_create" class="btn btn-primary">
        + Nouvelle formation
    </a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Formateur</th>
                <th>Dates</th>
                <th>Places</th>
                <th>Inscriptions</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($formations)): ?>
                <tr>
                    <td colspan="8" class="text-center">
                        <div class="empty-table">
                            📭 Aucune formation créée pour le moment.
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($formations as $formation): ?>
                    <?php
                    $inscriptionsCount = $formation['inscriptions_count'] ?? 0;
                    $placesDisponibles = $formation['places_max'] - $inscriptionsCount;
                    $pourcentage = $formation['places_max'] > 0 
                        ? round(($inscriptionsCount / $formation['places_max']) * 100) 
                        : 0;
                    ?>
                    <tr>
                        <td><?php echo $formation['id']; ?></td>
                        <td>
                            <div class="formation-title-cell">
                                <strong><?php echo htmlspecialchars($formation['titre']); ?></strong>
                                <small><?php echo htmlspecialchars($formation['niveau']); ?></small>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($formation['formateur_nom']); ?></td>
                        <td>
                            <div class="dates-cell">
                                <div><?php echo date('d/m/Y', strtotime($formation['date_debut'])); ?></div>
                                <?php if ($formation['date_fin']): ?>
                                    <div><?php echo date('d/m/Y', strtotime($formation['date_fin'])); ?></div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="places-cell">
                                <div class="places-total"><?php echo $formation['places_max']; ?></div>
                                <div class="places-label">max</div>
                            </div>
                        </td>
                        <td>
                            <div class="inscriptions-cell">
                                <div class="inscriptions-count">
                                    <span class="count <?php echo $pourcentage >= 90 ? 'text-danger' : 
                                                           ($pourcentage >= 70 ? 'text-warning' : 'text-success'); ?>">
                                        <?php echo $inscriptionsCount; ?>
                                    </span>
                                    / <?php echo $formation['places_max']; ?>
                                </div>
                                <div class="inscriptions-bar">
                                    <div class="bar-fill" style="width: <?php echo $pourcentage; ?>%;
                                        background: <?php echo $pourcentage >= 90 ? '#dc3545' : 
                                                      ($pourcentage >= 70 ? '#ffc107' : '#28a745'); ?>;">
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge <?php 
                                echo $formation['statut'] == 'actif' ? 'badge-success' : 
                                     ($formation['statut'] == 'complet' ? 'badge-warning' : 'badge-danger');
                            ?>">
                                <?php echo ucfirst($formation['statut']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="index.php?controller=formation&action=front_show&id=<?php echo $formation['id']; ?>" 
                                   class="btn-action view" title="Voir">
                                    👁️
                                </a>
                                
                                <a href="index.php?controller=formation&action=admin_edit&id=<?php echo $formation['id']; ?>" 
                                   class="btn-action edit" title="Modifier">
                                    ✏️
                                </a>
                                
                                <a href="index.php?controller=formation&action=admin_delete&id=<?php echo $formation['id']; ?>" 
                                   class="btn-action delete" title="Supprimer"
                                   onclick="return confirm('Supprimer cette formation ? Cette action est irréversible.')">
                                    🗑️
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="stats-section">
    <div class="stats-content">
        <h4>📊 Statistiques</h4>
        <div class="stats-grid">
            <?php 
            $totalFormations = count($formations);
            $totalInscriptions = array_sum(array_column($formations, 'inscriptions_count'));
            $moyenneInscriptions = $totalFormations > 0 ? round($totalInscriptions / $totalFormations, 1) : 0;
            ?>
            <div class="stat-item">
                <div class="stat-value"><?php echo $totalFormations; ?></div>
                <div class="stat-label">Formations</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-value"><?php echo $totalInscriptions; ?></div>
                <div class="stat-label">Inscriptions</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-value"><?php echo $moyenneInscriptions; ?></div>
                <div class="stat-label">Moyenne/formation</div>
            </div>
        </div>
    </div>
    
    <div class="stats-actions">
        <a href="index.php?controller=inscription&action=admin_list" class="btn btn-primary">
            📋 Gérer les inscriptions
        </a>
    </div>
</div>

<style>
.text-center {
    text-align: center;
}

.empty-table {
    padding: 40px;
    color: #666;
    font-size: 16px;
}

.formation-title-cell {
    display: flex;
    flex-direction: column;
}

.formation-title-cell small {
    color: #666;
    font-size: 12px;
    margin-top: 5px;
}

.dates-cell {
    display: flex;
    flex-direction: column;
    font-size: 14px;
}

.places-cell {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.places-total {
    font-weight: 600;
    font-size: 18px;
}

.places-label {
    font-size: 12px;
    color: #666;
}

.inscriptions-cell {
    min-width: 100px;
}

.inscriptions-count {
    margin-bottom: 5px;
    font-size: 14px;
}

.count {
    font-weight: 600;
}

.text-danger { color: #dc3545; }
.text-warning { color: #ffc107; }
.text-success { color: #28a745; }

.inscriptions-bar {
    background: #e9ecef;
    height: 6px;
    border-radius: 3px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    transition: width 0.3s;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-action.view {
    background: #17a2b8;
    color: white;
}

.btn-action.edit {
    background: #ffc107;
    color: #333;
}

.btn-action.delete {
    background: #dc3545;
    color: white;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.stats-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.stats-content h4 {
    margin: 0 0 15px 0;
    color: #333;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 28px;
    font-weight: 600;
    color: #667eea;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    color: #666;
}
</style>

<?php require_once __DIR__ . '/../template_footer.php'; ?>