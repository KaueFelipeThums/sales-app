<?php
namespace SalesAppApi\Infrastructure\Database\Migrations;

use SalesAppApi\Domain\MigrationInterface;
use SalesAppApi\Infrastructure\Database\Database;

class CreateUsersTable implements MigrationInterface
{
    public function up(Database $db): void
    {
        $db->query(
            "CREATE TABLE IF NOT EXISTS users 
            (
                id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                is_active TINYINT NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NULL
            ) 
            ENGINE=InnoDB 
            DEFAULT CHARSET=utf8mb4 
            COLLATE=utf8mb4_general_ci;"
        );

        /**
         * Inserir um usuário padrão
         *
         */
        $db->query(
            "INSERT INTO 
                users (name, email, password, is_active, created_at) 
            VALUES
                (:name, :email, :password, :is_active, :created_at)",
            [
                'name' => 'Admin',
                'email' => 'test@example.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );
    }

    public function down(Database $db): void
    {
        $db->query("DROP TABLE IF EXISTS users");
    }
}