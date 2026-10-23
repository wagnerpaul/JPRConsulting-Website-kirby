<?php

/**
 * Instantiate every page form, file blueprint, block fieldset, and Panel
 * section, so blueprint-level breakage surfaces here instead of as Panel
 * 500s. Sections are a separate factory that form instantiation misses.
 *
 * Run inside the app container:
 *
 *   docker exec <container> php /app/check-blueprints.php
 */

declare(strict_types=1);

use Kirby\Cms\App;
use Kirby\Form\Form;

require '/app/vendor/autoload.php';

$base = '/app';
$kirby = new App([
    'roots' => [
        'index'    => $base . '/public',
        'base'     => $base,
        'content'  => $base . '/content',
        'site'     => $base . '/site',
        'storage'  => $base . '/storage',
        'accounts' => $base . '/storage/accounts',
        'cache'    => $base . '/storage/cache',
        'sessions' => $base . '/storage/sessions',
    ],
]);
$kirby->impersonate('kirby');

$errors = 0;

function check(string $label, callable $fn): void
{
    global $errors;
    try {
        $fn();
        echo "OK   {$label}\n";
    } catch (Throwable $e) {
        $errors++;
        echo "ERR  {$label}: " . get_class($e) . ': ' . $e->getMessage() . "\n";
    }
}

$models = array_merge([$kirby->site()], $kirby->site()->index(true)->values());

foreach ($models as $model) {
    $id = $model instanceof Kirby\Cms\Site ? '(site)' : $model->id();

    check("form     {$id}", function () use ($model) {
        $form = Form::for($model);
        // blocks/layout fields parse their fieldsets lazily; force them
        foreach ($form->fields() as $field) {
            if (method_exists($field, 'fieldsets')) {
                $field->fieldsets();
            }
        }
    });

    check("sections {$id}", function () use ($model) {
        foreach ($model->blueprint()->tabs() as $tab) {
            // tab columns hold the section definitions
        }
        $model->blueprint()->sections();
    });

    foreach ($model->files() as $file) {
        check("file     {$file->id()}", function () use ($file) {
            $file->blueprint();
            Form::for($file);
        });
    }
}

echo $errors === 0 ? "\nall blueprints clean\n" : "\n{$errors} blueprint errors\n";
exit($errors === 0 ? 0 : 1);
