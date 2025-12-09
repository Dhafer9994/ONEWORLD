<?php
// formation_apprentissage/includes/alerts.php
if (isset($_SESSION['flash_messages'])): 
    $messages = $_SESSION['flash_messages'];
    unset($_SESSION['flash_messages']);
?>
<div class="flash-messages-container">
    <?php foreach ($messages as $message): ?>
        <div class="alert alert-<?php echo $message['type']; ?> alert-dismissible fade show" role="alert">
            <div class="alert-content">
                <?php 
                $icons = [
                    'success' => '✅',
                    'error' => '❌',
                    'warning' => '⚠️',
                    'info' => 'ℹ️'
                ];
                $icon = $icons[$message['type']] ?? '📢';
                ?>
                <div class="alert-icon"><?php echo $icon; ?></div>
                <div class="alert-text">
                    <div class="alert-title">
                        <?php 
                        $titles = [
                            'success' => 'Succès !',
                            'error' => 'Erreur !',
                            'warning' => 'Attention !',
                            'info' => 'Information'
                        ];
                        echo $titles[$message['type']] ?? 'Message';
                        ?>
                    </div>
                    <div class="alert-message"><?php echo htmlspecialchars($message['text']); ?></div>
                </div>
            </div>
            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Fermer">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endforeach; ?>
</div>

<style>
.flash-messages-container {
    position: fixed;
    top: 100px;
    right: 20px;
    z-index: 9998;
    max-width: 400px;
    width: 100%;
}

.alert {
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 15px;
    border-left: 5px solid;
    animation: slideInRight 0.3s ease;
    position: relative;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.alert-success {
    background: #d4edda;
    border-color: #28a745;
    color: #155724;
}

.alert-error {
    background: #f8d7da;
    border-color: #dc3545;
    color: #721c24;
}

.alert-warning {
    background: #fff3cd;
    border-color: #ffc107;
    color: #856404;
}

.alert-info {
    background: #d1ecf1;
    border-color: #17a2b8;
    color: #0c5460;
}

.alert-content {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.alert-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.alert-text {
    flex: 1;
}

.alert-title {
    font-weight: 600;
    margin-bottom: 5px;
    font-size: 16px;
}

.alert-message {
    font-size: 14px;
    line-height: 1.5;
}

.btn-close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    font-size: 14px;
    opacity: 0.7;
    transition: opacity 0.3s;
    padding: 5px;
}

.btn-close:hover {
    opacity: 1;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}

.alert.fade-out {
    animation: fadeOut 0.3s ease forwards;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade-out');
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Close button functionality
    document.querySelectorAll('.btn-close').forEach(btn => {
        btn.addEventListener('click', function() {
            const alert = this.closest('.alert');
            alert.classList.add('fade-out');
            setTimeout(() => alert.remove(), 300);
        });
    });
});
</script>
<?php endif; ?>