<?php

$errors = [];
$uploadDir = './uploads/';

if (isset($_POST['send'])) {

    $uploadDir = './uploads/';
    $uniqueName = uniqid('', true) . basename($_FILES['avatar']['name']);
    $uploadFile = $uploadDir . $uniqueName;
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $authorizedExtensions = ['jpg', 'png', 'gif', 'webp'];
    $maxFileSize = 1000000;
    $data = array_map('trim', $_POST);
    $data = array_map('htmlentities', $data);

    if (!isset($data['firstName']) || empty($data['firstName'])) {
        $errors[] = "Le champ prénom est obligatoire";
    }

    if (!isset($data['lastName']) || empty($data['lastName'])) {
        $errors[] = "Le champ nom est obligatoire";
    }

    if (empty($data['birthDate'])) {
        $errors[] = "Le champ date de naissance est obligatoire";
    }

    if ((!in_array($extension, $authorizedExtensions))) {
        $errors[] = 'Veuillez sélectionner une image de type Jpg ou Png ou gif ou webp !';
    }

    if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize || filesize($_FILES['avatar']['tmp_name']) == 0) {
        $errors[] = "Votre fichier doit faire moins de 1M !";
    }

    if (empty($errors)) {
        // var_dump($_FILES);
        // var_dump($data);
        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);
        echo "L'image a bien été ajouté !";
        // var_dump($uploadFile);
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Upload Quest</title>
</head>

<body>
    <br><br><br><br><br>
    <div class="container">
        <?php foreach ($errors as $error) : ?>
            <li><?= $error ?></li>
        <?php endforeach ?>
        <br><br><br><br>
        <form method="post" enctype="multipart/form-data">
            <label for="firstname">Firstname</label>
            <input type="text" name="firstName" required>
            <label for="lastname">Lastname</label>
            <input type="text" name="lastName" required>
            <label for="birthday">Date de naissance</label>
            <input type="date" name="birthDate" required>
            <label for="imageUpload">Upload an profile image</label>
            <input type="file" name="avatar" id="imageUpload" accept="image/png, image/jpeg, image/gif, image/webp">
            <button name="send">Send</button>

        </form>
    </div>
    <br>
    <br>
    <br><br><br><br><br>
    <div id="card">
        <div id="card-title">
            <h1>Springfield</h1>
        </div>
        <div id="card-id">
            <div id="card-number">
                <p class="cardtitle">Carte nationale d'identité :</p>
                <p>014564165155</p>
            </div>
        </div>
        <div id="card-information">
            <div id="card-photo">
                <img src="<?php if (isset($uploadFile)) {
                                echo $uploadFile;
                            } ?>">
            </div>
            <div id="card-text">
                <div id="card-name">
                    <div class="card-box"></div>
                    <p class="cardtitle"> Nom : </p>
                    <p><?php if (isset($data['firstName'])) {
                            echo $data['firstName'];
                        } ?> </p>
                    <div class="card-box"></div>
                    <p class="cardtitle">Prénom : </p>
                    <p><?php if (isset($data['lastName'])) {
                            echo $data['firstName'];
                        } ?></p>
                </div>
                <div id="card-detail">

                    <div class="card-box">
                        <p class="cardtitle">Né(e) le :</p>
                        <p><?php if (isset($data['birthDate'])) {
                                echo $data['birthDate'];
                            } ?></p>
                    </div>
                </div>
            </div>
        </div>
        <form method="post">
            <select name="delete">
                <?php
                $files = array_diff(scandir($uploadDir), array('..', '.'));
                foreach ($files as $file) :
                ?>
                    <option value="<?= $file ?>"> <?= $file ?></option>
                <?php endforeach ?>
            </select>
            <button type="submit">Delete</button>
            <br>
            <?php if (isset($_POST['delete'])) {
                $data = array_map('trim', $_POST);
                $data = array_map('htmlentities', $data);
                $fileName = $data['delete'];
                $fileUpload = $uploadDir . $fileName;
                if (file_exists($fileUpload)) {
                    if (unlink($fileUpload)) {
                        echo "Le fichier $fileName a bien été supprimé !";
                    }
                }
            } ?>
        </form>
    </div>
</body>

</html>