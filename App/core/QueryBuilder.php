<?php
class QueryBuilder {
    private PDO $pdo;
    private string $table;
    private array $wheres = [];
    private array $params = [];
    private string $order = '';
    public function __construct(PDO $pdo, string $table) {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table)) throw new InvalidArgumentException('Nama tabel tidak valid');
        $this->pdo = $pdo; $this->table = $table;
    }
    public function where(string $column, string $operator, mixed $value): self {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column) ||
            !in_array($operator, ['=', '!=', 'LIKE', '>', '<', '>=', '<='], true)) {
            throw new InvalidArgumentException('Kondisi tidak valid');
        }
        $key = ':p' . count($this->params);
        $this->wheres[] = "`$column` $operator $key";
        $this->params[$key] = $value;
        return $this;
    }
    public function orderBy(string $column, string $direction = 'ASC'): self {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column) || !in_array(strtoupper($direction), ['ASC','DESC'], true))
            throw new InvalidArgumentException('Urutan tidak valid');
        $this->order = " ORDER BY `$column` " . strtoupper($direction);
        return $this;
    }
    public function get(): array {
        $sql = "SELECT * FROM `{$this->table}`";
        if ($this->wheres) $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        $sql .= $this->order;
        $stmt = $this->pdo->prepare($sql); $stmt->execute($this->params);
        return $stmt->fetchAll();
    }
    public function first(): ?array { return $this->get()[0] ?? null; }
}
