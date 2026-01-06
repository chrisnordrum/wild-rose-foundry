<?php
require("includes/connect.php");

if (isset($_GET['category'])) {
    $id = $_GET['category'];
    $query = 
        "SELECT * FROM categories
        WHERE category_id = $id;";
    $sql = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($sql);
    $name = $row['category_name'];
} elseif (isset($_GET['vendor'])) {
    $id = $_GET['vendor'];
    $query = 
        "SELECT * FROM vendors
        WHERE vendor_id = $id;";
    $sql = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($sql);
    $name = $row['vendor_name'];
} else {
    $name = "All";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop <?php echo $name; ?> | wildrosefoundry.ca</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <script src='https://cdn.jsdelivr.net/npm/temporal-polyfill@0.3.0/global.min.js'></script> <!-- Temporal Polyfill so it works on any browser. -->
</head>
<body>
    <?php include("includes/header.inc"); ?>
    <main>
        <section id="shop-header">
            <?php
            if (isset($_GET['category'])) {
                echo "<h1>Shop $name</h1>";
                echo '<p>'.$row['category_description'].'</p>';
            } elseif (isset($_GET['vendor'])) {
                echo '<img src="images/vendors/'.str_replace(" ", "", str_replace("&", "", str_replace(".", "", strtolower($name)))).'.webp" alt="'.$name.'" width="400" height="400">';

                echo "<h1>Shop $name</h1>";
                echo '<p>'.$row['bio'].'</p>';
            ?>
            <ul>
                <li>
                    <svg width="26" height="26" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4H20" stroke="#554d46" stroke-width="2" stroke-linecap="round"/>
                        <path d="M6 4V18" stroke="#554d46" stroke-width="2" stroke-linecap="round"/>
                        <path d="M18 4V18" stroke="#554d46" stroke-width="2" stroke-linecap="round"/>
                        <rect x="6" y="10" width="12" height="4" stroke="#554d46" stroke-width="2"/>
                    </svg>
                    <span><?php echo $row['booth_number']; ?></span>
                </li>
                <li>
                    <a href="https://www.instagram.com/" target="_blank" aria-label="Go to Instagram">
                        <img src="images/instagram_glyph.webp" alt="Instagram Logo" width="100" height="100">
                        <span><?php echo $row['instagram']; ?></span>
                    </a>
                </li>
            </ul>
            <?php
            } else {
                echo "<h1>Shop All</h1>";
                echo "<p>Items made by hand, inspired by place.</p>";
            }
            ?>
        </section>
        <div>
            <button id="filterBTN">Filter & Sort</button>
        </div>
        <div>
            <aside>
                <form action="shop.php" method="get" id="filters">
                    <?php
                    if (isset($_GET['category'])) {
                        echo '<input type="hidden" name="category" value="'.$_GET['category'].'">';
                    }
                    if (isset($_GET['vendor'])) {
                        echo '<input type="hidden" name="vendor" value="'.$_GET['vendor'].'">';
                    }
                    ?>
                    <fieldset>
                        <legend>Filter & Sort</legend>
                        <details open>
                            <summary>Sort By</summary>
                            <div>
                                <input type="radio" name="sort" id="featured" value="featured" <?php if(isset($_GET['sort']) && ($_GET['sort'] !== "featured")){echo "";}else{echo "checked ";} ?>>
                                <label for="featured">Featured</label>
                            </div>
                            <div>
                                <input type="radio" name="sort" id="priceLow" value="price-low" <?php if(isset($_GET['sort']) && ($_GET['sort'] == "price-low")){echo "checked ";} ?>>
                                <label for="priceLow">Price: Low to High</label>
                            </div>
                            <div>
                                <input type="radio" name="sort" id="priceHigh" value="price-high" <?php if(isset($_GET['sort']) && ($_GET['sort'] == "price-high")){echo "checked ";} ?>>
                                <label for="priceHigh">Price: High to Low</label>
                            </div>
                        </details>
                        <details <?php if(isset($_GET['category'])) {echo "open";} ?>>
                            <summary>Category</summary>
                            <?php
                            $categorysql = mysqli_query($connect, "SELECT * FROM categories");
                            while ($cat = mysqli_fetch_assoc($categorysql)) {
                                echo '<a href="?';
                                if (isset($_GET['sort'])) {
                                    echo 'sort='.$_GET['sort'].'&';
                                }
                                echo 'category='.$cat['category_id'].'" class="filter-btn';
                                if (isset($_GET['category']) and ($_GET['category'] == $cat['category_id'])) {
                                    echo ' is-selected';
                                }
                                echo '">'.$cat['category_name'].'</a>';
                            }
                            ?>
                        </details>
                        <details <?php if(isset($_GET['vendor'])) {echo "open";} ?>>
                            <summary>Vendor</summary>
                            <?php
                            $vendorsql = mysqli_query($connect, "SELECT vendor_id, vendor_name FROM vendors");
                            while ($ven = mysqli_fetch_assoc($vendorsql)) {
                                echo '<a href="?';
                                if (isset($_GET['sort'])) {
                                    echo 'sort='.$_GET['sort'].'&';
                                }
                                echo 'vendor='.$ven['vendor_id'].'" class="filter-btn';
                                if (isset($_GET['vendor']) and ($_GET['vendor'] == $ven['vendor_id'])) {
                                    echo ' is-selected';
                                }
                                echo '">'.$ven['vendor_name'].'</a>';
                            }
                            ?>
                        </details>
                    </fieldset>
                </form>
            </aside>
            <div id="product-grid">
                <?php
                $totalrows = mysqli_query($connect, "SELECT COUNT(*) FROM products");
                $rowcount = mysqli_fetch_assoc($totalrows);
                $count = $rowcount['COUNT(*)'];
                $items_per_page = 24;

                if (isset($_GET['start'])) {
                    $start = $_GET['start'];
                } else {
                    $start = 0;
                }

                $query = "SELECT * FROM products p JOIN vendors v ON p.vendor_id = v.vendor_id";
                $limit = "LIMIT $start, $items_per_page";

                $sort = "ORDER BY p.is_featured DESC";
                if (isset($_GET['sort'])) {
                    if ($_GET['sort'] == "featured") {
                        $sort = "ORDER BY p.is_featured DESC";
                    } elseif ($_GET['sort'] == "price-low") {
                        $sort = "ORDER BY p.price";
                    } elseif ($_GET['sort'] == "price-high") {
                        $sort = "ORDER BY p.price DESC";
                    }
                }

                $filter = "";

                if (isset($_GET['category'])) {
                    $categoryid = $_GET['category'];
                    $filter = 
                        "JOIN productscategories pc ON p.product_id = pc.product_id
                        WHERE pc.category_id = $categoryid";
                }

                if (isset($_GET['vendor'])) {
                    $vendorid = $_GET['vendor'];
                    $filter = "WHERE p.vendor_id = $vendorid";
                }

                $sql = mysqli_query($connect, "$query $filter $sort $limit");

                while ($row = mysqli_fetch_assoc($sql)) {
                    $productid = $row['product_id'];
                    $variantquery = 
                        "SELECT * FROM variants
                        JOIN products ON variants.product_id = products.product_id
                        WHERE products.product_id = $productid
                        LIMIT 1";
                    $variantsql = mysqli_query($connect, $variantquery);
                    $variantrow = mysqli_fetch_assoc($variantsql);
                    $variantvalue = $variantrow['variant_value'];
                    $variantid = $variantrow['variant_id'];
                ?>
                <article class="product-card">
                    <a href="product.php?id=<?php echo $productid; ?>&variant=<?php echo $variantid; ?>">
                        <?php if ($row['is_featured'] == 1) {
                            echo '<span class="featured">Featured</span>';
                        } ?>
                        <img src="images/products/productoverview/<?php echo $row['product_img']; ?>" alt="<?php echo $row['product_img_alt']; ?>" width="400" height="400" loading="lazy">
                        <span class="price"><?php echo $row['price']; ?></span>
                        <h2><?php
                            echo $row['product_name'];
                            if ($variantvalue !== "") {
                                echo ' - <span class="variantvalue">'.$variantvalue.'</span>';
                            } else {
                                echo "";
                            }
                            ?>
                        </h2>
                        <h3><?php echo $row['vendor_name']; ?></h3>
                    </a>
                    <?php
                    $swatchquery = 
                    "SELECT * FROM variants
                    JOIN products ON variants.product_id = products.product_id
                    WHERE variants.product_id = $productid";
                    $swatchsql = mysqli_query($connect, $swatchquery);
                    if (mysqli_num_rows($swatchsql) > 1) {
                        echo '<div class="swatches">';

                        while ($swatch = mysqli_fetch_assoc($swatchsql)) {
                            $swatchValue = str_replace(" ", "-", str_replace(" & ", "-", str_replace(" + ", "-", strtolower($swatch['variant_value']))));
                            echo '<img src="images/swatches/'.$swatchValue.'.webp" alt="'.$swatch['variant_value'].'" width="25" height="25" data-variant-img="images/products/productoverview/'.$swatch['variant_img'].'" loading="lazy"';
                            if ($swatch['variant_value'] == $variantvalue) {
                                    echo ' class="is-selected"';
                                }
                            echo '>';
                        }

                        echo '</div>';
                    }
                    ?>
                </article>
                <?php } // Close loop ?>
                <menu>
                    <?php // Pagination
                    $totalrows = mysqli_query($connect, "SELECT COUNT(*) FROM products p $filter");
                    $rowcount = mysqli_fetch_assoc($totalrows);
                    $count = $rowcount['COUNT(*)'];
                    $pagination_links_needed = ceil($count / $items_per_page);

                    if ($pagination_links_needed > 1) {
                    ?>
                    <li><a href="?<?php if(isset($_GET['sort'])){ echo 'sort='.$_GET['sort'].'&'; } ?><?php if(isset($_GET['category'])){ echo 'category='.$_GET['category'].'&'; } ?><?php if(isset($_GET['vendor'])){ echo 'vendor='.$_GET['vendor'].'&'; } ?>start=<?php if($start >= 1) {echo ($start - $items_per_page);} else {echo '0" class="is-disabled';} ?>">&#x00ab;</a></li>
                    <?php
                    for ($i=0; $i<$pagination_links_needed; $i++) {
                        echo '<li><a href="?';
                        if (isset($_GET['sort'])) {
                            echo 'sort='.$_GET['sort'].'&';
                        }
                        if (isset($_GET['category'])) {
                            echo 'category='.$_GET['category'].'&';
                        }
                        if (isset($_GET['vendor'])) {
                            echo 'vendor='.$_GET['vendor'].'&';
                        }
                        echo 'start=' . ($i * $items_per_page) . '"';
                        if (($start / $items_per_page) == $i) {
                            echo ' class="is-selected"';
                        }
                        echo '>' . ($i + 1) . '</a></li>';
                    }
                    ?>
                    <li><a href="?<?php if(isset($_GET['sort'])){ echo 'sort='.$_GET['sort'].'&'; } ?><?php if(isset($_GET['category'])){ echo 'category='.$_GET['category'].'&'; } ?><?php if(isset($_GET['vendor'])){ echo 'vendor='.$_GET['vendor'].'&'; } ?>start=<?php if($start < $pagination_links_needed) {echo ($start + $items_per_page);} else {echo '0" class="is-disabled';} ?>">&#x00bb;</a></li>
                    <?php } // Close "if" statement ?>
                </menu>
                <small>Showing <?php echo ($start + 1); ?> - <?php if(($start + $items_per_page) > $count) {echo $count;} else {echo ($start + $items_per_page);} ?> of <?php echo $count; ?> products</small>
            </div>
        </div>
    </main>
    <?php include("includes/footer.inc"); ?>
    <script src="scripts/shop.js"></script>
    <script src="scripts/swatches.js"></script>
</body>
</html>