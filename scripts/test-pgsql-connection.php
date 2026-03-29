<?php
/**
 * Used by docker-entrypoint.sh. Reads TEST_DB_* env vars (trimmed).
 * Tries primary connection; on Supabase pooler password failure, tries direct db.<ref>.supabase.co + user postgres.
 * If direct works, patches /var/www/html/.env so Laravel matches the working connection.
 */
declare(strict_types=1);

$h = trim((string) getenv('TEST_DB_HOST'));
$port = trim((string) getenv('TEST_DB_PORT'));
$db = trim((string) getenv('TEST_DB_DATABASE'));
$u = trim((string) getenv('TEST_DB_USERNAME'));
$w = trim((string) getenv('TEST_DB_PASSWORD'));

$envPath = '/var/www/html/.env';

function connect(string $h, string $port, string $db, string $u, string $w): PDO
{
    $dsn = "pgsql:host={$h};port={$port};dbname={$db};sslmode=require";

    return new PDO($dsn, $u, $w, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 20,
    ]);
}

function patchEnv(string $envPath, string $newHost, string $newUser): void
{
    if (! is_readable($envPath) || ! is_writable($envPath)) {
        fwrite(STDERR, "Cannot patch .env at {$envPath}\n");

        return;
    }
    $env = file_get_contents($envPath);
    if ($env === false) {
        return;
    }
    $env = preg_replace('/^DB_HOST=.*$/m', 'DB_HOST='.$newHost, $env, 1);
    $env = preg_replace('/^DB_USERNAME=.*$/m', 'DB_USERNAME='.$newUser, $env, 1);
    file_put_contents($envPath, $env);
    fwrite(STDERR, "✅ Updated .env to DB_HOST={$newHost} and DB_USERNAME={$newUser} (direct connection works; pooler did not accept credentials).\n");
}

try {
    connect($h, $port, $db, $u, $w);
    echo 'Render PostgreSQL connected successfully';
    exit(0);
} catch (Throwable $e) {
    $msg = $e->getMessage();
    $authFailed = str_contains($msg, 'password authentication failed');
    $pooler = str_contains($h, 'pooler.supabase.com');
    if ($authFailed && $pooler && preg_match('/^postgres\.(.+)$/i', $u, $m)) {
        $ref = $m[1];
        $directHost = 'db.'.$ref.'.supabase.co';
        fwrite(STDERR, "Pooler auth failed; trying Supabase direct host {$directHost} as user postgres...\n");
        try {
            connect($directHost, $port, $db, 'postgres', $w);
            patchEnv($envPath, $directHost, 'postgres');
            echo 'Render PostgreSQL connected successfully (direct host)';
            exit(0);
        } catch (Throwable $e2) {
            fwrite(STDERR, 'Direct attempt failed: '.$e2->getMessage()."\n");
        }
    }
    echo 'Render PostgreSQL connection failed: '.$msg;
    exit(1);
}
