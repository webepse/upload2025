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

// tester si les infos sont envoyée
if(isset($_POST['nom']))
{
    // technique init error
    $err=0;

    if(empty($_POST['nom']))
    {
        $err = 1;
    }else{
        $nom = htmlspecialchars($_POST['nom']);
        // vérifier si nom existe
         if($nom != $don['nom'])
         {
             $req = $bdd->prepare("SELECT * FROM products WHERE nom=?");
             $req->execute([$nom]);
             $don = $req->fetch();
             if($don)
             {
                $err=3;
             }
         }
    }

    if(empty($_POST['description']))
    {
        $err = 2;
    }else{
        $description = htmlspecialchars($_POST['description']);
    }

    // vérifier s'il y a eu une erreur?
    if($err == 0)
    {
        // vérifier s'il y a une image
        if(isset($_FILES['image']) && !empty($_FILES['image']['tmp_name']))
        {
            // vérifier si l'image n'a pas d'erreur de transfert
            if($_FILES['image']['error']==0)
            {
                // gestion du fichier
                $dossier = 'images/';
                $fichier = basename($_FILES['image']['name']);
                $tailleMaxi = 2000000;
                $taille = filesize($_FILES['image']['tmp_name']);
                $extensions = ['.png', '.gif', '.jpg', '.jpeg'];
                $mimes = ["image/jpeg","image/gif","image/png"];
                $extension = strrchr($_FILES['image']['name'], '.');
                $mime = $_FILES['image']['type'];

                if($taille > $tailleMaxi)
                {
                    $err = "i5";
                }

                if(!in_array($extension,$extensions))
                {
                    $err = "i6";
                }

                if(!in_array($mime,$mimes))
                {
                    $err= "i7";
                }
                // revérif de $err mais cette fois-ci avec l'image
                if($err==0)
                {
                    //On formate le nom du fichier, strtr remplace tous les KK spéciaux en normaux suivant notre liste
                    // image crée.jpg
                    $fichier = strtr($fichier,
                    'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                    // image cree.jpg
                    $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier); 
                    // image-cree.jpg
                    // preg_replace remplace tout ce qui n'est pas un KK normal en tiret
                    $fichiercplt = rand().$fichier;
                    // 6156156156image-cree.jpg
                    // move_uploaded_file(c:/wamp64/tmp/tmpkjfdklsfjkldsf.tmp, images/6156156156image-cree.jpg)
                    if(move_uploaded_file($_FILES['image']['tmp_name'],$dossier.$fichiercplt))
                    {
                        // insertion dans la bdd
                       
                        $insert = $bdd->prepare("INSERT INTO products(nom,description,cover) VALUES(:nom,:descri,:cover)");
                        $insert->execute([
                            ":nom" => $nom,
                            ":descri" => $description,
                            ":cover" => $fichiercplt
                        ]);
                        header("LOCATION:index.php?upload=success");
                        exit();
                    }else{
                        header("LOCATION:index.php?error=i8");
                        exit();
                    }
                   
                }else{
                    // problème avec les tests sur l'image
                    header("LOCATION:index.php?error=".$err);
                    exit();
                }
                
                
            }else{
                // si une erreur au niveau du transfert 
                header("LOCATION:index.php?error=i".$_FILES['image']['error']);
                exit();
            }


        }else{
            // si pas d'image
            // edition sans image
            $update = $bdd->prepare("UPDATE products SET nom=:nom, description=:descri WHERE id=:myid");
            $update->execute([
                ":nom" => $nom,
                ":descri" => $description,
                ":myid" => $id
            ]);
            header("LOCATION:index.php?update=".$id);
        }



    }else{
        // redirection vers index avec en supplément l'indication de l'erreur (GET)
        header("LOCATION:index.php?error=".$err);
        exit();
    }

}else{
    // les informations ne sont pas envoyées donc redirection
    header("LOCATION:index.php");
    exit();
}

