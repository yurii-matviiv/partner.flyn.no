# Partner database changes

## P1 — Isolated Partner authentication

**Purpose:** Partner credentials must never be read from or written to the ERM
`users` table. This change creates a dedicated user table and an independent
password-reset-token table inside the same MySQL database.

Run this SQL once locally and once on hosting:

```sql
CREATE TABLE IF NOT EXISTS partner_users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NULL DEFAULT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NULL DEFAULT NULL,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    locale VARCHAR(5) NOT NULL DEFAULT 'nb',
    remember_token VARCHAR(100) NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY partner_users_email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS partner_password_reset_tokens (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## P4 — Remember that profile completion was deferred

```sql
ALTER TABLE partner_profiles
    ADD COLUMN deferred_at TIMESTAMP NULL DEFAULT NULL AFTER contact_job_title;
```

## P2 — Passwordless email access codes

```sql
CREATE TABLE IF NOT EXISTS partner_email_login_codes (
    email VARCHAR(255) NOT NULL,
    code_hash VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    locale VARCHAR(5) NOT NULL DEFAULT 'nb',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## P3 — Partner company and contact profile

Partner keeps no duplicate company or contact data. `customer_companies` and
`clients` remain the existing ERM sources used by invoices; this table only
records which company and contact belong to a Partner account.

```sql
CREATE TABLE IF NOT EXISTS partner_profiles (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    partner_user_id BIGINT UNSIGNED NOT NULL,
    customer_company_id BIGINT UNSIGNED NULL DEFAULT NULL,
    contact_client_id BIGINT UNSIGNED NULL DEFAULT NULL,
    contact_job_title VARCHAR(150) NULL DEFAULT NULL,
    completed_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY partner_profiles_partner_user_unique (partner_user_id),
    KEY partner_profiles_company_index (customer_company_id),
    KEY partner_profiles_contact_index (contact_client_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
