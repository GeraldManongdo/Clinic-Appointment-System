<?php
class ServiceModel extends Model
{
    public function all(int $limit = 6, int $offset = 0): array
    {
        $stmt = $this->db->prepare('SELECT * FROM services WHERE visible = 1 ORDER BY created_at DESC LIMIT ? OFFSET ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM services WHERE visible = 1')->fetchColumn();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM services WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function visible(): array
    {
        $stmt = $this->db->query('SELECT * FROM services WHERE visible = 1 ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function adminList(): array
    {
        $stmt = $this->db->query('SELECT * FROM services ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO services (title, description, image_path, visible, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$data['title'], $data['description'], $data['image_path'], $data['visible'] ? 1 : 0]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE services SET title = ?, description = ?, image_path = ?, visible = ? WHERE id = ?');
        return $stmt->execute([$data['title'], $data['description'], $data['image_path'], $data['visible'] ? 1 : 0, $id]);
    }

    public function toggle(int $id, int $visible): bool
    {
        $stmt = $this->db->prepare('UPDATE services SET visible = ? WHERE id = ?');
        return $stmt->execute([$visible, $id]);
    }
}
