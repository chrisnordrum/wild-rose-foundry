<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | wildrosefoundry.ca</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <script src='https://cdn.jsdelivr.net/npm/temporal-polyfill@0.3.0/global.min.js'></script> <!-- Temporal Polyfill so it works on any browser. -->
</head>
<body>
    <?php require("includes/header.inc"); ?>
    <main id="about" class="image-text">
        <picture>
            <source media="(min-width: 768px)" srcset="images/interior.webp">
            <source srcset="images/interior1.webp">
            <img src="wildrose-images/interior1.webp" alt="Interior of the Wild Rose Foundry artisan market in Alberta, showing vendors selling ceramics and handmade goods inside a warm, rustic warehouse with shoppers walking through the aisles." width="1536" height="1024">
        </picture>
        <div>
            <h1>About Us</h1>
            <p><strong>Wild Rose Foundry</strong> is a vibrant artisan collective in the heart of Alberta, dedicated to showcasing the creativity and craftsmanship of local makers. Each weekend, our converted warehouse comes alive with the buzz of shoppers exploring ceramics, wood goods, textiles, home fragrances, and leather goods crafted by independent vendors from across the province. Visitors come for the quality but stay for the community, discovering new makers, stories, and favourites with every visit.</p>
            <p>Currently rooted in Diamond Valley, Wild Rose Foundry is proudly growing and plans to expand to multiple locations in Alberta by 2028.</p>
        </div>
    </main>
    <?php include("includes/footer.inc"); ?>
</body>
</html>