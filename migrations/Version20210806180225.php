<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210806180225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actu_image DROP FOREIGN KEY FK_D0CC93DFA2843073');
        $this->addSql('DROP INDEX UNIQ_D0CC93DFA2843073 ON actu_image');
        $this->addSql('ALTER TABLE actu_image DROP actualite_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actu_image ADD actualite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE actu_image ADD CONSTRAINT FK_D0CC93DFA2843073 FOREIGN KEY (actualite_id) REFERENCES actualites (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D0CC93DFA2843073 ON actu_image (actualite_id)');
    }
}
