<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $dept_namespace = 'App\Http\Controllers\DepartmentUser';
    protected $finace_namespace = 'App\Http\Controllers\Finance';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapFinanceRoutes();

        $this->mapDepartmentUserRoutes();

        $this->mapAdminRoutes();
        
        $this->mapStudentRoutes();

        //
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::group([
            'middleware' => ['web', 'admin', 'auth:admin'],
            'prefix' => 'admin',
            'as' => 'admin.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/admin.php');
        });
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapStudentRoutes()
    {
        Route::group([
            'middleware' => ['web', 'auth:student'],
            'prefix' => 'student',
            'as' => 'student.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/student.php');
        });
    }
    /**
     * Define the "department user" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapDepartmentUserRoutes()
    {
        Route::group([
            'middleware' => ['web', 'auth:department_user'],
            'prefix' => 'department',
            'as' => 'department.',
            'namespace' => $this->dept_namespace,
        ], function ($router) {
            require base_path('routes/department_user.php');
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
    public function mapFinanceRoutes()
    {
        Route::group([
            'middleware' => ["web", "finance", "auth:finance"],
            'prefix' => 'finance',
            'as' => 'finance.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/finance.php');
        });
    }
}
