<?php 
$page_title = "OneWorld - Special Offers";
include 'header.php';
include_once '../../controller/offreC.php';

$oc = new Offrec();
$offres = $oc->listeoffre(); 
?>

<!-- Start Offers Banner -->
<section style="padding: 120px 0 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 style="font-size: 48px; margin-bottom: 20px; font-weight: 300;">Exclusive Offers</h1>
                <p style="font-size: 18px; opacity: 0.9;">Discover amazing opportunities and promotions</p>
            </div>
        </div>
    </div>
</section>

<div class="container" style="padding: 80px 0;"> 
    <div class="row">
        <div class="col-md-12">
            <div class="section-title text-center">
                <h3>Current Offers</h3>
                <p>Click on any offer to view complete details</p>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if (!empty($offres)) { ?>
            <?php foreach ($offres as $index => $offre): 
                // Different colors based on category
                $categoryColors = [
                    'Technology' => '#3498db',
                    'Business' => '#e74c3c',
                    'Education' => '#2ecc71',
                    'Entertainment' => '#9b59b6',
                    'Travel' => '#f39c12',
                    'Food' => '#e67e22',
                    'Health' => '#1abc9c',
                    'Other' => '#95a5a6'
                ];
                
                $category = $offre['categorie'] ?? 'Other';
                $color = $categoryColors[$category] ?? $categoryColors['Other'];
                
                // Status badge color
                $statusColors = [
                    'Active' => 'success',
                    'Pending' => 'warning',
                    'Expired' => 'danger',
                    'Draft' => 'default'
                ];
                $statusClass = $statusColors[$offre['status']] ?? 'default';
            ?>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="offer-card" 
                     style="border: none; padding: 0; margin-bottom: 30px; border-radius: 12px; background: white; box-shadow: 0 5px 20px rgba(0,0,0,0.1); cursor: pointer; overflow: hidden; transition: all 0.3s ease;"
                     onclick="showOfferDetails(<?= htmlspecialchars(json_encode($offre)) ?>)">
                    
                    <!-- Offer Header with Category -->
                    <div style="background: <?= $color ?>; padding: 20px; color: white; position: relative;">
                        <div class="media">
                            <div class="pull-left">
                                <i class="fa fa-tag fa-2x"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading" style="margin: 0 0 5px 0; font-weight: 600; font-size: 18px;">
                                    <?= htmlspecialchars($offre['titre']) ?>
                                </h4>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <small>
                                        <i class="fa fa-folder"></i> 
                                        <?= htmlspecialchars($offre['categorie']) ?>
                                    </small>
                                    <span class="label label-<?= $statusClass ?>" style="font-size: 11px;">
                                        <?= htmlspecialchars($offre['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Offer Body -->
                    <div style="padding: 20px;">
                        <p style="color: #7f8c8d; margin-bottom: 15px; height: 60px; overflow: hidden; line-height: 1.4;">
                            <?= htmlspecialchars(substr($offre['description'], 0, 100)) . (strlen($offre['description']) > 100 ? '...' : '') ?>
                        </p>
                        
                        <!-- Location -->
                        <?php if (!empty($offre['location'])): ?>
                        <div style="margin-bottom: 10px;">
                            <small style="color: #95a5a6;">
                                <i class="fa fa-map-marker"></i> 
                                <?= htmlspecialchars($offre['location']) ?>
                            </small>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Author -->
                        <div style="border-top: 1px solid #ecf0f1; padding-top: 15px; display: flex; justify-content: space-between; align-items: center;">
                            <small style="color: #7f8c8d;">
                                <i class="fa fa-user"></i> 
                                By <?= htmlspecialchars($offre['auteur']) ?>
                            </small>
                            <button class="btn btn-sm" style="background: <?= $color ?>; color: white; border: none; border-radius: 20px; padding: 5px 15px;">
                                View Details <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php } else { ?>
            <div class="col-md-12 text-center">
                <div style="padding: 80px 20px; background: #f8f9fa; border-radius: 12px; border: 2px dashed #bdc3c7;">
                    <i class="fa fa-tags fa-4x" style="color: #bdc3c7; margin-bottom: 20px;"></i>
                    <h3 style="color: #7f8c8d; font-weight: 300;">No Offers Available</h3>
                    <p style="color: #95a5a6; font-size: 16px;">Check back later for new opportunities.</p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Offer Details Modal -->
<div class="modal fade" id="offerModal" tabindex="-1" role="dialog" aria-labelledby="offerModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #3498db; color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="offerModalLabel">Offer Details</h4>
            </div>
            <div class="modal-body" id="offerModalBody">
                <!-- Dynamic content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="contactAboutOffer()">
                    <i class="fa fa-envelope"></i> Contact About This Offer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showOfferDetails(offer) {
    // Status badge color
    const statusColors = {
        'Active': 'success',
        'Pending': 'warning', 
        'Expired': 'danger',
        'Draft': 'default'
    };
    const statusClass = statusColors[offer.status] || 'default';

    // Category color mapping
    const categoryColors = {
        'Technology': '#3498db',
        'Business': '#e74c3c',
        'Education': '#2ecc71',
        'Entertainment': '#9b59b6',
        'Travel': '#f39c12',
        'Food': '#e67e22',
        'Health': '#1abc9c',
        'Other': '#95a5a6'
    };
    const categoryColor = categoryColors[offer.categorie] || categoryColors['Other'];

    const modalBody = `
        <div class="row">
            <div class="col-md-12">
                <!-- Offer Header -->
                <div style="background: ${categoryColor}; padding: 20px; border-radius: 8px; color: white; margin-bottom: 20px;">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 style="margin: 0 0 10px 0; font-weight: 600;">${offer.titre}</h2>
                            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                <span class="label" style="background: rgba(255,255,255,0.2);">
                                    <i class="fa fa-folder"></i> ${offer.categorie}
                                </span>
                                <span class="label label-${statusClass}">
                                    ${offer.status}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <i class="fa fa-tag fa-4x" style="opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>

                <!-- Offer Details -->
                <div class="row">
                    <div class="col-md-8">
                        <h4 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 20px;">
                            <i class="fa fa-info-circle"></i> Offer Description
                        </h4>
                        <p style="font-size: 16px; line-height: 1.6; color: #34495e; background: #f8f9fa; padding: 20px; border-radius: 8px;">
                            ${offer.description}
                        </p>
                    </div>
                    
                    <div class="col-md-4">
                        <h4 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 20px;">
                            <i class="fa fa-list-alt"></i> Details
                        </h4>
                        
                        <div style="background: white; border: 1px solid #ecf0f1; border-radius: 8px; padding: 15px;">
                            <!-- Location -->
                            <div style="margin-bottom: 15px;">
                                <strong style="color: #2c3e50; display: block; margin-bottom: 5px;">
                                    <i class="fa fa-map-marker" style="color: #e74c3c;"></i> Location
                                </strong>
                                <span style="color: #7f8c8d;">${offer.location || 'Not specified'}</span>
                            </div>
                            
                            <!-- Author -->
                            <div style="margin-bottom: 15px;">
                                <strong style="color: #2c3e50; display: block; margin-bottom: 5px;">
                                    <i class="fa fa-user" style="color: #3498db;"></i> Author
                                </strong>
                                <span style="color: #7f8c8d;">${offer.auteur}</span>
                            </div>
                            
                            <!-- Category -->
                            <div style="margin-bottom: 15px;">
                                <strong style="color: #2c3e50; display: block; margin-bottom: 5px;">
                                    <i class="fa fa-folder" style="color: #9b59b6;"></i> Category
                                </strong>
                                <span class="label" style="background: ${categoryColor}; color: white;">
                                    ${offer.categorie}
                                </span>
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <strong style="color: #2c3e50; display: block; margin-bottom: 5px;">
                                    <i class="fa fa-flag" style="color: #f39c12;"></i> Status
                                </strong>
                                <span class="label label-${statusClass}">
                                    ${offer.status}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div style="margin-top: 20px; padding: 15px; background: #e8f4fd; border-radius: 8px; border-left: 4px solid #3498db;">
                    <h5 style="color: #2c3e50; margin-bottom: 10px;">
                        <i class="fa fa-lightbulb-o"></i> How to Proceed
                    </h5>
                    <p style="color: #34495e; margin: 0;">
                        Interested in this offer? Click the "Contact About This Offer" button below to get in touch with the author for more details and next steps.
                    </p>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('offerModalLabel').textContent = offer.titre;
    document.getElementById('offerModalBody').innerHTML = modalBody;
    $('#offerModal').modal('show');
}

function contactAboutOffer() {
    alert('Thank you for your interest! You will be redirected to contact the offer author.');
    $('#offerModal').modal('hide');
    // You can redirect to a contact form or email here
    // window.location.href = 'contact.php?offer=' + encodeURIComponent(offerTitle);
}

// Add hover effects
document.addEventListener('DOMContentLoaded', function() {
    const offerCards = document.querySelectorAll('.offer-card');
    offerCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 5px 20px rgba(0,0,0,0.1)';
        });
    });
});
</script>

<style>
.offer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.modal-header {
    border-radius: 6px 6px 0 0;
}

.label {
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 12px;
}

.btn-primary {
    background: #3498db;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
}

.btn-primary:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}
</style>

<?php include 'footer.php'; ?>