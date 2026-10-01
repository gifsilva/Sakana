<?php 
class InputValidator {
    private $errors = [];

    public function notEmpty(string $fieldName, string $value, int $minLength = 1): self {
        $trimmed = trim($value);

        if (strlen($trimmed) < $minLength) {
            $this->errors[$fieldName] = "Campo obrigatório";
        }
        return $this;
    }

    public function email(string $fieldName, string $value): self {
        $trimmed = trim($value);
        if (!filter_var($trimmed, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$fieldName] = "Email inválido.";
        }
        return $this;
    }

    public function cpf(string $fieldName, string $value): self {
        // remove caracteres especiais
        $cpf = preg_replace("/[^0-9]/", "", $value);

        // Valida se tem 11 dígitos
        if (strlen($cpf) !== 11) {
            $this->errors[$fieldName] = "CPF deve conter 11 dígitos.";
            return $this;
        }

        /* // Verifica se não é uma sequência repetida (ex 111.111.111-11)
        if (preg_match("/^(\d)\1{10}$/", $cpf)) {
            $this->errors[$fieldName] = "CPF inválido.";
            return $this;
        } */

        /* // Calcula o primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $primeiroDigito = 11 - ($soma % 11);
        $primeiroDigito = ($primeiroDigito >= 10) ? 0 : $primeiroDigito;

        // Valida o primeiro dígito
        if ($cpf[9] != $primeiroDigito) {
            $this->errors[$fieldName] = "CPF inválido.";
            return $this;
        }

        // Calcula o segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }

        $segundoDigito = 11 - ($soma % 11);
        $segundoDigito = ($segundoDigito >= 10) ? 0 : $segundoDigito;

        // Valida o segundo dígito
        if ($cpf[10] != $segundoDigito) {
            $this->errors[$fieldName] = "CPF inválido.";
            return $this;
        } */

        return $this;
    }

    public function minLength(string $fieldName, string $value, int $minLength): self {
        if (strlen($value) < $minLength) {
            $this->errors[$fieldName] = "Mínimo {$minLength} caracteres.";
        }
        return $this;
    }

    public function match(string $fieldName, string $value1, string $value2, string $label = "Valores"): self {
        if ($value1 !== $value2) {
            $this->errors[$fieldName] = "{$label} não conferem.";
        }
        return $this;
    }

    public function isValid(): bool {
        return empty($this->errors);
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function getFirstError(): ?string {
        return empty($this->errors) ? null : reset($this->errors);
    }

    public function clear(): self {
        $this->errors = [];
        return $this;
    }
}
?>