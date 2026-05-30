<?php
class SiteSettingModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->query('SELECT `key`, `value` FROM site_settings');
        $rows = $stmt->fetchAll();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }
    public function update(string $key, string $value): bool
    {
        $stmt = $this->db->prepare('UPDATE site_settings SET value = ? WHERE `key` = ?');
        return $stmt->execute([$value, $key]);
    }
}
