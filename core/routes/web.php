<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});




// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});


Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
    Route::post('subscribe', 'subscribe')->name('subscribe');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('blogs', 'blogs')->name('blogs');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');

    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    
    Route::get('add-click-up', 'addClickUp')->name('add.click.up');


    Route::get('tour-plans', 'plans')->name('plans');
    Route::get('tour-plan/{id}/{slug}', 'planDetails')->name('plan.details');
    
    // Hotels Listing
    Route::get('hotels', 'hotels')->name('hotels');
    
    Route::get('day-trip-details/{id}/{slug}', 'seminarDetails')->name('seminar.details');
    Route::get('day-trips', 'seminars')->name('seminars');
    Route::get('search-day-trips', 'seminarSearch')->name('seminars.search');

    // Hotels
    Route::controller('HotelController')->group(function () {
        Route::get('/hotels/search', 'search')->name('hotel.search');
        Route::get('/hotel/{id}/{slug}', 'details')->name('hotel.details');
    });

    // Booking & Checkout
    Route::controller('BookingController')->group(function () {
        Route::post('/hotel/checkout', 'checkout')->name('hotel.checkout');
        Route::post('/hotel/book', 'process')->name('hotel.book');
    });
  

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});
