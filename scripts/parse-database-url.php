<?php
/**
 * Parse postgres:// or postgresql:// URLs for docker-entrypoint.sh.
 * PHP parse_url() mishandles passwords containing ":" ; this uses RFC-style userinfo splitting.
 * If DB_PASSWORD is set in the environment, it overrides the password from the URL (plain text on Render).
 */
declare(strict_types=1);

$url = getenv('DATABASE_URL');
if (!$url) {
    fwrite(STDERR, "DATABASE_URL empty\n");
    exit(1);
}

/**
 * @return array{0:string,1:string,2:string,3:string,4:string} user, pass, host, port, database
 */
function parse_postgres_url(string $url): array
{
    if (!preg_match('#^postgres(?:ql)?://#i', $url)) {
        throw new InvalidArgumentException('DATABASE_URL must start with postgres:// or postgresql://');
    }

    $rest = preg_replace('#^postgres(?:ql)?://#i', '', $url);
    $qpos = strpos($rest, '?');
    if ($qpos !== false) {
        $rest = substr($rest, 0, $qpos);
    }

    $path = 'postgres';
    $slash = strpos($rest, '/');
    if ($slash !== false) {
        $pathPart = substr($rest, $slash + 1);
        $pathPart = explode('?', $pathPart, 2)[0];
        $path = $pathPart !== '' ? $pathPart : 'postgres';
        $rest = substr($rest, 0, $slash);
    }

    $atPos = strrpos($rest, '@');
    if ($atPos === false) {
        $user = '';
        $pass = '';
        $hostport = $rest;
    } else {
        $userinfo = substr($rest, 0, $atPos);
        $hostport = substr($rest, $atPos + 1);
        $colonPos = strpos($userinfo, ':');
        if ($colonPos === false) {
            $user = rawurldecode($userinfo);
            $pass = '';
        } else {
            $user = rawurldecode(substr($userinfo, 0, $colonPos));
            $pass = rawurldecode(substr($userinfo, $colonPos + 1));
        }
    }

    if (preg_match('#^(.+):(\d+)$#', $hostport, $m)) {
        $host = $m[1];
        $port = $m[2];
    } else {
        $host = $hostport;
        $port = '5432';
    }

    if (strpos($host, 'dpg-') === 0 && strpos($host, '.singapore-postgres.render.com') === false) {
        $host .= '.singapore-postgres.render.com';
    }

    return [$user, $pass, $host, $port, $path];
}

try {
    [$user, $pass, $host, $port, $db] = parse_postgres_url($url);
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage()."\n");
    exit(1);
}

$override = getenv('DB_PASSWORD');
if ($override !== false && $override !== '') {
    fwrite(STDERR, "ℹ️  Using DB_PASSWORD from environment (overrides password embedded in DATABASE_URL).\n");
    $pass = $override;
}

$prefix = $argv[1] ?? 'DB_';
$map = [
    $prefix.'USERNAME' => $user,
    $prefix.'PASSWORD' => $pass,
    $prefix.'HOST' => $host,
    $prefix.'PORT' => $port,
    $prefix.'DATABASE' => $db,
];

foreach ($map as $name => $value) {
    echo 'export '.$name.'='.escapeshellarg($value)."\n";
}
