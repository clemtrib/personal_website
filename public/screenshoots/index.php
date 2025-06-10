<?php

// Définition des extensions autorisées
$extensions = array('jpg', 'jpeg', 'png', 'gif');

// Récupération du répertoire courant
$repertoire = dirname(__FILE__) . '/';
$images = array();

// Ouverture du répertoire
if ($handle = opendir($repertoire)) {
    while (($entry = readdir($handle)) !== false) {
        $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
        if (in_array($extension, $extensions)) {
            $images[] = $entry;
        }
    }
    closedir($handle);
}

// Tri des images par ordre alphabétique descendant (modifiable selon besoin)
sort($images);

// Affichage de la galerie
echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Captures d\'écran</title><style>';
echo 'body {font-family: Arial, sans-serif; margin: 20px;}';
echo '.gallery {display: flex; flex-wrap: wrap; gap: 10px;}';
echo '.gallery img {max-width: 250px; height: auto; border: 1px solid #ddd; border-radius: 4px;}';
echo '</style></head><body><h1>Captures d\'écran</h1><div class="gallery">';

foreach ($images as $image) {
    echo '<a href="./view.php?image=' . urlencode($image) . '">';
    echo '<img src="./' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($image) . '" />';
    echo '</a>';
}

echo '</div></body></html>';
