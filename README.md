#Página de Marcas

Listagem de produtos referentes a marca, é uma listagem com navegação de camada, seo amigavel e mais...

#Destaques
- Sincromização das marcas pelo atributo
- Sincronização da listagem de produto da marca
- Camada de navegação
- Inclução das páginas no sitemap
- Logo (ou nome) da marca na página de produto
- Url amigável
- SEO (incluíndo url canonical)
- Possibilidade de configuração de tema para a página
- Possibilidade de atualização de Layout da Página
- Uso de templates do próprio catalogo de produtos (não requer personalização adcional) o comportamento das páginas de marca é igual ao da categoria.

#Instalação
```
composer require o2ti/shopbybrand
```

Registrar o módulo
```
bin/magento setup:upgrade --clear-static-content	
```
#Configuração

Por padrão é usado o atributo Manufacturer (nativo) no entanto é necessário ativar a opção "Used in Product Listing" para obter a listagem de produtos na página.
Após criar uma opção para esse atributo basta sincronizar:
Catalog -> Brand -> "Re-Sync Manufacturer Attribute"
