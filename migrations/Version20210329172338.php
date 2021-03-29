<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20210329172338
 * @package DoctrineMigrations
 * @author bernard-ng <ngandubernard@gmail.com>
 */
final class Version20210329172338 extends AbstractMigration
{

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getDescription(): string
    {
        return 'subscription table';
    }

    /**
     * @param Schema $schema
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, chat_id INT NOT NULL, promotion VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, UNIQUE INDEX UNIQ_A3C664D31A9A7125 (chat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE subscription');
    }
}
