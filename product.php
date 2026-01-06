<?php
require("includes/connect.php");
$id = $_GET['id'];
$var = $_GET['variant'];
$sql = mysqli_query($connect, "SELECT * FROM products JOIN vendors ON products.vendor_id = vendors.vendor_id WHERE products.product_id = $id");
$row = mysqli_fetch_assoc($sql);

$varquery = "SELECT * FROM variants JOIN products ON variants.product_id = products.product_id WHERE variants.variant_id = $var";
$varsql = mysqli_query($connect, $varquery);
$varrow = mysqli_fetch_assoc($varsql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['product_name']; ?> | wildrosefoundry.ca</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <script src='https://cdn.jsdelivr.net/npm/temporal-polyfill@0.3.0/global.min.js'></script> <!-- Temporal Polyfill so it works on any browser. -->
</head>
<body>
    <?php include("includes/header.inc"); ?>
    <main id="productDetails" class="image-text">
        <img src="images/products/productdetails/<?php echo $row['product_img']; ?>" alt="<?php echo $row['product_img_alt']; ?>" title="<?php echo $row['product_name']; ?> - Bergamont Clay" width="1024" height="1024" loading="lazy">
        <div>
            <?php
            $variantquery = 
                "SELECT * FROM variants
                JOIN products ON variants.product_id = products.product_id
                WHERE products.product_id = $id";
            $variantsql = mysqli_query($connect, $variantquery);

            if (mysqli_num_rows($variantsql) > 1) {
                echo '<ul id="thumbs">';

                while ($variant = mysqli_fetch_assoc($variantsql)) {
                    echo '<li><img src="images/products/productdetails/'.$variant['variant_img'].'" alt="'.$variant['variant_img_alt'].'" title="'.$variant['variant_value'].'" data-variant-id="'.$variant['variant_id'].'" width="150" height="150" loading="lazy"';
                    if ($var == $variant['variant_id']) {
                        echo ' class="is-selected"';
                    } else {
                        echo '';
                    }
                    echo '></li>';
                }
                echo '</ul>';
            }

            if ($row['is_featured'] == 1) {
                echo '<span class="featured">Featured</span>';
            }
            ?>
            <h1><?php
                echo $row['product_name'];
                if ($varrow['variant_value'] !== "") {
                    echo '<span class="variantvalue"> - '.$varrow['variant_value'].'</span>';
                }
            ?></h1>
            <a href="shop.php?vendor=<?php echo $row['vendor_id']; ?>"><?php echo $row['vendor_name']; ?></a>
            <span class="price"><?php echo $row['price']; ?></span>
            <?php
            if ($var !== "") {
                echo '<span class="variantvalue" id="variantvaluedesktop">'.$varrow['variant_value'].'</span>';
            }
            ?>
            <form action="checkout.php" method="post">
                <button type="submit" class="primary-btn" name="id" id="buyBTN" value="<?php echo $var; ?>">Add to Cart</button>
            </form>
             <details>
                <summary>Description</summary>
                <p><?php echo $row['product_description']; ?></p>
            </details>
            <details>
                <summary>Pickup & Returns</summary>
                <strong>Pickup</strong>
                <ul>
                    <li>Orders are available for pickup at the next market weekend in Diamond Valley.</li>
                    <li>Please provide your first and last name when you arrive.</li>
                </ul>
                <strong>Returns</strong>
                <ul>
                    <li>Returns and exchanges are handled directly by each <a href="shop.php?vendor=<?php echo $row['vendor_id']; ?>">vendor</a>.</li>
                </ul>
            </details>
        </div>
    </main> 
    <?php include("includes/footer.inc"); ?>
    <script src="scripts/thumbs.js"></script>
</body>
</html>