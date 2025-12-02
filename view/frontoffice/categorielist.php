<?php 
include 'header.php';
include '../../controller/categorieC.php';

$cc = new categorieC();
$liste = $cc->listecategorie();
?>

<style>
body {
    padding-top: 80px !important;
}

/* Couleurs */
.bg-primary-subtle { background-color: rgba(67, 97, 238, 0.1) !important; --card-color: #4361ee; --card-color-light: #667eea; }
.bg-success-subtle { background-color: rgba(6, 214, 160, 0.1) !important; --card-color: #06d6a0; --card-color-light: #0ce9b8; }
.bg-warning-subtle { background-color: rgba(255, 209, 102, 0.1) !important; --card-color: #ffd166; --card-color-light: #ffde8a; }
.bg-info-subtle { background-color: rgba(17, 138, 178, 0.1) !important; --card-color: #118ab2; --card-color-light: #15a0d1; }
.bg-danger-subtle { background-color: rgba(239, 71, 111, 0.1) !important; --card-color: #ef476f; --card-color-light: #f56c8d; }
.bg-purple-subtle { background-color: rgba(114, 9, 183, 0.1) !important; --card-color: #7209b7; --card-color-light: #8a1bd9; }
.bg-indigo-subtle { background-color: rgba(58, 12, 163, 0.1) !important; --card-color: #3a0ca3; --card-color-light: #4a11cc; }
.bg-teal-subtle { background-color: rgba(13, 148, 136, 0.1) !important; --card-color: #0d9488; --card-color-light: #10b3a4; }

/* Cartes */
.category-card-wrapper {
    position: relative;
    height: 100%;
    margin-bottom: 0.75rem;
    animation: fadeInUp 0.4s ease forwards;
    opacity: 0;
}

.category-card {
    border-radius: 12px;
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    background: white;
    position: relative;
}

.category-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12) !important;
    border-color: #4361ee;
}

.category-card .card-body {
    padding: 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, var(--card-color-light), var(--card-color));
    color: white;
    font-size: 1.8rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.category-title {
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.3;
    min-height: 2.5rem;
    text-align: center;
    margin: 0.3rem 0;
}

.category-indicator .badge {
    font-weight: 500;
    font-size: 0.75rem;
    margin: 0.25rem 0;
    padding: 0.25rem 0.75rem;
    transition: all 0.3s ease;
}

.category-card:hover .category-indicator .badge {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card-footer {
    margin-top: auto;
    padding: 0.5rem 1rem;
    background: rgba(248,249,250,0.3);
    border-top: 1px solid #f0f0f0;
}

.card-footer a {
    transition: all 0.3s ease;
    display: inline-block;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.9rem;
}

.card-footer a:hover {
    color: #4361ee !important;
    transform: translateX(3px);
    background: rgba(67,97,238,0.05);
    text-decoration: none !important;
}

/* Empty state */
.empty-state-container {
    max-width: 500px;
    margin: 2rem auto;
    padding: 1rem;
    text-align: center;
}

.border-dashed { border-style: dashed !important; border-color: #dee2e6 !important; border-width: 2px !important; }
.empty-state-icon { opacity: 0.5; margin-bottom: 1rem; font-size: 3rem; }

/* Animation */
@keyframes fadeInUp {
    from { opacity:0; transform: translateY(15px); }
    to { opacity:1; transform: translateY(0); }
}

/* Responsive */
@media (max-width: 768px) {
    .icon-circle { width:50px; height:50px; font-size:1.5rem; }
    .category-title { font-size:1rem; min-height:2.2rem; }
    .category-card { min-height:160px; }
}

@media (max-width: 576px) {
    .col-md-6 { width:50%; }
    .category-card { min-height:150px; }
}
</style>

<div class="container mt-4">
    <h1 class="mb-4 fw-bold">Categories</h1>

    <?php if($liste): ?>
        <div class="row g-3">
            <?php 
            $colors = ['primary','success','warning','info','danger','purple','indigo','teal'];
            foreach($liste as $index => $cat): 
                $color = $colors[$index % count($colors)];
            ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="category-card-wrapper">
                        <div class="category-card">
                            <a href="viewoffers.php?category=<?= $cat['id'] ?>" class="stretched-link"></a>
                            <div class="card-body">
                                <div class="icon-circle bg-<?= $color ?>-subtle">
                                    <i class="mdi mdi-tag-multiple"></i>
                                </div>
                                <h4 class="category-title"><?= htmlspecialchars($cat['nom']) ?></h4>
                                <div class="category-indicator">
                                    <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?>">Category</span>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <a href="viewoffers.php?category=<?= $cat['id'] ?>">
                                    <i class="mdi mdi-eye-outline me-1"></i> See Offers
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state-container">
            <div class="card border-dashed shadow-none">
                <div class="card-body">
                    <div class="empty-state-icon"><i class="mdi mdi-tag-off-outline"></i></div>
                    <h5 class="text-muted fw-normal">No Categories</h5>
                    <p class="text-muted">Start by creating your first category</p>
                    <a href="addcategorie.php" class="btn btn-primary rounded-pill px-3">
                        <i class="mdi mdi-plus-circle-outline me-2"></i> Create Category
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.category-card');
    const wrappers = document.querySelectorAll('.category-card-wrapper');

    wrappers.forEach((wrapper, index) => { wrapper.style.animationDelay = (index*0.08)+'s'; });

    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.icon-circle');
            const badge = this.querySelector('.category-indicator .badge');
            if(icon) { icon.style.transform='scale(1.08)'; icon.style.boxShadow='0 4px 15px rgba(0,0,0,0.15)'; }
            if(badge) { badge.style.transform='translateY(-1px)'; badge.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'; }
        });
        card.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.icon-circle');
            const badge = this.querySelector('.category-indicator .badge');
            if(icon) { icon.style.transform='scale(1)'; icon.style.boxShadow='none'; }
            if(badge) { badge.style.transform='translateY(0)'; badge.style.boxShadow='none'; }
        });
    });
});
</script>
