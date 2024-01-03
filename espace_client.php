<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Espace Client</title>
    <link rel="stylesheet" href="StyleEC.css">

</head>
<body>
<div class="banniere">
    <?php
    session_start();
    if (isset($_SESSION['nom'])) {
        echo '<h1>Bienvenue ' . htmlspecialchars($_SESSION['nom']) . '</h1>';
    }
    ?>
</div>

<div class="messageBienv">
    <h1> Pret pour une petite gourmandise ? </h1>
</div>
<header>


</header>
</body>
</html>
