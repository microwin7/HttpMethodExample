<?php

namespace Gravita\Http;

use JsonSerializable;
use Gravita\Http\Utils;
use Gravita\Http\Config\Config;
use Gravita\Http\Exceptions\HttpErrorException;
use Microwin7\PHPUtils\DB\SingletonConnector;

class UserSession implements JsonSerializable
{
    public ?User $user = null;

    public function __construct(
        public int|null $id = null,
        public int|string $user_id,
        public string $access_token,
        public string $refresh_token,
        public string|null $server_id = null,
        public string $expire_in,
    ) {
    }

    public function refresh(): static
    {
        $this->access_token = Utils::generate_token();
        $this->refresh_token = Utils::generate_token();
        $this->expire_in = date("Y-m-d H:i:s", time() + Config::$sessionExpireSeconds);
        SingletonConnector::get()->query(
            "UPDATE user_sessions SET access_token = ?, refresh_token = ?, expire_in = ? WHERE id = ?",
            "sssi",
            $this->access_token,
            $this->refresh_token,
            $this->expire_in,
            $this->id
        );
        $this->expire_in = (int) date("U", strtotime($this->expire_in));
        return $this;
    }

    public function update_server_id(string $server_id): void
    {
        $this->server_id = $server_id;
        SingletonConnector::get()->query(
            "UPDATE user_sessions SET server_id = ? WHERE id = ?",
            "si",
            $this->server_id,
            $this->id
        );
    }

    public static function create_for_user(User $user): static
    {
        $session = new UserSession(
            id: null,
            user_id: $user->id,
            access_token: Utils::generate_token(),
            refresh_token: Utils::generate_token(),
            server_id: null,
            expire_in: date("Y-m-d H:i:s", time() + Config::$sessionExpireSeconds)
        );
        SingletonConnector::get()->query(
            "INSERT INTO user_sessions (user_id, access_token, refresh_token, expire_in)
        VALUES (?, ?, ?, ?)",
            "isss",
            $user->id,
            $session->access_token,
            $session->refresh_token,
            $session->expire_in
        );
        $session->id = SingletonConnector::get()->id();
        $session->user = $user;
        $session->expire_in = (int) date("U", strtotime($session->expire_in));
        return $session;
    }
    public static function delete_session(string|int $id): void
    {
        SingletonConnector::get()->query(
            "DELETE FROM user_sessions WHERE id = ?",
            "s",
            $id
        );
    }
    public static function exit_user(string $uuid): void
    {
        SingletonConnector::get()->query(
            "DELETE user_sessions FROM user_sessions
            JOIN users ON user_sessions.user_id = users.user_id WHERE users.uuid = ?",
            "s",
            $uuid
        );
    }

    public static function get_by_id(int $id): static|null
    {
        return UserSession::read_from_row(SingletonConnector::get()->query("SELECT * FROM user_sessions WHERE id = ?", "i", $id)->getStatementHandler()->fetch(\PDO::FETCH_OBJ));
    }

    public static function get_by_access_token_with_user(string $access_token): static|null
    {
        return UserSession::read_from_row(
            SingletonConnector::get()->query("SELECT user_sessions.id as session_id, users.id as id, username, uuid, access_token, refresh_token, server_id, expire_in, user_id, password
            FROM user_sessions
            JOIN users ON user_sessions.user_id = users.id
            WHERE access_token = ?", "s", $access_token)->getStatementHandler()->fetch(\PDO::FETCH_OBJ),
            true
        );
    }

    public static function get_by_refresh_token(string $refresh_token): static
    {
        return UserSession::read_from_row(
            SingletonConnector::get()->query("SELECT * FROM user_sessions WHERE refresh_token = ?", "s", $refresh_token)->getStatementHandler()->fetch(\PDO::FETCH_OBJ)
        ) ?: throw new HttpErrorException(AUTH_INVALID_TOKEN);
    }

    public static function get_by_server_id_and_username(string $server_id, string $username): static
    {
        return UserSession::read_from_row(
            SingletonConnector::get()->query("SELECT user_sessions.id as session_id, users.id as id, username, uuid, access_token, refresh_token, server_id, expire_in, user_id, password
            FROM user_sessions JOIN users ON user_sessions.user_id = users.id
            WHERE server_id = ? AND users.username = ?", "ss", $server_id, $username)->getStatementHandler()->fetch(\PDO::FETCH_OBJ),
            true
        ) ?: throw new HttpErrorException(SESSION_NOT_FOUND);
    }

    // ???
    public static function get_by_server_id_and_uuid(string $server_id, string $uuid): static|null
    {
        return UserSession::read_from_row(
            SingletonConnector::get()->query("SELECT user_sessions.id as session_id, users.id as id, username, uuid, access_token, refresh_token, server_id, expire_in, user_id, password
        FROM user_sessions
        JOIN users ON user_sessions.user_id = users.id
        WHERE server_id = ? AND users.uuid = ?", "ss", $server_id, $uuid)->getStatementHandler()->fetch(\PDO::FETCH_OBJ),
            true
        );
    }

    public static function read_from_row(object|false $row, bool $enableUser = false): static|null
    {
        if (!$row) return null;
        $session = new static(
            id: $enableUser ? $row->session_id : $row->id,
            user_id: $row->user_id,
            access_token: $row->access_token,
            refresh_token: $row->refresh_token,
            server_id: $row->server_id,
            expire_in: (int)date("U", strtotime($row->expire_in))
        );
        if ($enableUser) {
            $session->user = User::read_from_row($row);
        }
        return $session;
    }
    private function toArray(): array
    {
        $array = [
            "id" => $this->id,
            "accessToken" => $this->access_token,
            "refreshToken" => $this->refresh_token,
            "expire" => $this->expire_in - time()
        ];
        null === $this->user ?: $array['user'] = $this->user;
        return $array;
    }
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
