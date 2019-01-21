<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190114181615 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD status VARCHAR(255) NOT NULL, ADD verify_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E99315F04E ON users (auth_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9737624E3 ON users (verify_code)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1483A5E99315F04E ON users');
        $this->addSql('DROP INDEX UNIQ_1483A5E9737624E3 ON users');
        $this->addSql('ALTER TABLE users DROP status, DROP verify_code');
    }
}
