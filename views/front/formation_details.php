<?php
// formation_apprentissage/views/front/formation_details.php
require_once __DIR__ . '/../template.php';
?>

<div class="formation-detail">
    <div class="formation-detail-header">
        <h1 class="formation-title"><?php echo htmlspecialchars($formation['titre']); ?></h1>
        
        <div class="formation-header-info">
            <div class="header-info-item">
                <div class="info-label">Formateur</div>
                <div class="info-value">👨‍🏫 <?php echo htmlspecialchars($formation['formateur_nom']); ?></div>
            </div>
            
            <div class="header-info-item">
                <div class="info-label">Statut</div>
                <div class="info-value">
                    <span class="badge <?php 
                        echo $formation['statut'] == 'actif' ? 'badge-success' : 
                             ($formation['statut'] == 'complet' ? 'badge-warning' : 'badge-danger');
                    ?>">
                        <?php echo ucfirst($formation['statut']); ?>
                    </span>
                </div>
            </div>
            
            <div class="header-info-item">
                <div class="info-label">Places</div>
                <div class="info-value"><?php echo $placesDisponibles . '/' . $formation['places_max']; ?></div>
            </div>
        </div>
    </div>
    
    <div class="formation-detail-grid">
        <div class="detail-item">
            <div class="detail-icon">📅</div>
            <div class="detail-content">
                <div class="detail-label">Date de début</div>
                <div class="detail-value"><?php echo date('d/m/Y', strtotime($formation['date_debut'])); ?></div>
            </div>
        </div>
        
        <div class="detail-item">
            <div class="detail-icon">📅</div>
            <div class="detail-content">
                <div class="detail-label">Date de fin</div>
                <div class="detail-value">
                    <?php echo $formation['date_fin'] 
                        ? date('d/m/Y', strtotime($formation['date_fin'])) 
                        : 'Non définie'; ?>
                </div>
            </div>
        </div>
        
        <div class="detail-item">
            <div class="detail-icon">⏱️</div>
            <div class="detail-content">
                <div class="detail-label">Durée</div>
                <div class="detail-value"><?php echo htmlspecialchars($formation['duree']); ?></div>
            </div>
        </div>
        
        <div class="detail-item">
            <div class="detail-icon">📊</div>
            <div class="detail-content">
                <div class="detail-label">Niveau</div>
                <div class="detail-value">
                    <span class="badge <?php 
                        echo $formation['niveau'] == 'débutant' ? 'badge-info' : 
                             ($formation['niveau'] == 'intermédiaire' ? 'badge-warning' : 'badge-danger');
                    ?>">
                        <?php echo htmlspecialchars($formation['niveau']); ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="detail-item">
            <div class="detail-icon">💰</div>
            <div class="detail-content">
                <div class="detail-label">Prix</div>
                <div class="detail-value price"><?php echo $formation['prix'] > 0 ? $formation['prix'] . ' €' : 'Gratuit'; ?></div>
            </div>
        </div>
        
        <div class="detail-item">
            <div class="detail-icon">👥</div>
            <div class="detail-content">
                <div class="detail-label">Places max</div>
                <div class="detail-value"><?php echo $formation['places_max']; ?> participants</div>
            </div>
        </div>
    </div>
    
    <div class="formation-description-section">
        <h2>📝 Description de la formation</h2>
        <div class="description-content">
            <?php echo nl2br(htmlspecialchars($formation['description'])); ?>
        </div>
    </div>
    
    <?php if ($isRefugie): ?>
        <div class="inscription-section">
            <h3>🎯 S'inscrire à cette formation</h3>
            
            <?php if ($placesDisponibles > 0): ?>
                <div class="places-info">
                    Il reste <strong><?php echo $placesDisponibles; ?></strong> places disponibles.
                </div>
                
                <form method="POST" action="index.php?controller=inscription&action=subscribe"
                      onsubmit="return confirm('Confirmer votre inscription à cette formation ?')">
                    <input type="hidden" name="formation_id" value="<?php echo $formation['id']; ?>">
                    
                    <div class="inscription-actions">
                        <button type="submit" class="btn btn-success btn-lg">
                            ✅ S'inscrire maintenant
                        </button>
                        
                        <a href="index.php?controller=formation&action=front_list" class="btn btn-secondary">
                            Voir d'autres formations
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-error">
                    <div class="alert-content">
                        <div class="alert-icon">⛔</div>
                        <div>
                            <h4>Formation complète</h4>
                            <p>Désolé, toutes les places pour cette formation sont déjà prises.</p>
                        </div>
                    </div>
                    <a href="index.php?controller=formation&action=front_list" class="btn btn-secondary">
                        Voir d'autres formations
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="login-required-section">
            <div class="login-content">
                <div class="login-icon">🔒</div>
                <div>
                    <h4>Connexion requise</h4>
                    <p>Vous devez être connecté en tant que réfugié pour vous inscrire à cette formation.</p>
                </div>
            </div>
            
            <div class="login-actions">
                <a href="../../frontoffice/connexion.php" class="btn btn-primary">
                    Se connecter
                </a>
                <a href="../../frontoffice/inscription_form.php" class="btn btn-secondary">
                    Créer un compte
                </a>
                <a href="index.php?controller=formation&action=front_list" class="btn">
                    Voir toutes les formations
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.formation-detail-header {
    background: linear-gradient(135deg, #48aea7 0%, #6aa785 100%);
    color: white;
    padding: 40px;
    border-radius: 15px;
    margin-bottom: 30px;
}

.formation-title {
    color: white;
    margin-bottom: 15px;
    font-size: 32px;
}

.formation-header-info {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.header-info-item {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 14px;
    opacity: 0.8;
    margin-bottom: 5px;
}

.info-value {
    font-size: 18px;
    font-weight: 600;
}

.formation-detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
    margin: 25px 0;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 15px;
}

.detail-icon {
    font-size: 24px;
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.detail-label {
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
}

.detail-value {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.detail-value.price {
    font-size: 24px;
    color: #28a745;
}

.formation-description-section {
    margin: 40px 0;
}

.formation-description-section h2 {
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}

.description-content {
    line-height: 1.8;
    font-size: 16px;
    color: #444;
    white-space: pre-line;
}

.inscription-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 30px;
    border-radius: 15px;
    margin-top: 40px;
}

.inscription-section h3 {
    color: #333;
    margin-bottom: 20px;
}

.places-info {
    margin-bottom: 20px;
    color: #666;
    font-size: 16px;
}

.inscription-actions {
    display: flex;
    gap: 15px;
    align-items: center;
}

.btn-lg {
    padding: 15px 40px;
    font-size: 16px;
}

.login-required-section {
    background: #fff3cd;
    color: #856404;
    padding: 25px;
    border-radius: 10px;
    border-left: 5px solid #ffc107;
    margin-top: 40px;
}

.login-content {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.login-icon {
    font-size: 28px;
}

.login-actions {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 20px;
    border-radius: 10px;
    border-left: 5px solid #dc3545;
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.alert-icon {
    font-size: 24px;
}
</style>

<?php require_once __DIR__ . '/../template_footer.php'; ?>