<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240712121043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement ADD utilisateur_id INT NOT NULL, ADD evenement_id INT DEFAULT NULL, ADD discussion_id INT DEFAULT NULL, ADD notification TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB1ADED311 FOREIGN KEY (discussion_id) REFERENCES discussion (id)');
        $this->addSql('CREATE INDEX IDX_351268BBFB88E14F ON abonnement (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_351268BBFD02F13 ON abonnement (evenement_id)');
        $this->addSql('CREATE INDEX IDX_351268BB1ADED311 ON abonnement (discussion_id)');
        $this->addSql('ALTER TABLE commentaire ADD post_id INT DEFAULT NULL, ADD evenement_id INT DEFAULT NULL, ADD auteur_id INT NOT NULL, ADD contenu LONGTEXT NOT NULL, ADD date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_67F068BC4B89032C ON commentaire (post_id)');
        $this->addSql('CREATE INDEX IDX_67F068BCFD02F13 ON commentaire (evenement_id)');
        $this->addSql('CREATE INDEX IDX_67F068BC60BB6FE6 ON commentaire (auteur_id)');
        $this->addSql('ALTER TABLE discussion ADD auteur_id INT NOT NULL, ADD nom VARCHAR(255) NOT NULL, ADD notification TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90F60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C0B9F90F60BB6FE6 ON discussion (auteur_id)');
        $this->addSql('ALTER TABLE evenement ADD auteur_id INT NOT NULL, ADD titre VARCHAR(255) NOT NULL, ADD contenu LONGTEXT NOT NULL, ADD date_creation DATETIME NOT NULL, ADD date_debut DATETIME NOT NULL, ADD date_fin DATETIME NOT NULL, ADD lieu VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B26681E60BB6FE6 ON evenement (auteur_id)');
        $this->addSql('ALTER TABLE post ADD auteur_id INT NOT NULL, ADD titre VARCHAR(255) NOT NULL, ADD contenu LONGTEXT NOT NULL, ADD date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D60BB6FE6 ON post (auteur_id)');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(50) NOT NULL, ADD prenom VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBFB88E14F');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBFD02F13');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB1ADED311');
        $this->addSql('DROP INDEX IDX_351268BBFB88E14F ON abonnement');
        $this->addSql('DROP INDEX IDX_351268BBFD02F13 ON abonnement');
        $this->addSql('DROP INDEX IDX_351268BB1ADED311 ON abonnement');
        $this->addSql('ALTER TABLE abonnement DROP utilisateur_id, DROP evenement_id, DROP discussion_id, DROP notification');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC4B89032C');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCFD02F13');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC60BB6FE6');
        $this->addSql('DROP INDEX IDX_67F068BC4B89032C ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BCFD02F13 ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BC60BB6FE6 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP post_id, DROP evenement_id, DROP auteur_id, DROP contenu, DROP date_creation');
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90F60BB6FE6');
        $this->addSql('DROP INDEX IDX_C0B9F90F60BB6FE6 ON discussion');
        $this->addSql('ALTER TABLE discussion DROP auteur_id, DROP nom, DROP notification');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E60BB6FE6');
        $this->addSql('DROP INDEX IDX_B26681E60BB6FE6 ON evenement');
        $this->addSql('ALTER TABLE evenement DROP auteur_id, DROP titre, DROP contenu, DROP date_creation, DROP date_debut, DROP date_fin, DROP lieu');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D60BB6FE6');
        $this->addSql('DROP INDEX IDX_5A8A6C8D60BB6FE6 ON post');
        $this->addSql('ALTER TABLE post DROP auteur_id, DROP titre, DROP contenu, DROP date_creation');
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }
}
