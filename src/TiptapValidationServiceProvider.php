<?php

namespace JacobFitzp\LaravelTiptapValidation;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TiptapValidationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('tiptap-validation')
            ->hasTranslations()
            ->hasConfigFile();
    }
}
