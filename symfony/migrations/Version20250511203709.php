<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250511203709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // USER
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (
                id UUID NOT NULL, 
                name VARCHAR(100) NOT NULL, 
                usertag VARCHAR(20) NOT NULL, 
                email VARCHAR(255) NOT NULL, 
                password VARCHAR(255) NOT NULL, 
                email_verified BOOLEAN NOT NULL, 
                email_verified_at TIMESTAMP(0) WITH TIME ZONE NULL, 
                img VARCHAR(255) NOT NULL, 
                created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, 
                email_code VARCHAR(10) NULL, 
                PRIMARY KEY(id)
            )
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".email_verified_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_user_usertag ON "user" (usertag)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_user_email ON "user" (email)
        SQL);

        // FRIEND
        $this->addSql(<<<'SQL'
            CREATE TABLE friend (
                id UUID NOT NULL,
                sender_id UUID NOT NULL, 
                receiver_id UUID NOT NULL, 
                pending BOOLEAN NOT NULL, 
                accepted_at TIMESTAMP(0) WITH TIME ZONE NULL, 
                PRIMARY KEY(id),
                CONSTRAINT fk_friend_sender FOREIGN KEY (sender_id) REFERENCES "user"(id) ON DELETE CASCADE,
                CONSTRAINT fk_friend_receiver FOREIGN KEY (receiver_id) REFERENCES "user"(id) ON DELETE CASCADE
            )
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN friend.accepted_at IS '(DC2Type:datetime_immutable)'
        SQL);

        // SESSION
        $this->addSql(<<<'SQL'
            CREATE TABLE session (
                id UUID NOT NULL, 
                user_id UUID NOT NULL, 
                created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, 
                expires_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, 
                last_activity TIMESTAMP(0) WITH TIME ZONE NOT NULL, 
                user_agent VARCHAR(255) NULL, 
                revoked BOOLEAN NOT NULL, 
                PRIMARY KEY(id),
                CONSTRAINT fk_session_user FOREIGN KEY (user_id) REFERENCES "user"(id) ON DELETE CASCADE
            )
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN session.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN session.expires_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN session.last_activity IS '(DC2Type:datetime_immutable)'
        SQL);

        // CATEGORY
        $this->addSql(<<<'SQL'
            CREATE TABLE category (
                id UUID PRIMARY KEY NOT NULL,
                user_id UUID NOT NULL,
                icon SMALLINT NOT NULL,
                name VARCHAR(100) NOT NULL,
                CONSTRAINT fk_category_user FOREIGN KEY (user_id) REFERENCES "user"(id) ON DELETE CASCADE
            )
        SQL);

        // ACTIVITY
        $this->addSql(<<<'SQL'
            CREATE TABLE activity (
                id UUID NOT NULL, 
                category_id UUID NOT NULL, 
                user_id UUID NOT NULL, 
                name VARCHAR(100) NOT NULL, 
                started_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, 
                finished_at TIMESTAMP(0) WITH TIME ZONE NULL, 
                PRIMARY KEY(id),
                CONSTRAINT fk_activity_category FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE,
                CONSTRAINT fk_activity_user FOREIGN KEY (user_id) REFERENCES "user"(id) ON DELETE CASCADE
            )
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN activity.started_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN activity.finished_at IS '(DC2Type:datetime_immutable)'
        SQL);

        // NOTIFICATION
        $this->addSql(<<<'SQL'
            CREATE TABLE notification (
                id UUID PRIMARY KEY,
                user_id UUID NOT NULL, 
                title VARCHAR(255) NOT NULL, 
                content TEXT NOT NULL, 
                type SMALLINT NOT NULL,
                created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL,
                is_read BOOLEAN NOT NULL,
                CONSTRAINT fk_notification_user FOREIGN KEY (user_id) REFERENCES "user"(id) ON DELETE CASCADE
            )
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN notification.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE friend
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE session
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE activity
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE notification
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
    }
}
