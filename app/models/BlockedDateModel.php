<?php
class BlockedDateModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM blocked_dates ORDER BY date_value DESC');
        return $stmt->fetchAll();
    }

    public function blockedDates(): array
    {
        $stmt = $this->db->query('SELECT date_value FROM blocked_dates');
        return array_column($stmt->fetchAll(), 'date_value');
    }

    public function blockedSlots(string $date): array
    {
        $stmt = $this->db->prepare('SELECT time_value FROM blocked_time_slots WHERE date_value = ?');
        $stmt->execute([$date]);
        return array_column($stmt->fetchAll(), 'time_value');
    }
}
