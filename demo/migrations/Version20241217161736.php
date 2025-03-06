<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241217161736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the "user" table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            create table "users" (
                id          uuid not null,
                email       varchar(255) not null,
                password    varchar(255) not null,
                constraint  pk_user primary key (id)
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop table users');

    }
}
