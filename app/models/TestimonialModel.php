<?php
class TestimonialModel extends Model
{
    public function approved(): array
    {
        $stmt = $this->db->query('SELECT * FROM testimonials WHERE approved = 1 ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function latest(int $limit = 5): array
    {
        $stmt = $this->db->prepare('SELECT * FROM testimonials ORDER BY created_at DESC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
