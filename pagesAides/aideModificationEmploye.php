<?php
$startTime = microtime(true); //temps de chargement de la page
require("../fonction/connexion.php");
session_start();
verif_session();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Aides Modification Employé</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <!-- CSS -->
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/footer.css">
        <!-- Icon du site -->
        <link rel="icon" href=" ../img/logo.ico">
    </head>
    <body>
        <div class="container-fluid">
            <!-- Header de la page -->
            <?php include '../include/header.php'; ?>

            <div class="full-screen mt-4">
                <div class="row text-center">
                    <h1>StatiSalle</h1>
                </div>

                <div class="row d-flex justify-content-center align-items-start w-100 mb-5">
                    <div class="acc-container p-4 w-50">
                        <p>
                            Pour effectuer une modification, il suffit de renseigner uniquement le champ que vous souhaitez modifier parmi les informations disponibles sur l'utilisateur. Par exemple, dans le cas d'un employé, il est possible de modifier son nom, son prénom, son numéro de téléphone, son nom d’utilisateur ou identifiant, ainsi que son mot de passe.
                            <br>
                            Il peut y avoir des erreurs comme lors d'un essai de modification sur un identifiant de compte doit être différent des identifiants de compte déjà présent dans la base de données.
                            <br>
                            Ou alors si un mot de passe est modifié, il faut qu'il respecte les conditions des mots de passe donc huit caractères minimum et au moins un caractère spécial.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>
