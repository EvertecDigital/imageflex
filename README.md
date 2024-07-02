# ImageFlex

[![Maintainer](http://img.shields.io/badge/maintainer-@evertecdigital-blue.svg?style=flat-square)](https://twitter.com/evertecdigital)
[![Source Code](http://img.shields.io/badge/source-coffeecode/imageflex-blue.svg?style=flat-square)](https://github.com/EvertecDigital/ImageFlex)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/coffeecode/imageflex.svg?style=flat-square)](https://packagist.org/packages/coffeecode/imageflex)
[![Latest Version](https://img.shields.io/github/release/EvertecDigital/ImageFlex.svg?style=flat-square)](https://github.com/EvertecDigital/ImageFlex/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/EvertecDigital/ImageFlex.svg?style=flat-square)](https://scrutinizer-ci.com/g/EvertecDigital/ImageFlex)
[![Total Downloads](https://img.shields.io/packagist/dt/coffeecode/imageflex.svg?style=flat-square)](https://packagist.org/packages/coffeecode/imageflex)

ImageFlex é uma classe PHP para gerenciamento e manipulação de imagens usando a biblioteca GD. Ela permite redimensionar imagens, adicionar marcas d'água e preservar a transparência das imagens originais.

## Funcionalidades

- Redimensionar imagens mantendo a proporção original
- Adicionar marcas d'água com posição e opacidade configuráveis
- Preservar a transparência de imagens PNG e GIF
- Suporte a vários formatos de imagem: JPEG, PNG, GIF, e WEBP
- Sistema de cache para melhorar o desempenho

## Requisitos

- PHP 7.4 ou superior
- Extensão GD habilitada no PHP

## Instalação

1. Faça o download do arquivo `ImageFlex.php` e coloque-o no seu projeto.
2. Certifique-se de que a extensão GD está habilitada no seu ambiente PHP.

## Uso

### Configurações Básicas

```php
// Inclua a classe ImageFlex
require_once 'ImageFlex.php';

// Configure a classe ImageFlex
ImageFlex::setOutputFormat('auto'); // Formato de saída automático (igual ao da imagem original)
ImageFlex::setQuality(85); // Qualidade de 85 para imagens JPEG e WEBP
ImageFlex::setCompression(6); // Nível de compressão de 6 para imagens PNG

$imagePath = 'images/example.jpg';

// Redimensione a imagem
$resizedImagePath = ImageFlex::resize($imagePath, 800, 600);

if ($resizedImagePath) {
    echo 'Imagem redimensionada e salva em: ' . $resizedImagePath;
} else {
    echo 'Erro ao redimensionar a imagem: ' . implode(', ', ImageFlex::getErrors());
}
```

## Diretamente na tag HTML

Simplifique utilizando diretamente na tag HTML.

```php
<img src="<?= ImageFlex::resize($imagePath, 800, 600);?>">
```

## Adicionar Marca d'Água

```php
$watermarkPath = 'images/watermark.png';

// Configure a marca d'água
ImageFlex::setWatermark($watermarkPath); // Defina a imagem da marca d'água
ImageFlex::setWatermarkOpacity(50); // Defina a opacidade da marca d'água para 50%
ImageFlex::setWatermarkPosition('bottom right'); // Defina a posição da marca d'água para canto inferior direito

// Redimensione a imagem e aplique a marca d'água
$resizedImagePath = ImageFlex::resize($imagePath, 800, 600);

if ($resizedImagePath) {
    echo 'Imagem redimensionada e salva em: ' . $resizedImagePath;
} else {
    echo 'Erro ao redimensionar a imagem: ' . implode(', ', ImageFlex::getErrors());
}
```

## Limpar Cache

Se necessário, podera criar uma rotina para limpar a pasta de cache periodicamente.

```php
// Limpar todas as imagens em cache
ImageFlex::clearCache();
```

## Métodos Disponíveis

| Metódo                                       | Parâmetro                                                                                                                                                           | Descrição                                                                              |
| :------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------ | :------------------------------------------------------------------------------------- |
| `setOutputFormat($format)`                   | string $format (opções: 'auto', 'jpg', 'png', 'webp', 'gif')                                                                                                        | Define o formato de saída para as imagens redimensionadas.                             |
| `setQuality($quality)`                       | int $quality (0-100)                                                                                                                                                | Define o nível de qualidade para as imagens JPEG e WEBP.                               |
| `setCompression($compression)`               | int $compression (0-9)                                                                                                                                              | Define o nível de compressão para as imagens PNG.                                      |
| `setWatermark($watermarkPath)`               | string $watermarkPath (caminho para a imagem da marca d'água)                                                                                                       | Define a imagem da marca d'água.                                                       |
| `setWatermarkOpacity($opacity)`              | int $opacity (0-100)                                                                                                                                                | Define a opacidade da marca d'água.                                                    |
| `setWatermarkPosition($position)`            | string $position (opções: 'top left', 'top', 'center', 'top right', 'middle left', 'middle center', 'middle right', 'bottom left', 'bottom center', 'bottom right') | Define a posição da marca d'água.                                                      |
| `resize($imagePath, $width, $height = null)` | string $imagePath (caminho para a imagem original) int $width (largura desejada) int OR null $height (altura desejada, opcional)                                    | Redimensiona uma imagem e salva a versão redimensionada no diretório de cache.         |
| `clearCache()`                               | -                                                                                                                                                                   | Limpa todas as imagens em cache.                                                       |
| `getErrors()`                                | -                                                                                                                                                                   | Retorna um array de mensagens de erro encontradas durante as operações. Retorno: array |

## Contribuição

Por favor, veja [CONTRIBUIÇÃO](https://github.com/EvertecDigital/ImageFlex/blob/master/CONTRIBUTING.md) para maiores detalhes.

## Creditos

- [Everson Aguiar](https://github.com/eversonaguiar) (Desenvolvedor)
- [Evertec Digital](https://github.com/evertecdigital) (Business)
- [All Contributors](https://github.com/EvertecDigital/ImageFlex/contributors) (This Project)

## Licença

A licença MIT (MIT). Consulte Arquivo de [Licença](https://github.com/EvertecDigital/ImageFlex/blob/master/LICENSE) para obter mais informações.
