<?php
include 'header.php';
include '../../controller/categorieC.php';

$categorieC = new categorieC();
$listecategorie = $categorieC->listecategorie()->fetchAll();
?>

<style>
:root {
    --primary-color: #5BB12F;       
    --primary-dark: #4a9e25;
    --primary-light: #7bc954;
    --secondary-color: #333333;
    --bg-light: #f9f9f9;
    --text-dark: #333333;
    --text-muted: #666666;
    --white: #ffffff;
    --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.08);
}

body {
    background-color: var(--white);
    font-family: 'Open Sans', sans-serif;
    color: var(--text-dark);
}

/* Page Header */
.page-title-section {
    padding: 100px 0 60px;
    background-color: #f4f6f8;
    text-align: center;
    margin-bottom: 60px;
     background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('img/header-bg.jpg'); 
    background-size: cover;
    background-position: center;
    color: white;
}
.page-title-section.no-img {
    background: linear-gradient(135deg, #333 0%, #5BB12F 100%);
}


.page-title {
    font-family: 'Oswald', sans-serif;
    font-size: 42px;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 15px;
    letter-spacing: 1px;
    color: white;
}

.page-subtitle {
    font-size: 16px;
    color: #eee;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Search Bar */
.search-container {
    margin-top: -30px;
    margin-bottom: 50px;
    position: relative;
    z-index: 10;
}

.search-input-wrapper {
    background: white;
    padding: 10px 20px;
    border-radius: 50px;
    box-shadow: var(--shadow-soft);
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    border: 1px solid #eaeaea;
}

.search-input-wrapper i {
    color: var(--primary-color);
    font-size: 18px;
    margin-right: 15px;
}

.search-input {
    border: none;
    width: 100%;
    padding: 10px;
    outline: none;
    font-size: 16px;
    color: var(--text-dark);
}

/* Category Cards */
.category-card {
    background: white;
    border-radius: 3px; 
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.4s ease;
    border: 1px solid #f0f0f0;
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.category-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(91, 177, 47, 0.15); /* Greenish shadow */
    border-color: var(--primary-light);
}

.category-img {
    height: 160px;
    background-color: #f9f9f9;
    background-size: cover;
    background-position: center;
    position: relative;
}

.category-img-placeholder {
     display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--primary-color);
    font-size: 36px;
    opacity: 0.2;
}


.category-content {
    padding: 25px;
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Icon Bubble (Optional overlaid on image or top of content) */
.category-icon {
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: -55px auto 15px; /* Pull up into image area */
    position: relative;
    z-index: 2;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    color: var(--primary-color);
    font-size: 24px;
    transition: all 0.3s;
}

.category-card:hover .category-icon {
    background: var(--primary-color);
    color: white;
    transform: scale(1.1);
}

.category-title {
    font-family: 'Oswald', sans-serif;
    font-size: 20px;
    font-weight: 500;
    margin-bottom: 10px;
    color: var(--secondary-color);
    text-transform: uppercase;
}

.category-desc {
    color: var(--text-muted);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 20px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.btn-explore {
    color: var(--primary-color);
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s;
    text-decoration: none;
    border: 1px solid var(--primary-color);
    padding: 8px 20px;
    border-radius: 3px;
    display: inline-block;
}

.btn-explore:hover {
    background: var(--primary-color);
    color: white;
    text-decoration: none;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 40px, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

.category-item {
    opacity: 0; /* Hidden by default for animation */
    animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
}

</style>

<!-- Hero / Page Title -->
<div class="page-title-section no-img">
    <div class="container">
        <h1 class="page-title">Explore Categories</h1>
        <p class="page-subtitle"> OneWorld Connecting lives, building peace</p>
    </div>
</div>

<div class="container" style="padding-bottom: 80px;">
    
    <!-- Search Filter -->
    <div class="search-container">
        <div class="search-input-wrapper">
            <i class="fa fa-search"></i>
            <input type="text" id="categorySearch" class="search-input" placeholder="Search for a category (e.g. IT, Marketing)..." onkeyup="filterCategories()">
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="row" id="categoriesGrid">
        <?php 
        if(count($listecategorie) > 0) {
            $delay = 0;
            foreach ($listecategorie as $cat) {
                // Stagger animations
                $delay += 0.1;
                $catImg = !empty($cat['image']) ? $cat['image'] : null;
        ?>
            <div class="col-md-4 col-sm-6 category-item" style="animation-delay: <?php echo $delay; ?>s;">
                <div class="category-card">
                    <!-- Image Area -->
                     <div class="category-img" style="<?php echo $catImg ? "background-image: url('img/" . $catImg . "');" : ""; ?>">
                        <?php if(!$catImg): ?>
                            <div class="category-img-placeholder">
                                <i class="fa fa-picture-o"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="category-icon">
                        <i class="fa fa-folder-open-o"></i>
                    </div>

                    <div class="category-content">
                        <div>
                            <h3 class="category-title"><?php echo htmlspecialchars($cat['nom']); ?></h3>
                            <p class="category-desc">
                                <?php echo htmlspecialchars($cat['description']); ?>
                            </p>
                        </div>
                        <div>
                            <a href="viewoffers.php?category=<?php echo $cat['id']; ?>" class="btn-explore">
                                View Offers <i class="fa fa-angle-right" style="margin-left:5px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            }
        } else {
        ?>
            <div class="col-xs-12 text-center" style="padding: 40px; color: #999;">
                <i class="fa fa-folder-o" style="font-size: 48px; margin-bottom: 20px;"></i>
                <p>No categories found.</p>
            </div>
        <?php } ?>
    </div>

</div>



<script>
function filterCategories() {
    var input, filter, grid, items, title, i, txtValue;
    input = document.getElementById('categorySearch');
    filter = input.value.toUpperCase();
    grid = document.getElementById("categoriesGrid");
    items = grid.getElementsByClassName('category-item');

    for (i = 0; i < items.length; i++) {
        title = items[i].getElementsByClassName("category-title")[0];
        if (title) {
            txtValue = title.textContent || title.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                items[i].style.display = "";
            } else {
                items[i].style.display = "none";
            }
        }       
    }
}
</script>

</body>
</html>