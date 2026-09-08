<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('admin.dashboard');

    Route::get('/index-ecommerce', function () {
        return view('admin.index-ecommerce');
    })->name('admin.index-ecommerce');
    Route::get('/utilities/tables', function () {
        return view('admin.utilities.tables');
    })->name('admin.utilities.tables');
    Route::get('/utilities/images', function () {
        return view('admin.utilities.images');
    })->name('admin.utilities.images');
    Route::get('/utilities/typography', function () {
        return view('admin.utilities.typography');
    })->name('admin.utilities.typography');
    Route::get('/utilities/sizing', function () {
        return view('admin.utilities.sizing');
    })->name('admin.utilities.sizing');
    Route::get('/utilities/background', function () {
        return view('admin.utilities.background');
    })->name('admin.utilities.background');
    Route::get('/utilities/spacing', function () {
        return view('admin.utilities.spacing');
    })->name('admin.utilities.spacing');
    Route::get('/utilities/borders', function () {
        return view('admin.utilities.borders');
    })->name('admin.utilities.borders');
    Route::get('/utilities/misc', function () {
        return view('admin.utilities.misc');
    })->name('admin.utilities.misc');
    Route::get('/utilities/colors', function () {
        return view('admin.utilities.colors');
    })->name('admin.utilities.colors');
    Route::get('/utilities/flex', function () {
        return view('admin.utilities.flex');
    })->name('admin.utilities.flex');
    Route::get('/apps/kanban/kanban-basic', function () {
        return view('admin.apps.kanban.kanban-basic');
    })->name('admin.apps.kanban.kanban-basic');
    Route::get('/apps/kanban/kanban-custom-board', function () {
        return view('admin.apps.kanban.kanban-custom-board');
    })->name('admin.apps.kanban.kanban-custom-board');
    Route::get('/apps/help-desk/overview', function () {
        return view('admin.apps.help-desk.overview');
    })->name('admin.apps.help-desk.overview');
    Route::get('/apps/help-desk/details', function () {
        return view('admin.apps.help-desk.details');
    })->name('admin.apps.help-desk.details');
    Route::get('/apps/invoice/invoice-preview', function () {
        return view('admin.apps.invoice.invoice-preview');
    })->name('admin.apps.invoice.invoice-preview');
    Route::get('/apps/invoice/invoice-list', function () {
        return view('admin.apps.invoice.invoice-list');
    })->name('admin.apps.invoice.invoice-list');
    Route::get('/apps/mailbox/inbox', function () {
        return view('admin.apps.mailbox.inbox');
    })->name('admin.apps.mailbox.inbox');
    Route::get('/apps/pricing/pricing', function () {
        return view('admin.apps.pricing.pricing');
    })->name('admin.apps.pricing.pricing');
    Route::get('/apps/todo/todo', function () {
        return view('admin.apps.todo.todo');
    })->name('admin.apps.todo.todo');
    Route::get('/apps/chat/chats', function () {
        return view('admin.apps.chat.chats');
    })->name('admin.apps.chat.chats');
    Route::get('/apps/fullcalendar/calendar', function () {
        return view('admin.apps.fullcalendar.calendar');
    })->name('admin.apps.fullcalendar.calendar');
    Route::get('/index-project', function () {
        return view('admin.index-project');
    })->name('admin.index-project');
    Route::get('/forms/date-time', function () {
        return view('admin.forms.date-time');
    })->name('admin.forms.date-time');
    Route::get('/forms/form-layout', function () {
        return view('admin.forms.form-layout');
    })->name('admin.forms.form-layout');
    Route::get('/forms/input-group', function () {
        return view('admin.forms.input-group');
    })->name('admin.forms.input-group');
    Route::get('/forms/form-control', function () {
        return view('admin.forms.form-control');
    })->name('admin.forms.form-control');
    Route::get('/forms/editors/tinymce', function () {
        return view('admin.forms.editors.tinymce');
    })->name('admin.forms.editors.tinymce');
    Route::get('/forms/editors/quill', function () {
        return view('admin.forms.editors.quill');
    })->name('admin.forms.editors.quill');
    Route::get('/forms/form-range', function () {
        return view('admin.forms.form-range');
    })->name('admin.forms.form-range');
    Route::get('/forms/checks-radios', function () {
        return view('admin.forms.checks-radios');
    })->name('admin.forms.checks-radios');
    Route::get('/forms/form-upload', function () {
        return view('admin.forms.form-upload');
    })->name('admin.forms.form-upload');
    Route::get('/forms/form-validation', function () {
        return view('admin.forms.form-validation');
    })->name('admin.forms.form-validation');
    Route::get('/forms/floating-labels', function () {
        return view('admin.forms.floating-labels');
    })->name('admin.forms.floating-labels');
    Route::get('/forms/form-select', function () {
        return view('admin.forms.form-select');
    })->name('admin.forms.form-select');
    Route::get('/profile-edit', function () {
        return view('admin.profile-edit');
    })->name('admin.profile-edit');
    Route::get('/user-manage/user-profile', function () {
        return view('admin.user-manage.user-profile');
    })->name('admin.user-manage.user-profile');
    Route::get('/user-manage/user-list', function () {
        return view('admin.user-manage.user-list');
    })->name('admin.user-manage.user-list');
    Route::get('/user-manage/user-cards', function () {
        return view('admin.user-manage.user-cards');
    })->name('admin.user-manage.user-cards');
    Route::get('/user-manage/user-edit', function () {
        return view('admin.user-manage.user-edit');
    })->name('admin.user-manage.user-edit');
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');
    Route::get('/layout/breakpoints', function () {
        return view('admin.layout.breakpoints');
    })->name('admin.layout.breakpoints');
    Route::get('/layout/containers', function () {
        return view('admin.layout.containers');
    })->name('admin.layout.containers');
    Route::get('/layout/gutters', function () {
        return view('admin.layout.gutters');
    })->name('admin.layout.gutters');
    Route::get('/error/page-404', function () {
        return view('admin.error.page-404');
    })->name('admin.error.page-404');
    Route::get('/error/page-404-classic', function () {
        return view('admin.error.page-404-classic');
    })->name('admin.error.page-404-classic');
    Route::get('/error/page-504-classic', function () {
        return view('admin.error.page-504-classic');
    })->name('admin.error.page-504-classic');
    Route::get('/auths/auth-login-classic', function () {
        return view('admin.auths.auth-login-classic');
    })->name('admin.auths.auth-login-classic');
    Route::get('/auths/auth-reset-fancy', function () {
        return view('admin.auths.auth-reset-fancy');
    })->name('admin.auths.auth-reset-fancy');
    Route::get('/auths/auth-login-fancy', function () {
        return view('admin.auths.auth-login-fancy');
    })->name('admin.auths.auth-login-fancy');
    Route::get('/auths/auth-reset-split', function () {
        return view('admin.auths.auth-reset-split');
    })->name('admin.auths.auth-reset-split');
    Route::get('/auths/auth-register-classic', function () {
        return view('admin.auths.auth-register-classic');
    })->name('admin.auths.auth-register-classic');
    Route::get('/auths/auth-login-split', function () {
        return view('admin.auths.auth-login-split');
    })->name('admin.auths.auth-login-split');
    Route::get('/auths/auth-reset-classic', function () {
        return view('admin.auths.auth-reset-classic');
    })->name('admin.auths.auth-reset-classic');
    Route::get('/auths/auth-login', function () {
        return view('admin.auths.auth-login');
    })->name('admin.auths.auth-login');
    Route::get('/auths/auth-register-fancy', function () {
        return view('admin.auths.auth-register-fancy');
    })->name('admin.auths.auth-register-fancy');
    Route::get('/auths/auth-register-split', function () {
        return view('admin.auths.auth-register-split');
    })->name('admin.auths.auth-register-split');
    Route::get('/auths/auth-register', function () {
        return view('admin.auths.auth-register');
    })->name('admin.auths.auth-register');
    Route::get('/auths/auth-reset', function () {
        return view('admin.auths.auth-reset');
    })->name('admin.auths.auth-reset');
    Route::get('/index-marketing', function () {
        return view('admin.index-marketing');
    })->name('admin.index-marketing');
    Route::get('/ecommerce/add-category', function () {
        return view('admin.ecommerce.add-category');
    })->name('admin.ecommerce.add-category');
    Route::get('/ecommerce/products', function () {
        return view('admin.ecommerce.products');
    })->name('admin.ecommerce.products');
    Route::get('/ecommerce/edit-product', function () {
        return view('admin.ecommerce.edit-product');
    })->name('admin.ecommerce.edit-product');
    Route::get('/ecommerce/categories', function () {
        return view('admin.ecommerce.categories');
    })->name('admin.ecommerce.categories');
    Route::get('/ecommerce/add-product', function () {
        return view('admin.ecommerce.add-product');
    })->name('admin.ecommerce.add-product');
    Route::get('/ecommerce/edit-category', function () {
        return view('admin.ecommerce.edit-category');
    })->name('admin.ecommerce.edit-category');
    Route::get('/index-nft', function () {
        return view('admin.index-nft');
    })->name('admin.index-nft');
    Route::get('/components/toasts', function () {
        return view('admin.components.toasts');
    })->name('admin.components.toasts');
    Route::get('/components/breadcrumb', function () {
        return view('admin.components.breadcrumb');
    })->name('admin.components.breadcrumb');
    Route::get('/components/card', function () {
        return view('admin.components.card');
    })->name('admin.components.card');
    Route::get('/components/collapse', function () {
        return view('admin.components.collapse');
    })->name('admin.components.collapse');
    Route::get('/components/buttons', function () {
        return view('admin.components.buttons');
    })->name('admin.components.buttons');
    Route::get('/components/popovers', function () {
        return view('admin.components.popovers');
    })->name('admin.components.popovers');
    Route::get('/components/placeholders', function () {
        return view('admin.components.placeholders');
    })->name('admin.components.placeholders');
    Route::get('/components/chart', function () {
        return view('admin.components.chart');
    })->name('admin.components.chart');
    Route::get('/components/spinners', function () {
        return view('admin.components.spinners');
    })->name('admin.components.spinners');
    Route::get('/components/accordion', function () {
        return view('admin.components.accordion');
    })->name('admin.components.accordion');
    Route::get('/components/nioicon', function () {
        return view('admin.components.nioicon');
    })->name('admin.components.nioicon');
    Route::get('/components/tooltips', function () {
        return view('admin.components.tooltips');
    })->name('admin.components.tooltips');
    Route::get('/components/button-group', function () {
        return view('admin.components.button-group');
    })->name('admin.components.button-group');
    Route::get('/components/modal', function () {
        return view('admin.components.modal');
    })->name('admin.components.modal');
    Route::get('/components/dropdowns', function () {
        return view('admin.components.dropdowns');
    })->name('admin.components.dropdowns');
    Route::get('/components/progress', function () {
        return view('admin.components.progress');
    })->name('admin.components.progress');
    Route::get('/components/badge', function () {
        return view('admin.components.badge');
    })->name('admin.components.badge');
    Route::get('/components/offcanvas', function () {
        return view('admin.components.offcanvas');
    })->name('admin.components.offcanvas');
    Route::get('/components/data-tables', function () {
        return view('admin.components.data-tables');
    })->name('admin.components.data-tables');
    Route::get('/components/close-button', function () {
        return view('admin.components.close-button');
    })->name('admin.components.close-button');
    Route::get('/components/list-group', function () {
        return view('admin.components.list-group');
    })->name('admin.components.list-group');
    Route::get('/components/navs-tabs', function () {
        return view('admin.components.navs-tabs');
    })->name('admin.components.navs-tabs');
    Route::get('/components/sweet-alert', function () {
        return view('admin.components.sweet-alert');
    })->name('admin.components.sweet-alert');
    Route::get('/components/alerts', function () {
        return view('admin.components.alerts');
    })->name('admin.components.alerts');
    Route::get('/components/pagination', function () {
        return view('admin.components.pagination');
    })->name('admin.components.pagination');
    Route::get('/components/carousel', function () {
        return view('admin.components.carousel');
    })->name('admin.components.carousel');
});
