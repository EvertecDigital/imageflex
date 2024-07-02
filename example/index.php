<?php
require_once ('ImageFlex.php');

$watermarkPath = 'images/approved.png';

// Configure a classe ImageFlex
ImageFlex::setOutputFormat('auto'); // Formato de saída automático (igual ao da imagem original)
ImageFlex::setQuality(85); // Qualidade de 85 para imagens JPEG e WEBP
ImageFlex::setCompression(6); // Nível de compressão de 6 para imagens PNG
ImageFlex::setWatermark($watermarkPath); // Defina a imagem da marca d'água
ImageFlex::setWatermarkOpacity(100); // Defina a opacidade da marca d'água para 50%
ImageFlex::setWatermarkPosition('middle center'); // Defina a posição da marca d'água para canto inferior direito
?>

<html>

    <head>
        <title>ImageFlex - Dynamic Image Resizer and Cache Manager</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>

    <body class="text-bg-dark">
        <div class="container py-5 text-center">
            <h1>ImageFlex - Dynamic Image Resizer and Cache Manager</h1>

            <h2 class="mt-5">Image png 1920X1080</h2>
            <img class="img-fluid" src="<?= ImageFlex::resize('images/beach.png', 1920, 1080); ?>" title="ImageFlex" />


            <h2 class="mt-5">Image jpg 1920X1080</h2>
            <img  class="img-fluid"  src="<?= ImageFlex::resize('images/beach.jpg', 1920, 1080); ?>" title="ImageFlex" />

            <h2 class="mt-5">Image webp 1920X1080</h2>
            <img  class="img-fluid"  src="<?= ImageFlex::resize('images/beach.webp', 1920, 1080); ?>" title="ImageFlex" />


            <h2 class="mt-5">Image 1080 x 1920</h2>
            <img  class="img-fluid"  src="<?= ImageFlex::resize('images/beach.jpg', 1080, 1920); ?>" title="ImageFlex" />

            <h2 class="mt-5">Image 800 x 800</h2>
            <img  class="img-fluid"  src="<?= ImageFlex::resize('images/beach.jpg', 800, 800); ?>" title="ImageFlex" />

              <h2 class="mt-5">Image 800 x auto</h2>
            <img  class="img-fluid"  src="<?= ImageFlex::resize('images/beach.jpg', 1080); ?>" title="ImageFlex" />
        </div>
    </body>
</html>