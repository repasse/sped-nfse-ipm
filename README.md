# sped-nfse-ipm

Api para comunicação com webservices do Modelo IPM

## BETHA TESTES (necessito ajuda para realização de testes)

[![Latest Stable Version][ico-stable]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![License][ico-license]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

[![Issues][ico-issues]][link-issues]
[![Forks][ico-forks]][link-forks]
[![Stars][ico-stars]][link-stars]

Este pacote é aderente com os [PSR-1], [PSR-2] e [PSR-4]. Se você observar negligências de conformidade, por favor envie um patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

Não deixe de se cadastrar no [grupo de discussão do NFePHP](http://groups.google.com/group/nfephp) para acompanhar o desenvolvimento e participar das discussões e tirar duvidas!

## Dependências

- PHP >= 7.1
- ext-curl
- ext-soap
- ext-zlib
- ext-dom
- ext-openssl
- ext-json
- ext-simplexml
- ext-libxml

### Outras Libs

- nfephp-org/sped-common
- justinrainbow/json-schema

### Municipos Atendidos pelo modelo IPM

Os dados cadastrais dos municipios estão em [storage/municipios_ipm.json](storage/municipios_ipm.json)

|n|Município|UF|Ibge|Cidade|
|:---:|:---|:---:|:---:|:---:|
|1|AGROLANDIA|SC|4200200|8003|
|2|APIUNA|SC|4201257|9941|
|3|ARAPONGAS|PR|4101507|7427|
|4|ARAQUARI|SC|4201307|8025|
|5|ASCURRA|SC|4201703|8033|
|6|AURORA|SC|4201901|8037|
|7|BALNEARIO BARRA DO SUL|SC|4202057|5549|
|8|BALNEARIO PICARRAS|SC|4212809|0000|
|9|BARRA VELHA|SC|4202107|8041|
|10|BENEDITO NOVO|SC|4202206|8043|
|11|BIGUACU|SC|4202305|8045|
|12|BRUSQUE|SC|4202909|8055|
|13|CAMPO LARGO|PR|4104204|7481|
|14|CAMPO MOURAO|PR|4104303|7483|
|15|CANDELARIA|RS|4304200|8581|
|16|CASTRO|PR|4104907|7495|
|17|COLOMBO|PR|4105805|7513|
|18|CONCORDIA|SC|4204301|8083|
|19|DOUTOR PEDRINHO|SC|4205159|9945|
|20|ENEAS MARQUES|PR|4107405|7545|
|21|ESTRELA|RS|4307807|8653|
|22|ESTRELA VELHA|RS|4307815|0982|
|23|GARUVA|SC|4205803|8115|
|24|GRAVATAI|RS|4309209|8683|
|25|GUABIRUBA|SC|4206306|8123|
|26|GUAIRA|PR|4108809|7571|
|27|GUARAMIRIM|SC|4206504|8127|
|28|IBIRAMA|SC|4206900|8135|
|29|IGREJINHA|RS|4310108|8703|
|30|INDAIAL|SC|4207502|8147|
|31|IPORA DO OESTE|SC|4207650|9951|
|32|ITAPOA|SC|4208450|9985|
|33|ITUPORANGA|SC|4208500|8167|
|34|JANIOPOLIS|PR|4112207|7637|
|35|JOSE BOITEUX|SC|4209151|9957|
|36|LAURENTINO|SC|4209508|8187|
|37|LONTRAS|SC|4209904|8195|
|38|MAMBORE|PR|4114005|7673|
|39|MARECHAL CANDIDO RONDON|PR|4114609|7683|
|40|MARIPA|PR|4115358|5487|
|41|MASSARANDUBA|SC|4210605|8207|
|42|MERCEDES|PR|4115853|5531|
|43|NOVA CANTU|PR|4116802|7719|
|44|OURO|SC|4211801|8231|
|45|PALHOCA|SC|4211900|8233|
|46|PANAMBI|RS|4313904|8781|
|47|PARANAGUA|PR|4118204|7745|
|48|PAROBE|RS|4314050|9825|
|49|PINHAIS|PR|4119152|5453|
|50|POMERODE|SC|4213203|8259|
|51|PRESIDENTE GETULIO|SC|4214003|8275|
|52|RIO DAS ANTAS|SC|4214409|8283|
|53|RIO DO OESTE|SC|4214607|8287|
|54|RIO DO SUL|SC|4214805|8291|
|55|RIO DOS CEDROS|SC|4214706|8289|
|56|RODERIO|SC|0000000|0000|
|57|Rio Negrinho|SC|4215000|8295|
|58|SALETE|SC|4215307|8301|
|59|SANTA HELENA|PR|4123501|7971|
|60|SANTA ROSA|RS|4317202|8847|
|61|SAO FRANCISCO DO SUL|SC|4216206|8319|
|62|SOBRADINHO|RS|4320701|8917|
|63|TAIO|SC|4217808|8351|
|64|TELEMACO BORBA|PR|4127106|7915|
|65|TERRA BOA|PR|4127205|7917|
|66|TERRA ROXA|PR|4127403|7921|
|67|TIBAGI|PR|4127502|7923|
|68|TIMBO|SC|4218202|8357|
|69|TRES BARRAS|SC|4218301|8359|
|70|VIDEIRA|SC|4219309|8379|
|71|VITOR MEIRELES|SC|4219358|9977|
|72|WITMARSUM|SC|4219408|8381|

## Contribuindo
Este é um projeto totalmente *OpenSource*, para usa-lo e modifica-lo você não paga absolutamente nada. Porém para continuarmos a mante-lo é necessário qua alguma contribuição seja feita, seja auxiliando na codificação, na documentação ou na realização de testes e identificação de falhas e BUGs.

**Este pacote esta listado no [Packgist](https://packagist.org/) foi desenvolvido para uso do [Composer](https://getcomposer.org/), portanto não será explicitada nenhuma alternativa de instalação.**

*Durante a fase de desenvolvimento e testes este pacote deve ser instalado com:*
```bash
composer require nfephp-org/sped-nfse-ipm:dev-master
```

*Ou ainda,*
```bash
composer require nfephp-org/sped-nfse-ipm:dev-master --prefer-dist
```

*Ou ainda alterando o composer.json do seu aplicativo inserindo:*
```json
"require": {
    "nfephp-org/sped-nfse-ipm" : "dev-master"
}
```

> NOTA: Ao utilizar este pacote ainda na fase de desenvolvimento não se esqueça de alterar o composer.json da sua aplicação para aceitar pacotes em desenvolvimento, alterando a propriedade "minimum-stability" de "stable" para "dev".
> ```json
> "minimum-stability": "dev",
> "prefer-stable": true
> ```

*Após os stable realeases estarem disponíveis, este pacote poderá ser instalado com:*
```bash
composer require nfephp-org/sped-nfse-ipm
```
Ou ainda alterando o composer.json do seu aplicativo inserindo:
```json
"require": {
    "nfephp-org/sped-sped-nfse-ipm" : "^1.0"
}
```

## Forma de uso
vide a pasta *Examples*

## Log de mudanças e versões
Acompanhe o [CHANGELOG](CHANGELOG.md) para maiores informações sobre as alterações recentes.

## Testing

Todos os testes são desenvolvidos para operar com o PHPUNIT

## Security

Caso você encontre algum problema relativo a segurança, por favor envie um email diretamente aos mantenedores do pacote ao invés de abrir um ISSUE.

## Credits

Roberto L. Machado (owner and developer)

## License

Este pacote está diponibilizado sob LGPLv3 ou MIT License (MIT). Leia  [Arquivo de Licença](LICENSE.md) para maiores informações.


[ico-stable]: https://poser.pugx.org/nfephp-org/sped-nfse-ipm/version
[ico-stars]: https://img.shields.io/github/stars/nfephp-org/sped-nfse-ipm.svg?style=flat-square
[ico-forks]: https://img.shields.io/github/forks/nfephp-org/sped-nfse-ipm.svg?style=flat-square
[ico-issues]: https://img.shields.io/github/issues/nfephp-org/sped-nfse-ipm.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/nfephp-org/sped-nfse-ipm/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/nfephp-org/sped-nfse-ipm.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/nfephp-org/sped-nfse-ipm.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nfephp-org/sped-nfse-ipm.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/nfephp-org/sped-nfse-ipm.svg?style=flat-square
[ico-license]: https://poser.pugx.org/nfephp-org/nfephp/license.svg?style=flat-square
[ico-gitter]: https://img.shields.io/badge/GITTER-4%20users%20online-green.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/nfephp-org/sped-nfse-ipm
[link-travis]: https://travis-ci.org/nfephp-org/sped-nfse-ipm
[link-scrutinizer]: https://scrutinizer-ci.com/g/nfephp-org/sped-nfse-ipm/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nfephp-org/sped-nfse-ipm
[link-downloads]: https://packagist.org/packages/nfephp-org/sped-nfse-ipm
[link-author]: https://github.com/nfephp-org
[link-issues]: https://github.com/nfephp-org/sped-nfse-ipm/issues
[link-forks]: https://github.com/nfephp-org/sped-nfse-ipm/network
[link-stars]: https://github.com/nfephp-org/sped-nfse-ipm/stargazers
[link-gitter]: https://gitter.im/nfephp-org/sped-nfse-ipm?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge
