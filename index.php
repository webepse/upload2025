<?php
    // include("connexiongdf.php");
    require "connexion.php";

    if(isset($_GET['delete']) && is_numeric($_GET['delete']))
    {
        $id = htmlspecialchars($_GET['delete']);
        $verif = $bdd->prepare("SELECT * FROM products WHERE id=?");
        $verif->execute([$id]);
        if(!$donV = $verif->fetch())
        {
            header("LOCATION:index.php");
            exit();
        }

        unlink("images/".$donV['cover']);

        $delete = $bdd->prepare("DELETE FROM products WHERE id=?");
        $delete->execute([$id]);
        header("LOCATION:index.php?successDel=".$id);

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
    <h1>Formulaire upload</h1>
    <?php
        if(isset($_GET['error']))
        {
            echo "<div class='error'>Une erreur est survenue (code erreur: ".$_GET['error'].")</div>";
        }
        if(isset($_GET['upload']) && $_GET['upload']=="success")
        {
            echo "<div class='success'>Votre produit a bien été ajouté</div>";
        }
        if(isset($_GET['update']))
        {
            echo "<div class='update'>Vous avez bien modifié le produit n°".$_GET['update']."</div>";
        }
        if(isset($_GET['successDel']))
        {
            echo "<div class='delete'>Vous avez bien supprimé le produit n°".$_GET['successDel']."</div>";
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

    <?php
        $req = $bdd->query("SELECT * FROM products");
        // $don = $req->fetch();
        // var_dump($don);
        // $don2 = $req->fetch();
        // var_dump($don2);
        // $don3 = $req->fetch();
        // var_dump($don3);
        // while($don = $req->fetch(PDO::FETCH_ASSOC))
        // {
        //     var_dump($don);
        // }
        $dons =$req->fetchAll(PDO::FETCH_ASSOC);

        foreach($dons as $don)
        {
            echo "<div>";
                echo "<a href='edit.php?id=".$don['id']."'>".$don['nom']."</a>";
                echo "<a href='index.php?delete=".$don['id']."'>Supprimer</a>";
                echo "<img src='images/".$don['cover']."' alt='image de ".$don['nom']."'>";
            echo "</div>";
        }
        $req->closeCursor();
    ?>
</body>
</html>