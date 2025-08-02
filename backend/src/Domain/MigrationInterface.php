<?php
namespace SalesAppApi\Domain;

use SalesAppApi\Infrastructure\Database\Database;

interface MigrationInterface
{
    public function up(Database $db): void;
    public function down(Database $db): void;
}