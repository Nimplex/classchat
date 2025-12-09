<?php

/** @var PDO $db */
require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('cli only');

pcntl_async_signals(true);
error_reporting(E_ERROR | E_PARSE);

$history_file = __DIR__ . '/.repl_history';

if (file_exists($history_file)) {
    readline_read_history($history_file);
}

$command_table = [
    'send_email' => [
        'description' => 'Send email from configured SMTP',
        'exec' => function ($args) {
            $email = $args[0];
            $template = trim($args[1]);
            $raw_params = array_slice($args, 2);

            if (!isset($email) || !isset($template)) {
                throw new \Exception('Missing parameters');
            }

            $path = getcwd() . "/templates/{$template}.php";
            if (!file_exists($path)) {
                throw new \RuntimeException("Template not found: $template");
            }

            $code = file_get_contents($path);
            $tokens = token_get_all($code);

            $vars = [];
            foreach ($tokens as $token) {
                if (is_array($token) && $token[0] === T_VARIABLE) {
                    $vars[] = ltrim($token[1], '$'); // clean "$foo" → "foo"
                }
            }

            $vars = array_unique($vars);

            $ignore = ['this']; 
            $vars = array_diff($vars, $ignore);

            $params = json_decode(join(' ', $raw_params), true);
            if (!is_array($params)) {
                throw new \Exception('Missing parameter');
            }

            if (!isset($params['subject']) || !isset($params['params'])) {
                throw new \Exception('Correct params format: {"subject": "...", "params": {...}}');
            }

            $subject = $params['subject'];
            $nested_params = $params['params'];

            $missing = array_diff($vars, array_keys($nested_params));

            if (!empty($missing)) {
                $m = implode(', ', $missing);
                throw new \RuntimeException("Missing template variables: $m");
            }

            $mailer = new App\Mailer();
            $mailer->send($email, $subject, $template, $nested_params, true);

            echo "Sent an email to {$email}\n";
        }
    ]
];

pcntl_signal(SIGINT, function() use ($history_file) {
    readline_write_history($history_file);
    echo "SIGINT received, exiting.\n";
    die;
});

while (true) {
    $raw_command = readline('(? for help) > ');

    if ($raw_command === false) {
        break;
    }

    if (trim($raw_command) !== '') {
        readline_add_history($raw_command);
        readline_write_history($history_file);
    }

    $raw_args = explode(' ', $raw_command);
    $cmd = $raw_args[0];
    $args = array_slice($raw_args, 1);

    if ($cmd == '?') {
        foreach ($command_table as $command => $properties) {
            printf("%s -- \e[33m%s\e[0m\n", str_pad($command, 15), $properties['description']);
        }

        continue;
    }

    if (isset($command_table[$cmd])) {
        try {
            $command_table[$cmd]['exec']($args);
        } catch (\Exception $e) {
            print("\e[31m<E {$e->getMessage()}\e[0m\n");
        }
    } else {
        print("\e[31m<! Invalid command\n\e[0m");
    }
}
