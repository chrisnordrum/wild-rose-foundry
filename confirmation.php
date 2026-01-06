<?php
session_start();

require("includes/connect.php");

$email = mysqli_real_escape_string($connect, $_POST['email']);
$first = mysqli_real_escape_string($connect, $_POST['first']);
$last = mysqli_real_escape_string($connect, $_POST['last']);
$phone = mysqli_real_escape_string($connect, $_POST['phone']);
date_default_timezone_set('America/Edmonton');
$date = date('Y-m-d H:i:s');

$query = "INSERT INTO orders (first, last, customer_email, phone, date) VALUES ('$first', '$last', '$email', '$phone', '$date')";
$sql = mysqli_query($connect, $query);

$orderquery = "SELECT order_id FROM orders WHERE date = '$date'";
$ordersql = mysqli_query($connect, $orderquery);
$row = mysqli_fetch_assoc($ordersql);
$orderID = $row['order_id'];

if (isset($_SESSION['variants'])) {
    // If there IS a session:
    $varSession = $_SESSION['variants'];
    $cartvariants = explode(", ", $varSession);

    // For Each Product-Variant
    for ($i=0; $i<(count($cartvariants)); $i++) {
        $varID = $cartvariants[$i];

        $query = 
            "INSERT INTO variantsorders (variant_id, order_id, quantity) VALUES ('$varID', '$orderID', 1)";
        $sql = mysqli_query($connect, $query);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation | wildrosefoundry.ca</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <script src='https://cdn.jsdelivr.net/npm/temporal-polyfill@0.3.0/global.min.js'></script> <!-- Temporal Polyfill so it works on any browser. -->
</head>
<body>
    <?php include("includes/header.inc"); ?>
    <main id="confirmation">
        <div>
            <section id="confirmation-header">
                <svg width="72" height="72" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="9" stroke="#C48A51" stroke-width="1"/>
                    <path d="M8 12L11 15L16 9" stroke="#C48A51" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                    <span>Order #<?php echo $orderID; ?></span>
                    <h1>Thank you, <?php echo $first; ?>!</h1>
                </div>
            </section>
            <section>
                <h2>Your order is confirmed</h2>
                <p>You'll receive a confirmation email with your order number shortly.</p>
            </section>
            <section>
                <h2>Pickup Information</h2>
                <p>Orders are available for pickup at the next market weekend in Diamond Valley. Please provide your first and last name when you arrive.</p>
            </section>
            <section>
                <h2>Personal Information</h2>
                <?php
                echo "<span>$first $last</span>";
                echo "<span>$email</span>";
                echo "<span>$phone</span>";
                ?>
            </section>
        </div>
        <details open class="cart">
            <summary>Order Summary</summary>
            <?php
            if (isset($_SESSION['variants'])) {
                // If there IS a session:
                $varSession = $_SESSION['variants'];
                $cartvariants = explode(", ", $varSession);
                
                // For Each Product-Variant
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
            <?php } ?>
        </details>
    </main>
    <?php include("includes/footer.inc"); ?>
</body>
</html>
<?php session_destroy(); ?>