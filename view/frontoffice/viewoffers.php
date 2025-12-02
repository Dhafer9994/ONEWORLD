<?php 
include 'header.php';
include '../../controller/offreC.php';
include '../../controller/categorieC.php';

$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;

$offreC = new Offrec();
$categorieC = new categorieC();

$category = $categorieC->getCategorieById($category_id);
$offers = $offreC->getOffresByCategorie($category_id);
?>

<style>
body {
    padding-top: 80px !important;
}
</style>

<div class="container mt-4">
    <div class="mb-4">
        <a href="categories.php" class="btn btn-light btn-sm rounded-pill px-3">
            <i class="mdi mdi-arrow-left me-1"></i> Back to Categories
        </a>
    </div>

    <div class="text-center mb-5">
        <div class="mb-4">
            <div class="category-header-icon">
                <i class="mdi mdi-briefcase"></i>
            </div>
        </div>
        
        <h1 class="display-5 fw-bold mb-3 text-dark">
            <?php echo $category ? htmlspecialchars($category['nom']) : 'All Offers'; ?>
        </h1>
        
        <?php if($category && !empty($category['description'])): ?>
            <p class="lead text-muted mb-4">
                <?php echo htmlspecialchars($category['description']); ?>
            </p>
        <?php endif; ?>
        
        <div class="category-stats">
            <span class="badge bg-primary rounded-pill px-4 py-2">
                <i class="mdi mdi-briefcase-check me-2"></i>
                <?php echo count($offers); ?> Job<?php echo count($offers) != 1 ? 's' : '' ?> Available
            </span>
        </div>
    </div>

    <?php if($offers && count($offers) > 0): ?>
        <div class="row g-3">
            <?php 
            $colors = ['primary', 'success', 'warning', 'info', 'danger', 'purple', 'indigo', 'teal'];
            $color_index = 0;
            ?>
            <?php foreach($offers as $offer): ?>
                <?php 
                $color = $colors[$color_index % count($colors)];
                $color_index++;
                ?>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                    <div class="offer-card card border-0 h-100">
                        <div class="card-body p-3 text-center">
                            <div class="offer-icon mb-3 mx-auto">
                                <div class="icon-circle bg-<?php echo $color; ?>-subtle">
                                    <i class="mdi mdi-briefcase text-<?php echo $color; ?>" style="font-size: 2rem;"></i>
                                </div>
                            </div>

                            <h5 class="offer-title fw-bold mb-2 text-dark" style="font-size:1.1rem;">
                                <?php echo htmlspecialchars($offer['titre']); ?>
                            </h5>

                            <span class="badge bg-<?php echo $color; ?>-subtle text-<?php echo $color; ?> rounded-pill px-2 py-1 mb-3" style="font-size:0.75rem;">
                                <?php echo !empty($offer['categorie_nom']) ? htmlspecialchars($offer['categorie_nom']) : 'Category'; ?>
                            </span>
                        </div>

                        <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3 d-flex justify-content-around">
    <a href="viewofferdetails.php?id=<?php echo $offer['id']; ?>" 
       class="btn btn-outline-primary btn-sm rounded-pill px-3">
       <i class="mdi mdi-eye-outline me-1"></i> View Details
    </a>
    <a href="apply.php?id=<?php echo $offer['id']; ?>" 
       class="btn btn-primary btn-sm rounded-pill px-3">
       <i class="mdi mdi-send me-1"></i> Apply
    </a>
</div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state-container">
            <div class="card border-dashed border-2 shadow-none">
                <div class="card-body py-4 text-center">
                    <div class="empty-state-icon mb-3">
                        <i class="mdi mdi-briefcase-off-outline text-muted opacity-25" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted fw-normal mb-2">No Offers</h5>
                    <p class="text-muted mb-3">There are currently no job openings in this category.</p>
                    <a href="categories.php" class="btn btn-primary rounded-pill px-3">
                        <i class="mdi mdi-arrow-left me-2"></i> Back to Categories
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* Colors */
.bg-primary-subtle { background-color: rgba(67, 97, 238, 0.1) !important; }
.text-primary { color: #4361ee !important; }
.bg-success-subtle { background-color: rgba(6, 214, 160, 0.1) !important; }
.text-success { color: #06d6a0 !important; }
.bg-warning-subtle { background-color: rgba(255, 209, 102, 0.1) !important; }
.text-warning { color: #ffd166 !important; }
.bg-info-subtle { background-color: rgba(17, 138, 178, 0.1) !important; }
.text-info { color: #118ab2 !important; }
.bg-danger-subtle { background-color: rgba(239, 71, 111, 0.1) !important; }
.text-danger { color: #ef476f !important; }
.bg-purple-subtle { background-color: rgba(114, 9, 183, 0.1) !important; }
.text-purple { color: #7209b7 !important; }
.bg-indigo-subtle { background-color: rgba(58, 12, 163, 0.1) !important; }
.text-indigo { color: #3a0ca3 !important; }
.bg-teal-subtle { background-color: rgba(13, 148, 136, 0.1) !important; }
.text-teal { color: #0d9488 !important; }

/* Cards */
.offer-card {
    border-radius: 12px;
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    background: white;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.offer-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
    border-color: #4361ee;
}
.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 2rem;
}

/* Animation */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
.col-xl-3, .col-lg-4, .col-md-6 {
    animation: fadeInUp 0.4s ease forwards;
    opacity: 0;
}

/* Empty state */
.border-dashed { border-style: dashed !important; border-color: #dee2e6 !important; border-width:2px !important; }
.empty-state-icon { opacity:0.5; margin-bottom:1rem; }
.empty-state-container { max-width:500px; margin:2rem auto; padding:1rem; }

/* Responsive */
@media(max-width:768px){
    .icon-circle{width:50px;height:50px;font-size:1.5rem;}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.offer-card');
    cards.forEach((card,index)=>{
        card.style.animationDelay = (index*0.08)+'s';
        card.addEventListener('mouseenter',()=>{card.style.transform='translateY(-4px)';card.style.boxShadow='0 8px 25px rgba(0,0,0,0.08)';card.style.borderColor='#4361ee';});
        card.addEventListener('mouseleave',()=>{card.style.transform='translateY(0)';card.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';card.style.borderColor='#e0e0e0';});
    });
});
</script>
