<?php
    // include("connexiongdf.php");
    // require "connexion.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Formulaire upload</h1>
    <?php
        if(isset($_GET['error']))
        {
            echo "<div class='error'>Une erreur est survenue (code erreur: ".$_GET['error'].")</div>";
        }
    ?>
    <form action="treatment.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nom">nom: </label>
            <input type="text" name="nom" id="nom">
        </div>
        <div class="form-group">
            <label for="description">Description: </label>
            <textarea name="description" id="description"></textarea>
        </div>
        <div class="form-group">
            <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
            <label for="image">Image: </label>
            <input type="file" name="image" id="image">
        </div>
        <input type="submit" value="Envoyer">
    </form>
</body>
</html>