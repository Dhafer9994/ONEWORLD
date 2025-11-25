// Custom JavaScript for OneWorld Dashboard
$(document).ready(function() {
    // Force OneWorld branding
    function enforceBranding() {
        // Replace any Sleek Dashboard text
        $('title').text(function(i, text) {
            return text.replace(/Sleek Dashboard/gi, 'OneWorld Dashboard');
        });
        
        // Ensure logo is visible
        $('.brand-icon').hide();
        $('.brand-logo').show();
        
        // Update page titles
        $('h1, h2').text(function(i, text) {
            return text.replace(/Sleek/gi, 'OneWorld');
        });
    }

    // Run on page load
    enforceBranding();
    
    // Run when navigating (for SPA-like behavior)
    $(document).on('click', 'a', function() {
        setTimeout(enforceBranding, 100);
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Sample chart data for dashboard
    if (typeof Chart !== 'undefined') {
        // Initialize sample charts
        initializeCharts();
    }
});

function initializeCharts() {
    // Sample chart initialization
    // You can customize this based on your needs
    console.log('OneWorld charts initialized');
}