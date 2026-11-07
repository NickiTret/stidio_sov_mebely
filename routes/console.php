<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create {email?}', function (?string $email = null) {
    $email = $email ?: $this->ask('Email администратора');

    $validator = \Illuminate\Support\Facades\Validator::make(['email' => $email], ['email' => 'required|email']);
    if ($validator->fails()) {
        $this->error('Некорректный email.');
        return 1;
    }

    $user = \App\Models\User::where('email', $email)->first();

    if ($user && !$this->confirm("Пользователь {$email} уже есть. Сделать его администратором и сменить пароль?")) {
        return 0;
    }

    $password = $this->secret('Пароль (минимум 10 символов)');
    if (strlen((string) $password) < 10 || $password !== $this->secret('Повторите пароль')) {
        $this->error('Пароль короче 10 символов или не совпадает.');
        return 1;
    }

    $user ??= new \App\Models\User(['email' => $email, 'name' => $this->ask('Имя', 'Администратор')]);
    $user->password = \Illuminate\Support\Facades\Hash::make($password);
    $user->is_admin = 1;
    $user->save();

    $this->info("Администратор {$email} сохранён.");
    return 0;
})->purpose('Создать администратора или выдать права существующему пользователю');

Artisan::command('images:prune {--force : Действительно удалить файлы (без флага — только показать)}', function () {
    $disk = \Illuminate\Support\Facades\Storage::disk('public');

    $used = collect()
        ->merge(\App\Models\Post::pluck('image_src'))
        ->merge(\App\Models\Slider::pluck('image_src'))
        ->merge(\App\Models\Seting::pluck('favicon'))
        ->filter()
        ->flip();

    $orphans = collect($disk->allFiles('images'))
        ->reject(fn (string $path) => $used->has($path) || str_starts_with(basename($path), '.'))
        ->values();

    if ($orphans->isEmpty()) {
        $this->info('Неиспользуемых файлов нет.');
        return 0;
    }

    $bytes = $orphans->sum(fn (string $path) => $disk->size($path));
    $orphans->each(fn (string $path) => $this->line($path));
    $this->line(sprintf('Итого: %d файлов, %.1f МБ.', $orphans->count(), $bytes / 1048576));

    if (!$this->option('force')) {
        $this->comment('Это пробный запуск. Чтобы удалить, добавьте --force.');
        return 0;
    }

    $disk->delete($orphans->all());

    foreach (array_reverse($disk->allDirectories('images')) as $directory) {
        if (empty($disk->files($directory)) && empty($disk->directories($directory))) {
            $disk->deleteDirectory($directory);
        }
    }

    $this->info('Удалено.');
    return 0;
})->purpose('Найти и удалить картинки в storage/app/public/images, на которые не ссылается ни одна запись');
