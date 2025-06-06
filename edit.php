<?php
    // vérif si id est présent dans l'url et s'il est numérique
    if(!isset($_GET['id']) || !is_numeric($_GET['id']))
    {
        header("LOCATION:index.php");
        exit();
    }
    $id = htmlspecialchars($_GET['id']);
    require "connexion.php";
    $req = $bdd->prepare("SELECT * FROM products WHERE id=?");
    $req->execute([$id]);
    if(!$don=$req->fetch(PDO::FETCH_ASSOC))
    {
        header("LOCATION:index.php");
        exit();
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <h1>Formulaire édition</h1>
    <?php
        if(isset($_GET['error']))
        {
            echo "<div class='error'>Une erreur est survenue (code erreur: ".$_GET['error'].")</div>";
        }
        if(isset($_GET['upload']) && $_GET['upload']=="success")
        {
            echo "<div class='success'>Votre produit a bien été ajouté</div>";
        }
    ?>
    <form action="treatment.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nom">nom: </label>
            <input type="text" name="nom" id="nom" value="<?= $don['nom'] ?>">
        </div>
        <div class="form-group">
            <label for="description">Description: </label>
            <textarea name="description" id="description"><?= $don['description'] ?></textarea>
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