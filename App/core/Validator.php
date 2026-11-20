<?php
class Validator {
    private array $errors = [];
    public function required(array $data, array $fields): self {
        foreach ($fields as $field) if (!isset($data[$field]) || trim((string)$data[$field]) === '') $this->errors[] = "$field wajib diisi.";
        return $this;
    }
    public function numeric(array $data, array $fields): self {
        foreach ($fields as $field) if (($data[$field] ?? '') !== '' && !is_numeric($data[$field])) $this->errors[] = "$field harus berupa angka.";
        return $this;
    }
    public function inList(array $data, string $field, array $allowed): self {
        if (!in_array($data[$field] ?? null, $allowed, true)) $this->errors[] = "$field tidak valid.";
        return $this;
    }
    public function errors(): array { return $this->errors; }
    public function valid(): bool { return !$this->errors; }
}
