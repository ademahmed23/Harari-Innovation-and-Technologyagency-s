<?php

use App\Events\RTOrderPlacedNotificationEvent;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\PortfolioController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\CustomPageController;
use App\Http\Controllers\Frontend\LanguageController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\WishlistController;
use app\Http\Controllers\Admin\chapaController;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductSize;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\Frontend\TranslationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/** Admin Auth Routes */
Route::group(['middleware' => ['maintainance']], function () {

// Route::group(['middleware' => 'guest'], function () {
//     Route::get('admin/login', [AdminAuthController::class, 'index'])->name('admin.login');
//     Route::get('admin/forget-password', [AdminAuthController::class, 'forgetPassword'])->name('admin.forget-password');
// });

Route::group(['middleware' => 'auth'], function(){
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::post('address', [DashboardController::class, 'createAddress'])->name('address.store');
    Route::put('address/{id}/edit', [DashboardController::class, 'updateAddress'])->name('address.update');
    Route::delete('address/{id}', [DashboardController::class, 'destroyAddress'])->name('address.destroy');

    /** Chat Routes */
    Route::post('chat/send-message', [ChatController::class, 'sendMessage'])->name('chat.send-message');
    Route::get('chat/get-conversation/{senderId}',[ChatController::class, 'getConversation'])->name('chat.get-conversation');
});

require __DIR__.'/auth.php';

/** Show Home page */
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('language', LanguageController::class)->name('language');
Route::get('/faq', [FrontendController::class, 'faq'])->name('faq');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/service/{slug}', [ServiceController::class, 'detail'])->name('service_detail');
/** Chef page */
Route::get('/chef', [FrontendController::class, 'chef'])->name('chef');
/** Testimonial page */
Route::get('/testimonials', [FrontendController::class, 'testimonial'])->name('testimonial');
Route::post('/detect-language', [TranslationController::class, 'detectLanguage']);

/** Blogs Routes */
Route::get('/blogs', [FrontendController::class, 'blog'])->name('blogs');
Route::get('/blogs/{slug}', [FrontendController::class, 'blogDetails'])->name('blogs.details');
Route::post('/blogs/comment/{blog_id}', [FrontendController::class, 'blogCommentStore'])->name('blogs.comment.store');

/** About Routes */
Route::get('/about', [FrontendController::class, 'about'])->name('about');
/** Privacy Policy Routes */
Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacy-policy.index');
/** Trams and Conditions Routes */
Route::get('/trams-and-conditions', [FrontendController::class, 'tramsAndConditions'])->name('trams-and-conditions');
/** Contact Routes */
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact.index');
Route::post('/contact', [FrontendController::class, 'sendContactMessage'])->name('contact.send-message');

/** Reservation Routes */
Route::post('/reservation', [FrontendController::class, 'reservation'])->name('reservation.store');

/** Newsletter Routes */
Route::post('/subscribe-newsletter', [FrontendController::class, 'subscribeNewsletter'])->name('subscribe-newsletter');

/** Custom Page Routes */
Route::get('/page/{slug}', CustomPageController::class);

/** Product page Route*/
Route::get('/products', [FrontendController::class, 'products'])->name('product.index');

/** Show Product details page */
Route::get('/product/{slug}', [FrontendController::class, 'showProduct'])->name('product.show');

/** Product Modal Route */
Route::get('/load-product-modal/{productId}', [FrontendController::class, 'loadProductModal'])->name('load-product-modal');

Route::post('product-review', [FrontendController::class, 'productReviewStore'])->name('product-review.store');

/** Add to cart Route */
Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::get('get-cart-products', [CartController::class, 'getCartProduct'])->name('get-cart-products');
Route::get('cart-product-remove/{rowId}', [CartController::class, 'cartProductRemove'])->name('cart-product-remove');

/** Wishlist Route */
Route::get('wishlist/{productId}', [WishlistController::class, 'store'])->name('wishlist.store');


/** Cart Page Routes */
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart-update-qty', [CartController::class, 'cartQtyUpdate'])->name('cart.quantity-update');
Route::get('/cart-destroy', [CartController::class, 'cartDestroy'])->name('cart.destroy');

/** Coupon Routes */
Route::post('/apply-coupon', [FrontendController::class, 'applyCoupon'])->name('apply-coupon');
Route::get('/destroy-coupon', [FrontendController::class, 'destroyCoupon'])->name('destroy-coupon');

Route::group(['middleware' => 'auth'], function(){
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('checkout/{id}/delivery-cal', [CheckoutController::class, 'CalculateDeliveryCharge'])->name('checkout.delivery-cal');
    Route::post('checkout', [CheckoutController::class, 'checkoutRedirect'])->name('checkout.redirect');

    /** Payment Routes */
    Route::get('payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('make-payment', [PaymentController::class, 'makePayment'])->name('make-payment');


    Route::get('payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('payment-cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');

    /** PayPal Routes */
    Route::get('paypal/payment', [PaymentController::class, 'payWithPaypal'])->name('paypal.payment');
    Route::get('paypal/success', [PaymentController::class, 'paypalSuccess'])->name('paypal.success');
    Route::get('paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('paypal.cancel');

Route::post('pay', 'App\Http\Controllers\ChapaController@initialize')->name('pay');

// The callback url after a payment
Route::get('callback/{reference}', 'App\Http\Controllers\ChapaController@callback')->name('callback');
    /** Stripe Routes */
    Route::get('stripe/payment', [PaymentController::class, 'payWithStripe'])->name('stripe.payment');
    Route::get('stripe/success', [PaymentController::class, 'stripeSuccess'])->name('stripe.success');
    Route::get('stripe/cancel', [PaymentController::class, 'stripeCancel'])->name('stripe.cancel');

    /** Stripe Routes */
    Route::get('razorpay-redirect', [PaymentController::class, 'razorpayRedirect'])->name('razorpay-redirect');
    Route::post('razorpay/payment', [PaymentController::class, 'payWithRazorpay'])->name('razorpay.payment');
    Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/event/{slug}', [EventController::class, 'detail'])->name('event');
Route::post('/event/send-message', [EventController::class, 'send_message'])->name('event_send_message');

Route::get('/customer/event/tickets', [CustomerHomeController::class, 'tickets'])->name('customer_event_tickets');
Route::get('/customer/event/ticket/invoice/{id}', [CustomerHomeController::class, 'ticket_invoice'])->name('customer_event_ticket_invoice');
Route::get('/vendor', [FrontendController::class, 'vendorPage'])->name('vendor');
Route::get('/adminstration', [FrontendController::class, 'vendorProductsPage'])->name('adminstration');
Route::get('/portfolio/details/{id}',[FrontendController::class, 'PortfolioDetails'])->name('portfolio.details');

Route::get('/portfolio',[FrontendController::class,'HomePortfolio'])->name('home.portfolio');
Route::post('/event/ticket/payment', [EventController::class, 'payment'])->name('event_ticket_payment');
Route::get('/event/ticket/paypal-success', [EventController::class, 'paypal_success'])->name('event_ticket_paypal_success');
Route::get('/event/ticket/paypal-cancel', [EventController::class, 'paypal_cancel'])->name('event_ticket_cancel');
Route::get('/portfolios', [PortfolioController::class, 'index'])->name('portfolios');
Route::get('/portfolio/{slug}', [PortfolioController::class, 'detail'])->name('portfolio_detail');
});

});