<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | wildrosefoundry.ca</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <script src='https://cdn.jsdelivr.net/npm/temporal-polyfill@0.3.0/global.min.js'></script> <!-- Temporal Polyfill so it works on any browser. -->
</head>
<body>
    <?php
    require("includes/connect.php");
    include("includes/header.inc");
    ?>
    <main>
        <section id="hero">
            <h1>Wild Rose Foundry</h1>
            <h2>Made by hand, inspired by place.</h2>
            <a href="shop.php">Shop Now</a>
        </section>
        <section>
            <h2>Featured</h2>
            <ul id="carousel">
                <?php
                $query = "SELECT * FROM products p JOIN vendors v ON p.vendor_id = v.vendor_id WHERE p.is_featured = 1";
                $sql = mysqli_query($connect, $query);
                
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
                <li>
                    <article class="product-card">
                        <a href="product.php?id=<?php echo $row['product_id']; ?>&variant=<?php echo $variantid; ?>">
                            <?php if ($row['is_featured'] == 1) {
                                echo '<span class="featured">Featured</span>';
                            } ?>
                            <img src="images/products/featured/<?php echo $row['product_img']; ?>" alt="<?php echo $row['product_img_alt']; ?>" width="700" height="700" loading="lazy">
                            <span class="price"><?php echo $row['price']; ?></span>
                            <h3><?php
                            echo $row['product_name'];
                            if ($variantvalue !== "") {
                                echo ' - <span class="variantvalue">'.$variantvalue.'</span>';
                            } else {
                                echo "";
                            }
                            ?></h3>
                            <h4><?php echo $row['vendor_name']; ?></h4>
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
                                echo '<img src="images/swatches/'.$swatchValue.'.webp" alt="'.$swatch['variant_value'].'" width="25" height="25" data-variant-img="images/products/featured/'.$swatch['variant_img'].'" loading="lazy"';
                                if ($swatch['variant_value'] == $variantvalue) {
                                    echo ' class="is-selected"';
                                }
                                echo'>';
                            }

                            echo '</div>';
                        }
                        ?>
                    </article>
                </li>
                <?php } ?>
            </ul>
        </section>
        <section id="about" class="image-text">
            <picture>
                <source media="(min-width: 768px)" srcset="images/interior.webp">
                <source srcset="images/interior1.webp">
                <img src="images/interior1.webp" alt="Interior of the Wild Rose Foundry artisan market in Alberta, showing vendors selling ceramics and handmade goods inside a warm, rustic warehouse with shoppers walking through the aisles." width="1024" height="1024">
            </picture>
            <div>
                <h1>About Us</h1>
                <p><strong>Wild Rose Foundry</strong> is a vibrant artisan collective in the heart of Alberta, dedicated to showcasing the creativity and craftsmanship of local makers. Each weekend, our converted warehouse comes alive with the buzz of shoppers exploring pottery, jewelry, textiles, candles, and small-batch foods crafted by independent vendors from across the province. Visitors come for the quality but stay for the community, discovering new makers, stories, and favourites with every visit.</p>
                <a href="about.php" class="primary-btn">Learn More</a>
            </div>
        </section>
        <!--<section class="bg-img">
            <h2>Made by hand, inspired by place.</h2>
        </section>-->
    </main>
    <?php include("includes/footer.inc"); ?>
    <script src="scripts/carousel.js"></script>
    <script src="scripts/swatches.js"></script>
</body>
</html>