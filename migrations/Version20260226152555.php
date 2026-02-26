<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226152555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables PRO_PROCESO, TIP_TIPO_DOC, and DOC_DOCUMENTO with relationships';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE DOC_DOCUMENTO (DOC_ID INT AUTO_INCREMENT NOT NULL, DOC_NOMBRE VARCHAR(60) NOT NULL, DOC_CODIGO VARCHAR(50) NOT NULL, DOC_CONTENIDO VARCHAR(4000) NOT NULL, DOC_ID_TIPO INT NOT NULL, DOC_ID_PROCESO INT NOT NULL, INDEX DOC_PROCESO_idx (DOC_ID_PROCESO), INDEX DOC_TIPO_idx (DOC_ID_TIPO), PRIMARY KEY (DOC_ID)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE PRO_PROCESO (PRO_ID INT AUTO_INCREMENT NOT NULL, PRO_NOMBRE VARCHAR(60) NOT NULL, PRO_PREFIJO VARCHAR(20) NOT NULL, PRIMARY KEY (PRO_ID)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE TIP_TIPO_DOC (TIP_ID INT AUTO_INCREMENT NOT NULL, TIP_NOMBRE VARCHAR(60) NOT NULL, TIP_PREFIJO VARCHAR(20) NOT NULL, PRIMARY KEY (TIP_ID)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE DOC_DOCUMENTO ADD CONSTRAINT FK_23781393915B5621 FOREIGN KEY (DOC_ID_TIPO) REFERENCES TIP_TIPO_DOC (TIP_ID)');
        $this->addSql('ALTER TABLE DOC_DOCUMENTO ADD CONSTRAINT FK_23781393E20AF3E7 FOREIGN KEY (DOC_ID_PROCESO) REFERENCES PRO_PROCESO (PRO_ID)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE DOC_DOCUMENTO DROP FOREIGN KEY FK_23781393915B5621');
        $this->addSql('ALTER TABLE DOC_DOCUMENTO DROP FOREIGN KEY FK_23781393E20AF3E7');
        $this->addSql('DROP TABLE DOC_DOCUMENTO');
        $this->addSql('DROP TABLE PRO_PROCESO');
        $this->addSql('DROP TABLE TIP_TIPO_DOC');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
