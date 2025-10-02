<?php
require_once __DIR__ . "/../templates/head.php";
require_once __DIR__ . "/../templates/header.php";
?>

<body>
    <?php echo $_ENV['VITE_DEV'] ?>
    <p>Vite PHP Kit</p>
    <a href="/contact">contact</a>
</body>

<?php
require_once __DIR__ . "/../templates/footer.php";
require_once __DIR__ . "/../templates/foot.php";
?>