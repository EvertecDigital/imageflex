# ImageFlex

[![Maintainer](http://img.shields.io/badge/maintainer-@evertecdigital-blue.svg?style=flat-square)](https://twitter.com/evertecdigital)
[![Source Code](http://img.shields.io/badge/source-evertecdigital/imageflex-blue.svg?style=flat-square)](https://github.com/evertecdigital/imageflex)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/evertecdigital/imageflex.svg?style=flat-square)](https://packagist.org/packages/evertecdigital/imageflex)
[![Latest Version](https://img.shields.io/github/release/evertecdigital/imageflex.svg?style=flat-square)](https://github.com/evertecdigital/imageflex/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/evertecdigital/imageflex.svg?style=flat-square)](https://packagist.org/packages/evertecdigital/imageflex)

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

### Via composer

```bash
"evertecdigital/imageflex": "1.0.*"
```

ou execute

```bash
composer require evertecdigital/imageflex
```

### Via download direto

1. Faça o download do arquivo `imageflex.php` e coloque-o no seu projeto.
2. Certifique-se de que a extensão GD está habilitada no seu ambiente PHP.

## Uso

### Configurações Básicas

```php
// Inclua a classe imageflex
require_once 'imageflex.php';

// Configure a classe imageflex
imageflex::setOutputFormat('auto'); // Formato de saída automático (igual ao da imagem original)
imageflex::setQuality(85); // Qualidade de 85 para imagens JPEG e WEBP
imageflex::setCompression(6); // Nível de compressão de 6 para imagens PNG

$imagePath = 'images/example.jpg';

// Redimensione a imagem
$resizedImagePath = imageflex::resize($imagePath, 800, 600);

if ($resizedImagePath) {
    echo 'Imagem redimensionada e salva em: ' . $resizedImagePath;
} else {
    echo 'Erro ao redimensionar a imagem: ' . implode(', ', imageflex::getErrors());
}
```

## Diretamente na tag HTML

Simplifique utilizando diretamente na tag HTML.

```php
<img src="<?= imageflex::resize($imagePath, 800, 600);?>">
```

## Adicionar Marca d'Água

```php
$watermarkPath = 'images/watermark.png';

// Configure a marca d'água
imageflex::setWatermark($watermarkPath); // Defina a imagem da marca d'água
imageflex::setWatermarkOpacity(50); // Defina a opacidade da marca d'água para 50%
imageflex::setWatermarkPosition('bottom right'); // Defina a posição da marca d'água para canto inferior direito

// Redimensione a imagem e aplique a marca d'água
$resizedImagePath = imageflex::resize($imagePath, 800, 600);

if ($resizedImagePath) {
    echo 'Imagem redimensionada e salva em: ' . $resizedImagePath;
} else {
    echo 'Erro ao redimensionar a imagem: ' . implode(', ', imageflex::getErrors());
}
```

## Limpar Cache

Se necessário, podera criar uma rotina para limpar a pasta de cache periodicamente.

```php
// Limpar todas as imagens em cache
imageflex::clearCache();
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

Por favor, veja [CONTRIBUIÇÃO](https://github.com/evertecdigital/imageflex/blob/master/CONTRIBUTING.md) para maiores detalhes.

## Creditos

- [Everson Aguiar](https://github.com/eversonaguiar) (Desenvolvedor)
- [Evertec Digital](https://github.com/evertecdigital) (Business)
- [All Contributors](https://github.com/evertecdigital/imageflex/contributors) (This Project)

## Licença

A licença MIT (MIT). Consulte Arquivo de [Licença](https://github.com/evertecdigital/imageflex/blob/master/LICENSE) para obter mais informações.
