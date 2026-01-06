<?php
session_start();
if (isset($_POST['id'])) {
    // If there IS an id posted:
    $variantID = $_POST['id'];
}
if (!isset($_SESSION['variants']) && isset($_POST['id'])) {
    // If there is NO session and an id IS posted:
    $_SESSION['variants'] = $variantID;
} elseif (isset($_SESSION['variants']) && isset($_POST['id'])) {
    // If there IS a session and an id IS posted:
    if (!str_contains($_SESSION['variants'], $variantID)) {
        // If the session does NOT contain the id posted:
        $_SESSION['variants'] .= ", ".$variantID;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | wildrosefoundry.ca</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <script src='https://cdn.jsdelivr.net/npm/temporal-polyfill@0.3.0/global.min.js'></script> <!-- Temporal Polyfill so it works on any browser. -->
</head>
<body>
    <?php
    require("includes/connect.php");
    include("includes/header.inc");

    if (isset($_SESSION['variants'])) {
        // If there IS a session:
        $varSession = $_SESSION['variants'];
        $cartvariants = explode(", ", $varSession);
    ?>
        <main id="checkout">
            <h1>Checkout</h1>
            <details open>
                <summary>Order Summary</summary>
                <?php // For Each Product-Variant
                for ($i=0; $i<(count($cartvariants)); $i++) {
                    $varID = $cartvariants[$i];

                    $query = 
                        "SELECT * FROM variants va
                        JOIN products p ON va.product_id = p.product_id
                        JOIN vendors ve ON p.vendor_id = ve.vendor_id
                        WHERE va.variant_id = $varID;";
                    $sql = mysqli_query($connect, $query);
                    $row = mysqli_fetch_assoc($sql);

                    echo '<article><img src="images/products/productoverview/'.$row['variant_img'].'" alt="'.$row['variant_img_alt'].'" width="85" height="85">';
                    echo '<div>';
                    echo '<span class="price">'.$row['price'].'</span>';
                    echo '<h3>'.$row['product_name'];
                    if ($row['variant_value'] !== "") {
                        echo ' - '.$row['variant_value'];
                    }
                    echo '</h3><h4>'.$row['vendor_name'].'</h4>';
                    echo '</div></article>';
                }

                // SUM Query
                $sumquery = 
                    "SELECT SUM(p.price) AS total_price FROM products p
                    JOIN variants v ON p.product_id = v.product_id
                    WHERE v.variant_id IN ($varSession)";
                $sumsql = mysqli_query($connect, $sumquery);
                $sumrow = mysqli_fetch_assoc($sumsql);
                $sum = $sumrow['total_price'];
                ?>
                <table>
                    <tbody>
                        <tr>
                            <td>Sub Total</td>
                            <td>$<?php echo $sum; ?></td>
                        </tr>
                        <tr>
                            <td>Estimated Taxes</td>
                            <td>$<?php echo number_format(($sum * 0.05), 2); // GST in Alberta ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row">Total</th>
                            <td>$<?php echo number_format(($sum * 1.05), 2); ?></td>
                        </tr>
                    </tfoot>
                </table>       
            </details>
            <form action="confirmation.php" method="post">
                <div>
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9" stroke="#C48A51" stroke-width="1.5"/>
                        <line x1="12" y1="10" x2="12" y2="16" stroke="#C48A51" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="12" cy="7" r="1.1" fill="#C48A51"/>
                    </svg>
                    <h2>Pickup Information</h2>
                    <p>Orders are available for pickup at the next market weekend in Diamond Valley. Please provide your first and last name when you arrive.</p>
                </div>
                <fieldset>
                    <legend>Personal Information</legend>
                    <div class="overlap ol-center">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="flex">
                        <div class="overlap ol-center">
                            <label for="first">First name</label>
                            <input type="text" name="first" id="first" required>
                        </div>
                        <div class="overlap ol-center">
                            <label for="last">Last name</label>
                            <input type="text" name="last" id="last" required>
                        </div>
                    </div>
                    <div class="overlap ol-center">
                        <label for="phone">Phone</label>
                        <input type="tel" name="phone" id="phone" required>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Payment Information</legend>
                    <div class="overlap ol-center">
                        <label for="credit">Card number</label>
                        <input type="number" name="credit" id="credit">
                    </div>
                    <div class="flex">
                        <div class="overlap ol-center">
                            <label for="expiry">Expiration date (MM/YY)</label>
                            <input type="number" name="expiry" id="expiry">
                        </div>
                        <div class="overlap ol-center">
                            <label for="CVV">Security code</label>
                            <input type="number" name="CVV" id="CVV">
                        </div>
                    </div>
                    <div class="overlap ol-center">
                        <label for="cardholder">Name on card</label>
                        <input type="text" name="cardholder" id="cardholder">
                    </div>
                </fieldset>
                <input type="submit" value="Checkout" class="primary-btn">
                <small>Your customer info and order summary will be saved. No payment info is saved and is not required to fill out the form.</small>
            </form>
        </main>
    <?php } else {
        // If there is NO session:
        echo '<main id="noProducts">';
        echo '<img src="images/no-cart.webp" alt="Illustration of an empty shopping cart." width="1024" height="1024">';
        echo '<h1>Your Cart is Empty</h1>';
        echo '<p>There are no items in your cart.</p>';
        echo '<a href="shop.php" class="primary-btn">Shop Products</a>';
        echo '</main>';
    } ?>
    <?php include("includes/footer.inc"); ?>
    <script src="scripts/checkout.js"></script>
</body>
</html>