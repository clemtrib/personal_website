<?php
if (isset($_GET['image']) && !empty($_GET['image'])) {
    $image = basename($_GET['image']);
    $extensions = array('jpg', 'jpeg', 'png', 'gif');
    $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    if (in_array($extension, $extensions) && file_exists($image)) {
        // Déterminer le type MIME de l'image (optionnel, mais recommandé pour l’intégrité du contenu)
        $imageType = exif_imagetype($image);
        $mimeType = image_type_to_mime_type($imageType);
        // Récupérer la taille de l'image[8]
        $size = getimagesize($image);

        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Visualisation de l\'image</title>';
        echo '<style>body {background:#f5f5f5; text-align:center; padding:20px;}</style></head><body>';
        echo '<h1>' . htmlspecialchars($image) . '</h1>';
        echo '<img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($image) . '" style="max-width:90vw; max-height:80vh; border:1px solid #ddd; border-radius:4px;"/>';
        echo '<p>Taille : ' . $size[0] . ' x ' . $size[1] . ' px</p>';
        echo '<p><a href="./">Retour à la galerie</a></p>';
        echo '</body></html>';
    } else {
        // Sécurité : si l’image n’existe pas ou n’est pas autorisée
        header('HTTP/1.0 404 Not Found');
        echo '<p>Image non trouvée ou non autorisée.</p>';
    }
} else {
    header('Location: ./');
}
