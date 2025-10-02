<?php
require_once __DIR__ . "/../templates/head.php";
require_once __DIR__ . "/../templates/header.php";
?>

<body>
    <?php echo $_ENV['VITE_DEV'] ?>
    <p>PicNic by Mariel Nils. Distributed by velvetyne.fr.</p>
    <a href="/contact">contact</a>
</body>

<?php
require_once __DIR__ . "/../templates/footer.php";
require_once __DIR__ . "/../templates/foot.php";
?>