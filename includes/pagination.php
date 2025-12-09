<?php
// formation_apprentissage/includes/pagination.php

/**
 * Système de pagination avancé
 */

class Pagination {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    private $urlPattern;
    private $maxPagesToShow = 7;
    
    public function __construct($totalItems, $itemsPerPage, $currentPage, $urlPattern = '?page={page}') {
        $this->totalItems = (int)$totalItems;
        $this->itemsPerPage = (int)$itemsPerPage;
        $this->currentPage = (int)$currentPage;
        $this->urlPattern = $urlPattern;
        
        $this->calculate();
    }
    
    private function calculate() {
        $this->totalPages = ($this->itemsPerPage == 0) ? 0 : (int)ceil($this->totalItems / $this->itemsPerPage);
        
        if ($this->currentPage < 1) {
            $this->currentPage = 1;
        }
        
        if ($this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    public function getOffset() {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }
    
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    public function getItemsPerPage() {
        return $this->itemsPerPage;
    }
    
    public function getTotalItems() {
        return $this->totalItems;
    }
    
    public function getShowingText() {
        $start = $this->getOffset() + 1;
        $end = min($this->getOffset() + $this->itemsPerPage, $this->totalItems);
        
        if ($this->totalItems == 0) {
            return 'Aucun élément';
        }
        
        return "Affichage de $start à $end sur {$this->totalItems} éléments";
    }
    
    public function getPages() {
        $pages = [];
        
        if ($this->totalPages <= 1) {
            return $pages;
        }
        
        if ($this->totalPages <= $this->maxPagesToShow) {
            for ($i = 1; $i <= $this->totalPages; $i++) {
                $pages[] = $this->createPage($i, $i == $this->currentPage);
            }
        } else {
            // Calculer le début et la fin
            $start = max(1, $this->currentPage - floor($this->maxPagesToShow / 2));
            $end = $start + $this->maxPagesToShow - 1;
            
            if ($end > $this->totalPages) {
                $end = $this->totalPages;
                $start = $end - $this->maxPagesToShow + 1;
            }
            
            // Première page
            if ($start > 1) {
                $pages[] = $this->createPage(1, false);
                if ($start > 2) {
                    $pages[] = $this->createEllipsis();
                }
            }
            
            // Pages du milieu
            for ($i = $start; $i <= $end; $i++) {
                $pages[] = $this->createPage($i, $i == $this->currentPage);
            }
            
            // Dernière page
            if ($end < $this->totalPages) {
                if ($end < $this->totalPages - 1) {
                    $pages[] = $this->createEllipsis();
                }
                $pages[] = $this->createPage($this->totalPages, false);
            }
        }
        
        return $pages;
    }
    
    private function createPage($pageNumber, $isCurrent) {
        return [
            'number' => $pageNumber,
            'url' => str_replace('{page}', $pageNumber, $this->urlPattern),
            'isCurrent' => $isCurrent,
            'isEllipsis' => false
        ];
    }
    
    private function createEllipsis() {
        return [
            'number' => '...',
            'url' => null,
            'isCurrent' => false,
            'isEllipsis' => true
        ];
    }
    
    public function getPrevUrl() {
        if ($this->currentPage > 1) {
            return str_replace('{page}', $this->currentPage - 1, $this->urlPattern);
        }
        return null;
    }
    
    public function getNextUrl() {
        if ($this->currentPage < $this->totalPages) {
            return str_replace('{page}', $this->currentPage + 1, $this->urlPattern);
        }
        return null;
    }
    
    public function render($options = []) {
        $defaultOptions = [
            'container_class' => 'pagination',
            'item_class' => 'page-item',
            'link_class' => 'page-link',
            'active_class' => 'active',
            'disabled_class' => 'disabled',
            'prev_text' => '&laquo; Précédent',
            'next_text' => 'Suivant &raquo;',
            'show_info' => true,
            'show_limits' => true,
            'limits' => [10, 25, 50, 100]
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        ob_start();
        ?>
        
        <div class="pagination-container">
            <?php if ($options['show_info']): ?>
                <div class="pagination-info">
                    <?php echo $this->getShowingText(); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($options['show_limits']): ?>
                <div class="pagination-limits">
                    <form method="GET" class="limit-form">
                        <label for="items_per_page">Éléments par page :</label>
                        <select name="limit" id="items_per_page" class="limit-select" onchange="this.form.submit()">
                            <?php foreach ($options['limits'] as $limit): ?>
                                <option value="<?php echo $limit; ?>" <?php echo $this->itemsPerPage == $limit ? 'selected' : ''; ?>>
                                    <?php echo $limit; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            <?php endif; ?>
            
            <nav aria-label="Navigation des pages">
                <ul class="<?php echo $options['container_class']; ?>">
                    <!-- Bouton Précédent -->
                    <li class="<?php echo $options['item_class']; ?> <?php echo $this->getPrevUrl() ? '' : $options['disabled_class']; ?>">
                        <?php if ($this->getPrevUrl()): ?>
                            <a class="<?php echo $options['link_class']; ?>" href="<?php echo $this->getPrevUrl(); ?>">
                                <?php echo $options['prev_text']; ?>
                            </a>
                        <?php else: ?>
                            <span class="<?php echo $options['link_class']; ?>">
                                <?php echo $options['prev_text']; ?>
                            </span>
                        <?php endif; ?>
                    </li>
                    
                    <!-- Pages -->
                    <?php foreach ($this->getPages() as $page): ?>
                        <li class="<?php echo $options['item_class']; ?> <?php echo $page['isCurrent'] ? $options['active_class'] : ''; ?>">
                            <?php if ($page['isEllipsis']): ?>
                                <span class="<?php echo $options['link_class']; ?>"><?php echo $page['number']; ?></span>
                            <?php else: ?>
                                <a class="<?php echo $options['link_class']; ?>" href="<?php echo $page['url']; ?>">
                                    <?php echo $page['number']; ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    
                    <!-- Bouton Suivant -->
                    <li class="<?php echo $options['item_class']; ?> <?php echo $this->getNextUrl() ? '' : $options['disabled_class']; ?>">
                        <?php if ($this->getNextUrl()): ?>
                            <a class="<?php echo $options['link_class']; ?>" href="<?php echo $this->getNextUrl(); ?>">
                                <?php echo $options['next_text']; ?>
                            </a>
                        <?php else: ?>
                            <span class="<?php echo $options['link_class']; ?>">
                                <?php echo $options['next_text']; ?>
                            </span>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>
        
        <?php
        return ob_get_clean();
    }
    
    /**
     * Récupère les données paginées depuis une requête
     */
    public static function paginateQuery($query, $params = [], $itemsPerPage = 10, $pageParam = 'page') {
        $currentPage = isset($_GET[$pageParam]) ? (int)$_GET[$pageParam] : 1;
        
        // Compter le total
        $countQuery = preg_replace('/SELECT(.*?)FROM/i', 'SELECT COUNT(*) as total FROM', $query, 1);
        $countQuery = preg_replace('/ORDER BY.*/i', '', $countQuery);
        
        require_once __DIR__ . '/../config/database.php';
        $db = Database::getInstance();
        
        $stmt = $db->prepare($countQuery);
        $stmt->execute($params);
        $totalItems = $stmt->fetch()['total'];
        
        // Créer l'objet pagination
        $pagination = new self($totalItems, $itemsPerPage, $currentPage);
        
        // Modifier la requête pour ajouter LIMIT et OFFSET
        $query .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $pagination->getLimit(), PDO::PARAM_INT);
        $stmt->bindValue(':offset', $pagination->getOffset(), PDO::PARAM_INT);
        
        $stmt->execute();
        $items = $stmt->fetchAll();
        
        return [
            'items' => $items,
            'pagination' => $pagination
        ];
    }
}

/**
 * Fonction helper pour la pagination
 */
function paginate($totalItems, $itemsPerPage = 10, $currentPage = 1, $urlPattern = '?page={page}') {
    return new Pagination($totalItems, $itemsPerPage, $currentPage, $urlPattern);
}

/**
 * Styles pour la pagination
 */
function paginationStyles() {
    return '
    <style>
    .pagination-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin: 30px 0;
    }
    
    .pagination-info {
        font-size: 14px;
        color: #666;
    }
    
    .pagination-limits {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .limit-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .limit-select {
        padding: 5px 10px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background: white;
    }
    
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 5px;
    }
    
    .page-item {
        margin: 0;
    }
    
    .page-link {
        display: block;
        padding: 8px 16px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        color: #667eea;
        text-decoration: none;
        transition: all 0.3s;
        font-weight: 500;
    }
    
    .page-link:hover {
        background: #f8f9fa;
        border-color: #667eea;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
    }
    
    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background: #f8f9fa;
        border-color: #dee2e6;
    }
    
    @media (max-width: 768px) {
        .pagination-container {
            align-items: center;
            text-align: center;
        }
        
        .pagination-limits {
            flex-direction: column;
            align-items: center;
        }
        
        .limit-form {
            flex-direction: column;
        }
        
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
    </style>
    ';
}
?>