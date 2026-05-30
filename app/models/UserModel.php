<?php
class UserModel extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO users (name, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'] ?? 'user'
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET name = ?, phone = ? WHERE id = ?');
        return $stmt->execute([$data['name'], $data['phone'], $id]);
    }

    public function updateRole(int $id, string $role): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET role = ? WHERE id = ?');
        return $stmt->execute([$role, $id]);
    }

    public function allUsers(): array
    {
        $stmt = $this->db->query('SELECT id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }
}
