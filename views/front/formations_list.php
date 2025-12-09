<?php
// formation_apprentissage/views/front/formations_list.php
require_once __DIR__ . '/../template.php';
?>

<div class="content-header">
    <div>
        <h1 class="content-title">📚 Formations Disponibles</h1>
        <p class="subtitle">Développez vos compétences grâce à nos formations adaptées</p>
    </div>
    <?php if (isset($_SESSION['refugie_id'])): ?>
        <a href="index.php?controller=inscription&action=mes_inscriptions" class="btn btn-primary">
            📋 Mes inscriptions
        </a>
    <?php endif; ?>
</div>

<?php if (empty($formations)): ?>
    <div class="empty-state">
        <div class="empty-icon">📭</div>
        <h3>Aucune formation disponible</h3>
        <p>Aucune formation n'est disponible pour le moment. Revenez plus tard !</p>
    </div>
<?php else: ?>
    <div class="formations-grid">
        <?php foreach ($formations as $formation): ?>
            <?php
            $placesDisponibles = $formation['places_max'] - ($formation['inscriptions_count'] ?? 0);
            $isComplet = $placesDisponibles <= 0;
            ?>
            
            <div class="formation-card">
                <div class="formation-header">
                    <div class="formation-header-top">
                        <h3 class="formation-title"><?php echo htmlspecialchars($formation['titre']); ?></h3>
                        <span class="badge <?php echo $isComplet ? 'badge-danger' : 'badge-success'; ?>">
                            <?php echo $isComplet ? 'Complet' : $placesDisponibles . ' places'; ?>
                        </span>
                    </div>
                    
                    <div class="formation-meta">
                        <div class="meta-item">
                            <span class="meta-icon">👨‍🏫</span>
                            <span><?php echo htmlspecialchars($formation['formateur_nom']); ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">📅</span>
                            <span><?php echo date('d/m/Y', strtotime($formation['date_debut'])); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="formation-body">
                    <p class="formation-description">
                        <?php 
                        $description = htmlspecialchars($formation['description']);
                        echo strlen($description) > 150 
                            ? substr($description, 0, 150) . '...' 
                            : $description;
                        ?>
                    </p>
                    
                    <div class="formation-info-grid">
                        <div class="info-item">
                            <div class="info-label">Durée</div>
                            <div class="info-value"><?php echo htmlspecialchars($formation['duree']); ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Niveau</div>
                            <div class="info-value">
                                <span class="badge <?php 
                                    echo $formation['niveau'] == 'débutant' ? 'badge-info' : 
                                         ($formation['niveau'] == 'intermédiaire' ? 'badge-warning' : 'badge-danger');
                                ?>">
                                    <?php echo htmlspecialchars($formation['niveau']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="formation-footer">
                        <div class="formation-price">
                            <?php echo $formation['prix'] > 0 ? $formation['prix'] . ' €' : 'Gratuit'; ?>
                        </div>
                        
                        <div class="formation-actions">
                            <a href="index.php?controller=formation&action=front_show&id=<?php echo $formation['id']; ?>" 
                               class="btn btn-primary btn-sm">
                                Voir détails
                            </a>
                            
                            <?php if ($isRefugie && !$isComplet): ?>
                                <form method="POST" 
                                      action="index.php?controller=inscription&action=subscribe" 
                                      class="inline-form"
                                      onsubmit="return confirm('Confirmer votre inscription à cette formation ?')">
                                    <input type="hidden" name="formation_id" value="<?php echo $formation['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        S'inscrire
                                    </button>
                                </form>
                            <?php elseif ($isComplet): ?>
                                <span class="badge badge-danger">Complet</span>
                            <?php elseif (!$isRefugie): ?>
                                <a href="../../frontoffice/connexion.php" class="btn btn-sm btn-secondary">
                                    Connectez-vous
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 80px;
    margin-bottom: 20px;
    opacity: 0.3;
}

.formations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-top: 20px;
}

.formation-header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.formation-meta {
    display: flex;
    gap: 20px;
    font-size: 14px;
    opacity: 0.9;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.formation-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin: 20px 0;
}

.info-item {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
}

.info-label {
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
}

.info-value {
    font-weight: 600;
    color: #333;
}

.formation-price {
    font-weight: 600;
    color: #333;
    font-size: 18px;
}

.formation-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.inline-form {
    display: inline;
}
</style>

<?php require_once __DIR__ . '/../template_footer.php'; ?>