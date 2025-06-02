<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Str;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Registra automáticamente los bindings de los repositorios.
     *
     * @return void
     */
    protected function registerBindings(): void
    {
        $interfaceNamespace = 'App\\Interfaces\\Repositories';
        $repositoryNamespace = 'App\\Repositories\\Eloquent';
        $repositoryPath = app_path('Repositories/Eloquent');
        $interfacePath = app_path('Interfaces/Repositories');

        if (!is_dir($repositoryPath) || !is_dir($interfacePath)) {
            return;
        }

        $finder = new Finder();
        $finder->files()->name('*Interface.php')->in($interfacePath);

        foreach ($finder as $file) {
            $interfaceNameWithNamespace = $interfaceNamespace . '\\' . $file->getBasename('.php');
            $interfaceNameWithoutInterface = Str::replaceLast('Interface', '', $file->getBasename('.php'));
            $repositoryClassName = $repositoryNamespace . '\\' . $interfaceNameWithoutInterface;

            if (class_exists($repositoryClassName)) {
                $this->app->singleton($interfaceNameWithNamespace, $repositoryClassName);
            }
        }
    }
}
