<?php
declare(strict_types=1);

/**
 * CONFIG AUTH APP STUDIMOVE / SUPERTRIP
 *
 * A MODIFIER AVANT MISE EN LIGNE :
 * - SUPABASE_URL
 * - SUPABASE_SERVICE_ROLE_KEY
 * - APP_BASE_URL
 * - MAIL_FROM
 *
 * IMPORTANT :
 * Ce fichier contient la clé service role Supabase.
 * Il doit rester côté serveur uniquement.
 * Ne jamais l'exposer en JS public.
 */

define('SUPABASE_URL', 'https://knmizebzispcrnsudinf.supabase.co');
define('SUPABASE_SERVICE_ROLE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImtubWl6ZWJ6aXNwY3Juc3VkaW5mIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2ODIzNDE5NSwiZXhwIjoyMDgzODEwMTk1fQ.gh26LX8YjwNbDJ0pu9KlfeXFL-5ny7t9qv4Fdjmjrmg');

define('APP_BASE_URL', 'https://www.studimove.fr/studimove_app_test');

define('AUTH_COOKIE_NAME', 'supertrip_session');
define('AUTH_SESSION_DAYS', 30);
define('PASSWORD_RESET_MINUTES', 45);

define('MAIL_FROM', 'no-reply@studimove.fr');
define('MAIL_FROM_NAME', 'StudiMove');

// ─── SMTP IONOS ────────────────────────────────────────────────────────────
define('SMTP_HOST',       'smtp.ionos.fr');
define('SMTP_PORT',       587);
define('SMTP_ENCRYPTION', 'tls');
define('SMTP_USER',       'no-reply@studimove.fr');
define('SMTP_PASSWORD',   'VOTRE_MOT_DE_PASSE_IONOS'); // ← à remplir
// ───────────────────────────────────────────────────────────────────────────

define('DEFAULT_MARQUE', 'studimove');
define('DEFAULT_SOURCE', 'app');

/**
 * En prod, garde true si tu es bien en HTTPS.
 */
define('COOKIE_SECURE', true);

// ─── ADMIN ─────────────────────────────────────────────────────────────────
define('ADMIN_SECRET_TOKEN', 'CHANGE_MOI_PAR_UN_TOKEN_SECRET');
