<?php

namespace App\Providers;

use App\Entities\Category;
use App\Entities\Post;
use App\Entities\User;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Post\PostRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

//use App\Repositories\User\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();
        $this->bindRepositories();
    }

    private function bindRepositories () {
        $this->app->bind(UserRepository::class, function($app) {
            // This is what Doctrine's EntityRepository needs in its constructor.
            return new UserRepository(
                $app['em'],
                $app['em']->getClassMetaData(User::class)
            );
        });
        $this->app->bind(PostRepository::class, function($app) {
            return new PostRepository(
                $app['em'],
                $app['em']->getClassMetaData(Post::class)
            );
        });
        $this->app->bind(CategoryRepository::class, function($app) {
            return new CategoryRepository(
                $app['em'],
                $app['em']->getClassMetaData(Category::class)
            );
        });
    }
}
