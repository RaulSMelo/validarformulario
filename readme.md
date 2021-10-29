# PHP - Validar formulário HTML method POST

Classe simples para validar os valores de entrada dos inputs dos formulário HTML


## Instalação

Para instalar essa dependência basta executar o comando abaixo:

```shel
composer require raulsmelo/validarformulario
```

## Utilização

Para usar a classe basta seguir o exemplo abaixo:

```HTML
    <form method="POST">
        <input name="name-required@string">
        <input name="age-required@int">
        <input name="email@email">
        <input name="amount@float">
    </form>
    <!-- 
        Para que funcione corretamente o valor do atributo name do input deve ser como no exemplo acima
        ex: name="age-required@string"
        1 - (age) nome semântico do input que também sera a chave do novo array
        2 - (required) OBS: opcional, mais é nessessário quando o input deve ter um valor de entrada
        3 - (int) tipo do filtro a ser aplicado no PHP
     -->
```

```PHP
<?php 

 require_once __DIR__ . '/vendor/autoload.php';

 use RSM\ValidateForm;

/**
 * @param $_POST super global do PHP que contém todos o input que tem o atributo name declarado
 * 
 * $post recebe uma novo array com os valores filtrados
 * 
 * OBS: Caso alguns dos valores de entradas falhe na aplicação dos filtros e retornado false
 * */
 $post = (new ValidateForm($_POST))->getPostsValid();

```

## Requisitos
- Nessessário PHP 7.0 ou maior 