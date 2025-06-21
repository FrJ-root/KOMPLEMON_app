<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductMedia;
use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use App\Models\Coupon;
use App\Models\BlogPost;
use App\Models\Testimonial;
use App\Policies\ProductPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\MediaPolicy;
use App\Policies\OrderPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\UserPolicy;
use App\Policies\CouponPolicy;
use App\Policies\BlogPostPolicy;
use App\Policies\TestimonialPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Category::class => CategoryPolicy::class,
        ProductMedia::class => MediaPolicy::class,
        Order::class => OrderPolicy::class,
        Customer::class => CustomerPolicy::class,
        User::class => UserPolicy::class,
        Coupon::class => CouponPolicy::class,
        BlogPost::class => BlogPostPolicy::class,
        Testimonial::class => TestimonialPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for dashboard pages and widgets
        Gate::define('view-dashboard', function (User $user) {
            return true; // All authenticated users can view the dashboard
        });

        Gate::define('view-settings', function (User $user) {
            return $user->isAdmin(); // Only administrators can view settings
        });

        Gate::define('view-statistics', function (User $user) {
            return $user->isAdmin() || $user->isProductManager(); // Admins and product managers can view statistics
        });
    }
}
