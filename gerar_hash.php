<?php
// Senha que você quer gerar o hash
$senha = 'farofada123';

// Gera o hash da senha usando bcrypt
$hash = password_hash($senha, PASSWORD_BCRYPT);

// Exibe o hash gerado
echo "O hash da senha é: " . $hash;
?>
