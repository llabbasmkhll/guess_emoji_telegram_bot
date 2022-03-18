<?php
namespace App\Model;

use PDO;

final class Level extends Model
{
    protected static $table = "levels";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "quest" => PDO::PARAM_STR,
        "answer" => PDO::PARAM_STR,
        "orders" => PDO::PARAM_INT,
        "difficulty" => PDO::PARAM_INT,
        "created_at" => PDO::PARAM_STR,
        "updated_at" => PDO::PARAM_STR,
    ];

    public function __construct()
    {
    }

    public function check_level(string $text): bool
    {
        if ($text == $this->answer) {
            return true;
        }
        return false;
    }

    public static function get_last_order()
    {
        $last = self::get_first("", [], "ORDER BY orders DESC");
        if (!$last || empty($last)) {
            return 0;
        }
        return $last->orders;
    }

    public function auto_generate_hints()
    {
    }
}
