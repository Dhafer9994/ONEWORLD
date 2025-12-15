<?php require __DIR__ . '/../layout/header.php'; ?>

<!-- Header / Slider -->
<section id="page-top">
    <div id="main-slide" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="item active">
                <img class="img-responsive" src="<?= ASSETS_URL ?>/images/header-bg-1.jpg" alt="slider">
                <div class="slider-content">
                    <div class="col-md-12 text-center">
                        <h1 class="animated3"><span><strong>Formations Gratuites</strong></span></h1>
                        <p class="animated2">Pour les réfugiés et demandeurs d'asile partout dans le monde</p>
                        <a href="#formations" class="page-scroll btn btn-primary animated1">Voir les cours</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Formations Section -->
<section id="formations" class="portfolio-section-1">
    <div class="container">
        
        <!-- SEARCH BAR (Simple & Advanced) -->
        <div class="row" style="margin-bottom: 40px;">
            <div class="col-md-10 col-md-offset-1">
                <div class="well">
                    <h4 class="text-center"><i class="fa fa-search"></i> Trouver une formation</h4>
                    <form action="index.php?route=home" method="GET" id="searchForm">
                        <div class="input-group input-group-lg">
                            <input type="text" name="q" class="form-control" placeholder="Rechercher (ex: informatique, anglais...)" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">Rechercher</button>
                            </span>
                        </div>
                        <div class="text-center" style="margin-top: 10px;">
                            <a href="#" onclick="$('#advancedSearch').slideToggle(); return false;">Recherche Avancée</a>
                        </div>
                        
                        <div id="advancedSearch" style="display:none; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 15px;">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Niveau</label>
                                    <select name="niveau" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="débutant">Débutant</option>
                                        <option value="intermédiaire">Intermédiaire</option>
                                        <option value="avancé">Avancé</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Type</label>
                                    <select name="type" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="live">En Direct (Live)</option>
                                        <option value="replay">En Autonomie</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-success btn-block">Filtrer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h3>Nos Formations</h3>
                    <p>Découvrez nos programmes adaptés</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php if (empty($formations)): ?>
                    <div class="alert alert-warning text-center">Aucune formation ne correspond à votre recherche.</div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($formations as $f): ?>
                            <div class="col-md-6 col-sm-6">
                                <div class="formation-card" style="border-left-color: <?= $f->getDateDebut() ? '#2ecc71' : '#6c757d' ?>;">
                                    <?php if ($f->getUrlMeet()): ?>
                                        <span class="badge-live" style="background:#4285F4;"><i class="fa fa-video-camera"></i> GOOGLE MEET</span>
                                    <?php elseif ($f->getDateDebut()): ?>
                                        <span class="badge-live">EN DIRECT</span>
                                    <?php else: ?>
                                        <span class="badge-classic">AUTO-RYTHME</span>
                                    <?php endif; ?>
                                    
                                    <h4><?= htmlspecialchars($f->getTitre()) ?></h4>
                                    <ul class="list-unstyled" style="margin-top: 15px; color: #666;">
                                        <li><i class="fa fa-clock-o"></i> <?= htmlspecialchars($f->getDuree()) ?></li>
                                        <li><i class="fa fa-signal"></i> <?= htmlspecialchars($f->getNiveau()) ?></li>
                                        <?php if ($f->getDateDebut()): ?>
                                            <li><i class="fa fa-calendar"></i> Début: <?= htmlspecialchars($f->getDateDebut()) ?></li>
                                        <?php endif; ?>
                                    </ul>
                                    
                                    <button class="btn btn-info btn-block" style="margin-top: 15px;" 
                                       onclick="openInscription('<?= $f->getId() ?>', '<?= htmlspecialchars($f->getTitre(), ENT_QUOTES) ?>')">
                                       S'inscrire
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- IMPROVED REGISTRATION MODAL -->
<div class="modal fade" id="inscriptionModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-graduation-cap"></i> Inscription</h4>
            </div>
            <div class="modal-body">
                
                <!-- SUCCESS MESSAGE (Hidden by default) -->
                <div id="successMessage" style="display:none; text-align: center; padding: 20px;">
                    <i class="fa fa-check-circle" style="font-size: 60px; color: #2ecc71;"></i>
                    <h3 style="color: #2ecc71;">Inscription Terminée !</h3>
                    <p style="font-size: 16px; margin-top: 15px;">
                        Merci <strong><span id="successName"></span></strong>, votre demande a bien été enregistrée.
                    </p>
                    <p>Un email de confirmation vous sera envoyé prochainement.</p>
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-top: 20px;">Fermer</button>
                    <!-- Redirect or Print option could follow -->
                </div>

                <!-- FORM -->
                <form id="inscriptionForm">
                    <input type="hidden" id="formationId">
                    <div class="alert alert-info">
                        Vous vous inscrivez à : <strong id="formationTitleDisplay"></strong>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fa fa-user"></i> Nom complet *</label>
                        <input type="text" class="form-control" id="nom" required placeholder="Votre nom et prénom">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-globe"></i> Pays de résidence *</label>
                        <input type="text" class="form-control" id="pays" required placeholder="Où habitez-vous actuellement ?">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-envelope"></i> Email ou WhatsApp *</label>
                        <input type="text" class="form-control" id="contact" required placeholder="Pour vous contacter">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-id-card"></i> ID Réfugié (Optionnel)</label>
                        <input type="text" class="form-control" id="idRefugie" placeholder="Si disponible">
                    </div>
                    
                    <div class="checkbox">
                        <label><input type="checkbox" id="consent" required> Je m'engage à suivre la formation avec assiduité.</label>
                    </div>
                    
                   <div style="margin-top: 20px;">
                       <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Annuler</button>
                       <button type="submit" class="btn btn-success pull-right">Valider l'inscription</button>
                       <div class="clearfix"></div>
                   </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>