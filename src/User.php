<?php

namespace Gravita\Http;

use JsonSerializable;
use Microwin7\PHPUtils\DB\DriverPDO;
use Microwin7\PHPUtils\DB\DriverMySQLi;
use Microwin7\TextureProvider\Texture\Texture;
use Gravita\Http\Exceptions\HttpErrorException;
use Microwin7\TextureProvider\Utils\RequestParams;
use Microwin7\TextureProvider\Data\User as TextureProviderUser;

class User implements JsonSerializable
{
    public static DriverPDO|DriverMySQLi $DB;

    public function __construct(
        public int $id,
        public string $username,
        public string $uuid,
        #[\SensitiveParameter]
        private string|null $password_hash = null
    ) {
    }
    public function verify_password($password): void
    {
        password_verify($password, $this->password_hash) ?: throw new HttpErrorException(AUTH_WRONG_PASSWORD);
    }
    public static function get_by_id(int $id): static|null
    {
        return User::read_from_row(static::$DB->query("SELECT * FROM users WHERE id = ?", "i", $id)->getStatementHandler()->fetch(\PDO::FETCH_OBJ));
    }
    public static function get_by_uuid($uuid): static|null
    {
        return User::read_from_row(static::$DB->query("SELECT * FROM users WHERE uuid = ?", "s", $uuid)->getStatementHandler()->fetch(\PDO::FETCH_OBJ));
    }
    public static function get_by_username($username): static|null
    {
        return User::read_from_row(
            static::$DB->query("SELECT * FROM users WHERE username = ?", "s", $username)->getStatementHandler()->fetch(\PDO::FETCH_OBJ)
        );
    }
    public static function read_from_row(object|false $row): static|null
    {
        if (!$row) return null;
        return new static($row->id, $row->username, $row->uuid, $row->password);
    }
    private function toArray(): array
    {
        return [
            "username" => $this->username,
            "uuid" => $this->uuid,
            "roles" => [],
            "permissions" => [],
            "assets" => new Texture(
                new TextureProviderUser(new RequestParams(uuid: $this->uuid, username: $this->username)),
                static::$DB
            ),
            "properties" => (object)[],
        ];
    }
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
