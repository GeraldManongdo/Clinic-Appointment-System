<?php
class EmailVerificationModel extends Model
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO email_verifications (email, code, expires_at, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$data['email'], $data['code'], $data['expires_at']]);
        return (int)$this->db->lastInsertId();
    }

    public function findValid(string $email, string $code): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM email_verifications WHERE email = ? AND code = ? AND expires_at >= NOW() ORDER BY created_at DESC LIMIT 1');
        $stmt->execute([$email, $code]);
        return $stmt->fetch() ?: null;
    }
}
