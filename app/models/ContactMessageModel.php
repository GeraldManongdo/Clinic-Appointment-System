<?php
class ContactMessageModel extends Model
{
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())');
        return $stmt->execute([$data['name'], $data['email'], $data['subject'], $data['message']]);
    }

    public function latest(int $limit = 10): array
    {
        $stmt = $this->db->prepare('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
