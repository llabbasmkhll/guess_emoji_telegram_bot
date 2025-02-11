<?php
namespace App\Model;

use App\Helper\OutputHelper;
use PDO;

final class Level extends Model
{
    protected static $table = "levels";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "quest" => PDO::PARAM_STR,
        "answer" => PDO::PARAM_STR,
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

    public function auto_generate_hints()
    {
    }

    public function hint(int $_order)
    {
        return LevelHint::get_first(
            "WHERE level_id=:level_id AND orders>:orders",
            [":level_id" => $this->id, ":orders" => $_order],
            "ORDER BY orders ASC, id DESC"
        );
    }

    public function prize(): int
    {
        return 18 + 5 * $this->difficulty;
    }

    public function on_create()
    {
        // Get all users whom has no any level to play
        $users = User::get_all("WHERE level_id IS NULL", []);
        foreach ($users as $user) {
            $user->level_id = $this->id;
            $user->save();
            // Notif to user for new mission
            OutputHelper::new_level($user);
        }
    }
}
