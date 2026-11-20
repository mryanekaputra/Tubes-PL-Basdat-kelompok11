<?php
abstract class BaseModel {
    protected PDO $db;
    protected string $table;
    protected string $primaryKey;
    public function __construct() { $this->db = Database::getConnection(); }
    public function all(): array {
        return (new QueryBuilder($this->db, $this->table))->orderBy($this->primaryKey, 'DESC')->get();
    }
    public function find(int $id): ?array {
        return (new QueryBuilder($this->db, $this->table))->where($this->primaryKey, '=', $id)->first();
    }
    public function create(array $data): int {
        $columns = array_keys($data);
        $sql = "INSERT INTO `{$this->table}` (`" . implode('`,`', $columns) . "`) VALUES (" .
            implode(',', array_fill(0, count($columns), '?')) . ")";
        $stmt = $this->db->prepare($sql); $stmt->execute(array_values($data));
        return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $data): bool {
        $sets = array_map(fn($c) => "`$c` = ?", array_keys($data));
        $sql = "UPDATE `{$this->table}` SET " . implode(', ', $sets) . " WHERE `{$this->primaryKey}` = ?";
        return $this->db->prepare($sql)->execute([...array_values($data), $id]);
    }
    public function delete(int $id): bool {
        return $this->db->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?")->execute([$id]);
    }
}
