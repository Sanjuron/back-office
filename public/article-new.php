<?php

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../templates');

// instanciation du moteur de template
$twig = new \Twig\Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true, 
]);

// chargement de l'extension Twig_Extension_Debug
$twig->addExtension(new \Twig\Extension\DebugExtension());

dump($_POST);


$formData = [
    'name' => '',
    'price'  => '',
    'quantity' => '',
    'description' => '',
];


$errors = [];
$messages = [];


if($_POST) {
    
    if (isset($_POST['name'])) {
        $formData['name'] = $_POST['name'];
    }

    if (isset($_POST['price'])) {
        $formData['price'] = $_POST['price'];
    }

    if (isset($_POST['quantity'])) {
        $formData['quantity'] = $_POST['quantity'];
    }

    if (isset($_POST['description'])) { // pas certain
        $formData['description'] = $_POST['description'];
    }


    //vérification du name
    if (!isset($_POST['name']) || empty($_POST['name'])) {
        $errors['name'] = true;
        $messages['name'] = "Merci de renseigner le nom";
    } elseif (strlen($_POST['name']) < 2) {
        $errors['name'] = true;
        $messages['name'] = "Le nom doit faire 2 caractères minimum";
     } elseif (strlen($_POST['name']) > 100) {
        $errors['name'] = true;
        $messages['name'] = "Le nom doit faire 100 caractères maximum";
    }

    //vérification du price
    if (!isset($_POST['price']) || empty($_POST['price'])) {
        $errors['price'] = true;
        $messages['price'] = "Merci de renseigner le prix";
    } elseif (!is_numeric($_POST['price'])) {
        $errors['price'] = true;
        $messages['price'] = "Veuillez entrer un nombre";

    //vérification quantity
    if (!isset($_POST['quantity']) || empty($_POST['quantity'])) {
        $errors['quantity'] = true;
        $messages['quantity'] = "Merci de renseigner la quantité";
    } elseif (!is_int($_POST['quantity'])) {
        $errors['quantity'] = true;
        $messages['quantity'] = "Veuillez entrer un nombre entier";
     } elseif (($_POST['quantity']) < 0) { // sûrement pas bon
        $errors['quantity'] = true;
        $messages['quantity'] = "Veuillez entrer un nombre supérieur à 100";
    }


    //vérification de description si non vide
    if (isset($_POST['description'])) {
        if (strpos($_POST['description'], '<') || strpos($_POST['description'], '>')) {
            $errors['description'] = true;
            $messages['description'] = "Le nom doit faire 100 caractères maximum";
        }
    }


    if(!errors){

        session_start();

        $url = 'articles.php';
        header("Location: {$url}", true, 302);
        exit();

    }



// affichage du rendu d'un template
echo $twig->render('article-new.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'messages' => $messages,
    'formData' => $formData,
]);