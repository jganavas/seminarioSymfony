<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration for implementing Class Table Inheritance (JOINED)
 * Converts existing User entities to Writer or Administrator based on roles
 */
final class Version20260215115028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Implements Class Table Inheritance (JOINED) for User entity, creating Writer and Administrator child entities';
    }

    public function up(Schema $schema): void
    {
        // Step 1: Create child tables
        $this->addSql('CREATE TABLE administrator (permission_level INT NOT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE writer (specialty VARCHAR(100) DEFAULT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        
        // Step 2: Add foreign key constraints
        $this->addSql('ALTER TABLE administrator ADD CONSTRAINT FK_58DF0651BF396750 FOREIGN KEY (id) REFERENCES "user" (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE writer ADD CONSTRAINT FK_97A0D882BF396750 FOREIGN KEY (id) REFERENCES "user" (id) ON DELETE CASCADE');
        
        // Step 3: Add discriminator column as NULLABLE first
        $this->addSql('ALTER TABLE "user" ADD discr VARCHAR(255) DEFAULT NULL');
        
        // Step 4: Migrate existing users
        // Convert admins to Administrator entities (using JSON function for PostgreSQL)
        $this->addSql("
            INSERT INTO administrator (id, permission_level)
            SELECT id, 5 FROM \"user\" WHERE roles::text LIKE '%ROLE_ADMIN%'
        ");
        $this->addSql("
            UPDATE \"user\" SET discr = 'administrator' WHERE roles::text LIKE '%ROLE_ADMIN%'
        ");
        
        // Convert regular users to Writer entities
        $this->addSql("
            INSERT INTO writer (id, specialty)
            SELECT id, 'General' FROM \"user\" WHERE discr IS NULL
        ");
        $this->addSql("
            UPDATE \"user\" SET discr = 'writer' WHERE discr IS NULL
        ");
        
        // Step 5: Make discriminator column NOT NULL now that all users have been migrated
        $this->addSql('ALTER TABLE "user" ALTER COLUMN discr SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Rollback in reverse order
        $this->addSql('ALTER TABLE administrator DROP CONSTRAINT FK_58DF0651BF396750');
        $this->addSql('ALTER TABLE writer DROP CONSTRAINT FK_97A0D882BF396750');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE writer');
        $this->addSql('ALTER TABLE "user" DROP discr');
    }
}
