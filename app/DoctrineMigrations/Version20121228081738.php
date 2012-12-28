<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20121228081738 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE StoryEstimateByUser (id INT AUTO_INCREMENT NOT NULL, story_id INT DEFAULT NULL, user_id INT DEFAULT NULL, estimate INT NOT NULL, isEstimated TINYINT(1) NOT NULL, INDEX IDX_92881695AA5D4036 (story_id), INDEX IDX_92881695A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE StoryEstimateByUser ADD CONSTRAINT FK_92881695AA5D4036 FOREIGN KEY (story_id) REFERENCES Story (id)");
        $this->addSql("ALTER TABLE StoryEstimateByUser ADD CONSTRAINT FK_92881695A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user_user (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("DROP TABLE StoryEstimateByUser");
    }
}
