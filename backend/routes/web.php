<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\auth\ForgotPasswordController;

/*==================================*/
use App\Http\Controllers\Admin\AjaxStatusController;
use App\Http\Controllers\Admin\AdminAuth\LoginAdminController;
use App\Http\Controllers\Admin\AdminAuth\ForgotPasswordAdminController;
use App\Http\Controllers\Admin\AdminAuth\ResetPasswordAdminController;
use App\Http\Controllers\Admin\ContactFormAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\FamilyAdminController;
use App\Http\Controllers\Admin\TagAdminController;
use App\Http\Controllers\Admin\SubTagAdminController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\CatalogDownloadAdminController;
use App\Http\Controllers\Admin\ProjectAdminController;
use App\Http\Controllers\Admin\UploadCSVAdminController;
use App\Http\Controllers\Admin\CompositionAdminController;
use App\Http\Controllers\Admin\AjaxdeleteController;
use App\Http\Controllers\Admin\AjaxUploadFileController;
use App\Http\Controllers\Admin\AttributeAdminController;
use App\Http\Controllers\Admin\GroupAdminController;
use App\Http\Controllers\Admin\VariantAdminController;
use App\Http\Controllers\Admin\ProfileAdminController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\EventAdminController;
use App\Http\Controllers\Admin\DecorativeProductController;
use App\Http\Controllers\Admin\DecorativeCategoryController;
use App\Http\Controllers\Admin\DecorativeVariantController;
use App\Http\Controllers\Admin\JobAdminController;
use App\Http\Controllers\Admin\ClientAdminController;
use App\Http\Controllers\Admin\HomeSliderController as AdminHomeSliderController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\HomeController as WebsiteHomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeSliderController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProductController;
use App\Models\Client;
use App\Models\HomeSlider;
use App\Models\Inquiry;
use App\Models\JobOpening;
use App\Http\Controllers\DataCollectionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WebsiteHomeController::class, 'index'])->name('pages.home');

// DEBUG: Test CSS URL generation
Route::get('/test-css-urls', function() {
    return view('test-css');
});

Route::get('fair-events', [EventController::class, 'index'])->name('page.fair-events');
Route::get('event-detail/{any}', [EventController::class, 'show'])->name('event.detail');


Route::get('projects', [ProjectController::class, 'index'])->name('page.projects');
Route::get('projects/{any}', [ProjectController::class, 'show'])->name('project-detail');
Route::get('projects/category/{any}', [ProjectController::class, 'projectsByFilter'])->name('projects-with-filter');

Route::get('products', [ProductController::class, 'subTags'])->name('sub-tags');
Route::get('products/{any}', [ProductController::class, 'products'])->name('products');
Route::get('product/{any}', [ProductController::class, 'product'])->name('product');
Route::get('product/{any}/pdf', [ProductController::class, 'pdf'])->name('product.pdf');
Route::get('product/{any}/download-pdf', [ProductController::class, 'downloadPdf'])->name('product.download-pdf');
Route::get('products/category/{any}', [ProductController::class, 'subTagsByFilter'])->name('sub-tags-with-filter');

Route::get('search', [ProductController::class, 'search'])->name('search');
Route::get('category/{any}', [ProductController::class, 'search'])->name('category');


Route::get('subscribe-newsletter', [WebsiteHomeController::class, 'subscribe'])->name('subscribe');
Route::get('verify_email/{any}', [WebsiteHomeController::class, 'verify_email'])->name('verify_email');

Route::get('career', function () {
    $openings = JobOpening::all();
    return view('pages.career', compact('openings'));
})->name('page.career');

Route::get('contact', function () {
    return view('pages.contact');
})->name('page.contact');

Route::post('contact', [DataCollectionController::class, 'contact'])->name('mail.contact.send');
Route::post('product-inquiry', [DataCollectionController::class, 'productInquiry'])->name('mail.product.send');

Route::get('company', function () {
    return view('pages.company');
})->name('page.company');

Route::get('product-internal', function () {
    return view('pages.product-internal');
})->name('page.product-internal');

Route::get('terms-and-conditions', function () {
    return view('pages.terms-and-conditions');
})->name('page.terms-and-conditions');

Route::get('abby-smart', function () {
    return view('pages.abby-smart');
})->name('page.abby-smart');

Route::get('clients', function () {
    $clients = Client::all();
    return view('pages.clients', compact('clients'));
})->name('page.clients');

Route::get('privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('page.privacy-policy');

Route::post('/catalog-download-user-form', [WebsiteHomeController::class, 'catalogDownloadUserForm'])->name('catalog-download-user-form');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [LoginAdminController::class, 'showLoginForm'])->name('login_admin_main');
    Route::get('/login', [LoginAdminController::class, 'showLoginForm'])->name('login_admin');
    Route::post('/login', [LoginAdminController::class, 'login']);
    Route::post('/logout', [LoginAdminController::class, 'logout'])->name('logoutadmin');
    Route::get('/password/reset', [ForgotPasswordAdminController::class, 'showLinkRequestForm'])->name('forgotpassword_admin.reset');
    Route::post('/password/email', [ForgotPasswordAdminController::class, 'sendResetLinkEmail'])->name('forgotpassword_admin.email');

    Route::get('/password/reset/{token}', [ResetPasswordAdminController::class, 'showResetForm']);
    Route::post('/password/reset/{any}', [ResetPasswordAdminController::class, 'reset']);



    Route::get('/dashboard', [HomeController::class, 'index']);

    Route::middleware(['verifyAdminUser', 'restrict.specgen'])->group(function () {
        Route::post('/status', [AjaxstatusController::class, 'index'])->name('status');
        Route::post('/delete', [AjaxdeleteController::class, 'index'])->name('delete');
        Route::post('/upload-file', [AjaxUploadFileController::class, 'index'])->name('upload-file');

        /********************DECORATIVE PRODUCTS********************/
        Route::group(['prefix' => 'decorative-products'], function () {
            Route::get('/', [DecorativeProductController::class, 'index'])->name('decorative_product_admin');
            Route::get('/create', [DecorativeProductController::class, 'create'])->name('decorative_product_admin.add');
            Route::post('/', [DecorativeProductController::class, 'store'])->name('decorative_product_admin.store');
            Route::get('/{id}/edit', [DecorativeProductController::class, 'edit'])->name('decorative_product_admin.edit');
            Route::put('/{id}', [DecorativeProductController::class, 'update'])->name('decorative_product_admin.update');
            Route::post('/{id}/duplicate', [DecorativeProductController::class, 'duplicate'])->name('decorative_product_admin.duplicate');
            Route::post('/{id}/seo', [DecorativeProductController::class, 'updateSeo'])->name('decorative_product_admin.updateSeo');
            Route::delete('/{id}', [DecorativeProductController::class, 'destroy'])->name('decorative_product_admin.destroy');

            // Phase 7 - Downloads
            Route::post('/{product_id}/downloads', [DecorativeProductController::class, 'uploadDownload'])->name('decorative_product_admin.downloads.upload');
            Route::delete('/{product_id}/downloads', [DecorativeProductController::class, 'deleteDownload'])->name('decorative_product_admin.downloads.delete');

            // Phase 8 - Related Products
            Route::get('/{product_id}/related', [\App\Http\Controllers\Admin\DecorativeRelatedController::class, 'index'])->name('decorative_product_admin.related.index');
            Route::post('/{product_id}/related/toggle-family', [\App\Http\Controllers\Admin\DecorativeRelatedController::class, 'toggleFamily'])->name('decorative_product_admin.related.toggleFamily');
            Route::get('/related/search', [\App\Http\Controllers\Admin\DecorativeRelatedController::class, 'search'])->name('decorative_product_admin.related.search');
            Route::post('/{product_id}/related', [\App\Http\Controllers\Admin\DecorativeRelatedController::class, 'attach'])->name('decorative_product_admin.related.attach');
            Route::delete('/related/{id}', [\App\Http\Controllers\Admin\DecorativeRelatedController::class, 'detach'])->name('decorative_product_admin.related.detach');
            Route::post('/{product_id}/related/reorder', [\App\Http\Controllers\Admin\DecorativeRelatedController::class, 'reorder'])->name('decorative_product_admin.related.reorder');

            // Variants
            Route::post('/{product_id}/variants', [DecorativeVariantController::class, 'store'])->name('decorative_product_admin.variants.store');
            Route::get('/variants/{id}', [DecorativeVariantController::class, 'show'])->name('decorative_product_admin.variants.show');
            Route::put('/variants/{id}', [DecorativeVariantController::class, 'update'])->name('decorative_product_admin.variants.update');
            Route::post('/variants/{id}/images', [DecorativeVariantController::class, 'updateImages'])->name('decorative_product_admin.variants.updateImages');
            Route::delete('/variants/{id}', [DecorativeVariantController::class, 'destroy'])->name('decorative_product_admin.variants.destroy');
            Route::post('/{product_id}/variants/reorder', [DecorativeVariantController::class, 'reorder'])->name('decorative_product_admin.variants.reorder');

            // Decorative Product Gallery
            Route::post('/{product_id}/gallery', [\App\Http\Controllers\Admin\DecorativeGalleryController::class, 'store'])->name('decorative_product_admin.gallery.store');
            Route::post('/{product_id}/gallery/reorder', [\App\Http\Controllers\Admin\DecorativeGalleryController::class, 'reorder'])->name('decorative_product_admin.gallery.reorder');
            Route::put('/gallery/{id}', [\App\Http\Controllers\Admin\DecorativeGalleryController::class, 'update'])->name('decorative_product_admin.gallery.update');
            Route::delete('/gallery/{id}', [\App\Http\Controllers\Admin\DecorativeGalleryController::class, 'destroy'])->name('decorative_product_admin.gallery.destroy');

            // Decorative Specifications
            Route::get('/variants/{variant_id}/specs', [\App\Http\Controllers\Admin\DecorativeSpecController::class, 'getRows'])->name('decorative_product_admin.specs.get');
            Route::post('/variants/{variant_id}/specs', [\App\Http\Controllers\Admin\DecorativeSpecController::class, 'store'])->name('decorative_product_admin.specs.store');
            Route::put('/specs/{id}', [\App\Http\Controllers\Admin\DecorativeSpecController::class, 'update'])->name('decorative_product_admin.specs.update');
            Route::delete('/specs/{id}', [\App\Http\Controllers\Admin\DecorativeSpecController::class, 'destroy'])->name('decorative_product_admin.specs.destroy');
            Route::post('/variants/{variant_id}/specs/reorder', [\App\Http\Controllers\Admin\DecorativeSpecController::class, 'reorder'])->name('decorative_product_admin.specs.reorder');
            
            // Phase 6 - Copy & Templates
            Route::post('/variants/{variant_id}/specs/copy', [\App\Http\Controllers\Admin\DecorativeSpecController::class, 'copyFromVariant'])->name('decorative_product_admin.specs.copy');
            Route::get('/specs/templates', [\App\Http\Controllers\Admin\DecorativeSpecController::class, 'getTemplates'])->name('decorative_product_admin.specs.templates.get');
            Route::post('/variants/{variant_id}/specs/templates', [\App\Http\Controllers\Admin\DecorativeSpecController::class, 'saveTemplate'])->name('decorative_product_admin.specs.templates.save');
            Route::post('/variants/{variant_id}/specs/templates/apply', [\App\Http\Controllers\Admin\DecorativeSpecController::class, 'applyTemplate'])->name('decorative_product_admin.specs.templates.apply');
        });

        Route::group(['prefix' => 'decorative-categories'], function () {
            Route::get('/', [DecorativeCategoryController::class, 'index'])->name('decorative_category_admin');
            Route::get('/create', [DecorativeCategoryController::class, 'create'])->name('decorative_category_admin.add');
            Route::post('/', [DecorativeCategoryController::class, 'store'])->name('decorative_category_admin.store');
            Route::get('/{id}/edit', [DecorativeCategoryController::class, 'edit'])->name('decorative_category_admin.edit');
            Route::put('/{id}', [DecorativeCategoryController::class, 'update'])->name('decorative_category_admin.update');
            Route::delete('/{id}', [DecorativeCategoryController::class, 'destroy'])->name('decorative_category_admin.destroy');
        });

        /********************CONTACT FORMS********************/
        Route::get('/contact-forms', [ContactFormAdminController::class, 'index'])->name('contact_form_admin');
        Route::post('/contact-forms/upload-banner',[ContactFormAdminController::class,'uploadBanner'])->name('contact_form_admin.upload');
        Route::get('/contact-forms/list', [ContactFormAdminController::class, 'list'])->name('contact_form_admin.list');
        Route::get('/subscriptions', [ContactFormAdminController::class, 'subscriptions'])->name('subscriptions_admin');
        Route::get('/subscriptions/list', [ContactFormAdminController::class, 'subscriptions_list'])->name('subscriptions_admin.list');

        /********************CATEGORIES********************/
        Route::get('/categories', [CategoryAdminController::class, 'index'])->name('category_admin');
        Route::get('/categories/list', [CategoryAdminController::class, 'list'])->name('category_admin.list');
        Route::get('/categories/add', [CategoryAdminController::class, 'add'])->name('category_admin.add');
        Route::get('/categories/edit/{any}', [CategoryAdminController::class, 'edit'])->name('category_admin.edit');
        Route::post('/categories/insert', [CategoryAdminController::class, 'insert'])->name('category_admin.insert');
        Route::post('/categories/update/{any}', [CategoryAdminController::class, 'update'])->name('category_admin.update');
        Route::get('/categories/information/{any}', [CategoryAdminController::class, 'information'])->name('category_admin.information');

        /********************FAMILIES********************/
        Route::get('/families', [FamilyAdminController::class, 'index'])->name('family_admin');
        Route::get('/families/add', [FamilyAdminController::class, 'add'])->name('family_admin.add');
        Route::get('/families/add_product', [FamilyAdminController::class, 'add_product'])->name('add_product.add');
        Route::get('/families/edit/{any}', [FamilyAdminController::class, 'edit'])->name('family_admin.edit');

        /********************TAGS********************/
        Route::get('/tags', [TagAdminController::class, 'index'])->name('tag_admin');
        Route::get('/tags/add', [TagAdminController::class, 'add'])->name('tag_admin.add');
        Route::post('/tags/insert', [TagAdminController::class, 'insert'])->name('tag_admin.insert');
        Route::get('/tags/edit/{any}', [TagAdminController::class, 'edit'])->name('tag_admin.edit');
        Route::post('/tags/update/{any}', [TagAdminController::class, 'update'])->name('tag_admin.update');

        /********************SUB TAGS********************/
        Route::get('/sub_tags', [SubTagAdminController::class, 'index'])->name('sub_tag_admin');
        Route::get('/sub_tags/add', [SubTagAdminController::class, 'add'])->name('sub_tag_admin.add');
        Route::post('/sub_tags/insert', [SubTagAdminController::class, 'insert'])->name('sub_tag_admin.insert');
        Route::get('/sub_tags/edit/{any}', [SubTagAdminController::class, 'edit'])->name('sub_tag_admin.edit');
        Route::post('/sub_tags/update/{any}', [SubTagAdminController::class, 'update'])->name('sub_tag_admin.update');
        Route::post('/sub_tags/upload-banner', [SubTagAdminController::class, 'uploadBanner'])->name('sub_tag_admin.upload');
        Route::get('sub_tags_exports', [SubTagAdminController::class, 'exports']);

        /********************PRODUCTS********************/
        Route::get('/product', [ProductAdminController::class, 'index'])->name('product_admin');
        Route::get('/product/list', [ProductAdminController::class, 'list'])->name('product_admin.list');
        Route::get('/product/add', [ProductAdminController::class, 'add'])->name('product_admin.add');
        Route::post('/product/insert', [ProductAdminController::class, 'insert'])->name('product_admin.insert');
        Route::get('/product/edit/{any}', [ProductAdminController::class, 'edit'])->name('product_admin.edit');
        Route::post('/product/update/{any}', [ProductAdminController::class, 'update'])->name('product_admin.update');
        Route::get('/product/information/{any}', [ProductAdminController::class, 'information'])->name('product_admin.information');
        Route::post('/product-variant/insert', [ProductAdminController::class, 'product_variant_insert'])->name('product_admin.variant_insert');
        Route::post('/delete-variant', [ProductAdminController::class, 'delete_variant'])->name('delete_variant');
        Route::post('/edit-variant/{any}', [ProductAdminController::class, 'edit_variant'])->name('edit_variant');
        Route::post('/update-variant/{any}', [ProductAdminController::class, 'update_variant'])->name('update_variant');
        Route::get('products_exports', [ProductAdminController::class, 'exports']);
        Route::post('/product/duplicate/{id}', [ProductAdminController::class, 'duplicate'])->name('product_admin.duplicate');


        /********************CATALOG DOWNLOADS********************/
        Route::get('/catalog', [CatalogDownloadAdminController::class, 'index'])->name('catalog_admin');
        Route::get('/catalog/list', [CatalogDownloadAdminController::class, 'list'])->name('catalog_admin.list');
        Route::delete('/catalog/delete/{id}', [CatalogDownloadAdminController::class, 'destroy'])->name('catalog_admin.destroy');
        Route::post('/upload-catalog', [CatalogDownloadAdminController::class, 'uploadCatalog'])->name('catalog_admin.upload');

        /********************COMPOSITIONS********************/
        Route::get('/compositions', [CompositionAdminController::class, 'index'])->name('composition_admin');
        Route::get('/compositions/list', [CompositionAdminController::class, 'list'])->name('composition_admin.list');
        Route::get('/compositions/add', [CompositionAdminController::class, 'add'])->name('composition_admin.add');
        Route::post('/compositions/insert', [CompositionAdminController::class, 'insert'])->name('composition_admin.insert');
        Route::get('/compositions/edit/{any}', [CompositionAdminController::class, 'edit'])->name('composition_admin.edit');
        Route::post('/compositions/update/{any}', [CompositionAdminController::class, 'update'])->name('composition_admin.update');

        /********************PROJECT********************/
        Route::get('/project', [ProjectAdminController::class, 'index'])->name('project_admin');
        Route::get('/project/list', [ProjectAdminController::class, 'list'])->name('project_admin.list');
        Route::get('/project/add', [ProjectAdminController::class, 'add'])->name('project_admin.add');
        Route::post('/project/insert', [ProjectAdminController::class, 'insert'])->name('project_admin.insert');
        Route::get('/project/edit/{any}', [ProjectAdminController::class, 'edit'])->name('project_admin.edit');
        Route::post('/project/update/{any}', [ProjectAdminController::class, 'update'])->name('project_admin.update');
        Route::get('/project/information/{any}', [ProjectAdminController::class, 'information'])->name('project_admin.information');
        Route::get('/project-images/{any}', [ProjectAdminController::class, 'project_images'])->name('project_images');
        Route::get('projects_exports', [ProjectAdminController::class, 'exports']);

        /********************UPLOAD CSV********************/
        Route::get('/upload-csv', [UploadCSVAdminController::class, 'index'])->name('upload_csv_admin');

        /********************ATTRIBUTE MASTER********************/
        Route::get('/attributes', [AttributeAdminController::class, 'index'])->name('attribute_admin');
        Route::get('/attributes/list', [AttributeAdminController::class, 'list'])->name('attribute_admin.list');
        Route::get('/attributes/add', [AttributeAdminController::class, 'add'])->name('attribute_admin.add');
        Route::post('/attributes/insert', [AttributeAdminController::class, 'insert'])->name('attribute_admin.insert');
        Route::get('/attributes/edit/{any}', [AttributeAdminController::class, 'edit'])->name('attribute_admin.edit');
        Route::post('/attributes/update/{any}', [AttributeAdminController::class, 'update'])->name('attribute_admin.update');

        /********************GROUP MASTER********************/
        Route::get('/groups', [GroupAdminController::class, 'index'])->name('group_admin');
        Route::get('/groups/list', [GroupAdminController::class, 'list'])->name('group_admin.list');
        Route::get('/groups/add', [GroupAdminController::class, 'add'])->name('group_admin.add');
        Route::post('/groups/insert', [GroupAdminController::class, 'insert'])->name('group_admin.insert');
        Route::get('/groups/edit/{any}', [GroupAdminController::class, 'edit'])->name('group_admin.edit');
        Route::post('/groups/update/{any}', [GroupAdminController::class, 'update'])->name('group_admin.update');

        /********************VARIANT MASTER********************/
        Route::get('/variants', [VariantAdminController::class, 'index'])->name('variant_admin');
        Route::get('/variants/list', [VariantAdminController::class, 'list'])->name('variant_admin.list');
        Route::get('/variants/add', [VariantAdminController::class, 'add'])->name('variant_admin.add');
        Route::post('/variants/insert', [VariantAdminController::class, 'insert'])->name('variant_admin.insert');
        Route::get('/variants/edit/{any}', [VariantAdminController::class, 'edit'])->name('variant_admin.edit');
        Route::post('/variants/update/{any}', [VariantAdminController::class, 'update'])->name('variant_admin.update');

        Route::get('/variant-attributes', [VariantAdminController::class, 'variant_attribute_index'])->name('variant_attribute_admin');
        Route::get('/variant-attributes/list', [VariantAdminController::class, 'variant_attr_list'])->name('variant_attribute_admin.list');
        Route::get('/variant-attributes/add', [VariantAdminController::class, 'variant_attr_add'])->name('variant_attribute_admin.add');
        Route::post('/variant-attributes/insert', [VariantAdminController::class, 'variant_attr_insert'])->name('variant_attribute_admin.insert');
        Route::get('/variant-attributes/edit/{any}', [VariantAdminController::class, 'variant_attr_edit'])->name('variant_attribute_admin.edit');
        Route::post('/variant-attributes/update/{any}', [VariantAdminController::class, 'variant_attr_update'])->name('variant_attribute_admin.update');

        Route::get('/change-password', [ProfileAdminController::class, 'index'])->name('change_pass');
        Route::post('/change-password/update', [ProfileAdminController::class, 'update'])->name('change_pass.update');

        /********************ICONS********************/
        Route::get('/icons', [IconAdminController::class, 'index'])->name('icon_admin');
        Route::get('/icons/list', [IconAdminController::class, 'list'])->name('icon_admin.list');
        Route::get('/icons/add', [IconAdminController::class, 'add'])->name('icon_admin.add');
        Route::post('/icons/insert', [IconAdminController::class, 'insert'])->name('icon_admin.insert');
        Route::get('/icons/edit/{any}', [IconAdminController::class, 'edit'])->name('icon_admin.edit');
        Route::post('/icons/update/{any}', [IconAdminController::class, 'update'])->name('icon_admin.update');
        Route::get('/icons/information/{any}', [IconAdminController::class, 'information'])->name('icon_admin.information');

        /********************ICONS********************/
        Route::get('/events', [EventAdminController::class, 'index'])->name('event_admin');
        Route::get('/events/list', [EventAdminController::class, 'list'])->name('event_admin.list');
        Route::get('/events/add', [EventAdminController::class, 'add'])->name('event_admin.add');
        Route::post('/events/insert', [EventAdminController::class, 'insert'])->name('event_admin.insert');
        Route::get('/events/edit/{any}', [EventAdminController::class, 'edit'])->name('event_admin.edit');
        Route::post('/events/update/{any}', [EventAdminController::class, 'update'])->name('event_admin.update');
        Route::get('/events/information/{any}', [EventAdminController::class, 'information'])->name('event_admin.information');

        /********************CATEGORIES********************/
        Route::get('/jobs', [JobAdminController::class, 'index'])->name('job_admin');
        Route::get('/jobs/list', [JobAdminController::class, 'list'])->name('job_admin.list');
        Route::get('/jobs/add', [JobAdminController::class, 'add'])->name('job_admin.add');
        Route::get('/jobs/edit/{any}', [JobAdminController::class, 'edit'])->name('job_admin.edit');
        Route::post('/jobs/insert', [JobAdminController::class, 'insert'])->name('job_admin.insert');
        Route::post('/jobs/update/{any}', [JobAdminController::class, 'update'])->name('job_admin.update');
        Route::get('/jobs/information/{any}', [JobAdminController::class, 'information'])->name('job_admin.information');
        Route::post('/jobs/upload-banner', [JobAdminController::class, 'uploadBanner'])->name('job_admin.upload');

        /********************CATEGORIES********************/
        Route::get('/clients', [ClientAdminController::class, 'index'])->name('client_admin');
        Route::get('/clients/list', [ClientAdminController::class, 'list'])->name('client_admin.list');
        Route::get('/clients/add', [ClientAdminController::class, 'add'])->name('client_admin.add');
        Route::get('/clients/edit/{any}', [ClientAdminController::class, 'edit'])->name('client_admin.edit');
        Route::post('/clients/insert', [ClientAdminController::class, 'insert'])->name('client_admin.insert');
        Route::post('/clients/update/{any}', [ClientAdminController::class, 'update'])->name('client_admin.update');
        Route::get('/clients/information/{any}', [ClientAdminController::class, 'information'])->name('client_admin.information');
        Route::post('/clients/upload-banner', [ClientAdminController::class, 'uploadBanner'])->name('client_admin.upload');

        /********************CATEGORIES********************/
        Route::get('/homeslider', [AdminHomeSliderController::class, 'index'])->name('homeslider_admin');
        Route::get('/homeslider/add', [AdminHomeSliderController::class, 'add'])->name('homeslider_admin.add');
        Route::get('/homeslider/edit/{any}', [AdminHomeSliderController::class, 'edit'])->name('homeslider_admin.edit');
        Route::post('/homeslider/insert', [AdminHomeSliderController::class, 'insert'])->name('homeslider_admin.insert');
        Route::post('/homeslider/update/{any}', [AdminHomeSliderController::class, 'update'])->name('homeslider_admin.update');

        /********************LIGHT WORLDS********************/
        Route::get('/light-worlds', [App\Http\Controllers\Admin\LightWorldController::class, 'index'])->name('light_worlds_admin');
        Route::get('/light-worlds/add', [App\Http\Controllers\Admin\LightWorldController::class, 'add'])->name('light_worlds_admin.add');
        Route::get('/light-worlds/edit/{id}', [App\Http\Controllers\Admin\LightWorldController::class, 'edit'])->name('light_worlds_admin.edit');
        Route::post('/light-worlds/insert', [App\Http\Controllers\Admin\LightWorldController::class, 'insert'])->name('light_worlds_admin.insert');
        Route::post('/light-worlds/update/{id}', [App\Http\Controllers\Admin\LightWorldController::class, 'update'])->name('light_worlds_admin.update');
        Route::delete('/light-worlds/delete/{id}', [App\Http\Controllers\Admin\LightWorldController::class, 'delete'])->name('light_worlds_admin.delete');

        /********************MANUFACTURING SECTION / HOMEPAGE SETTINGS********************/
        Route::get('/manufacturing-section', [App\Http\Controllers\Admin\ManufacturingSectionController::class, 'index'])->name('admin.manufacturing.index');
        Route::get('/homepage-settings/{id?}', [App\Http\Controllers\Admin\ManufacturingSectionController::class, 'edit'])->name('admin.manufacturing.edit');
        Route::put('/homepage-settings/update', [App\Http\Controllers\Admin\ManufacturingSectionController::class, 'update'])->name('admin.manufacturing.update');

        /********************NEWS SECTION********************/
        Route::get('/news-section-settings/{id?}', [App\Http\Controllers\Admin\NewsSectionController::class, 'edit'])->name('admin.news-section.edit');
        Route::put('/news-section-settings/update', [App\Http\Controllers\Admin\NewsSectionController::class, 'update'])->name('admin.news-section.update');

        /********************NEWS ITEMS********************/
        Route::get('/news-items', [App\Http\Controllers\Admin\NewsItemController::class, 'index'])->name('admin.news-items.index');
        Route::get('/news-items/add', [App\Http\Controllers\Admin\NewsItemController::class, 'add'])->name('admin.news-items.add');
        Route::post('/news-items/store', [App\Http\Controllers\Admin\NewsItemController::class, 'store'])->name('admin.news-items.store');
        Route::get('/news-items/edit/{id}', [App\Http\Controllers\Admin\NewsItemController::class, 'edit'])->name('admin.news-items.edit');
        Route::put('/news-items/update/{id}', [App\Http\Controllers\Admin\NewsItemController::class, 'update'])->name('admin.news-items.update');
        Route::delete('/news-items/delete/{id}', [App\Http\Controllers\Admin\NewsItemController::class, 'delete'])->name('admin.news-items.delete');
        /********************BLOG CATEGORIES********************/
        Route::get('/blog-categories', [App\Http\Controllers\Admin\BlogCategoryController::class, 'index'])->name('admin.blog-categories.index');
        Route::get('/blog-categories/add', [App\Http\Controllers\Admin\BlogCategoryController::class, 'add'])->name('admin.blog-categories.add');
        Route::post('/blog-categories/store', [App\Http\Controllers\Admin\BlogCategoryController::class, 'store'])->name('admin.blog-categories.store');
        Route::get('/blog-categories/edit/{id}', [App\Http\Controllers\Admin\BlogCategoryController::class, 'edit'])->name('admin.blog-categories.edit');
        Route::put('/blog-categories/update/{id}', [App\Http\Controllers\Admin\BlogCategoryController::class, 'update'])->name('admin.blog-categories.update');
        Route::delete('/blog-categories/delete/{id}', [App\Http\Controllers\Admin\BlogCategoryController::class, 'destroy'])->name('admin.blog-categories.destroy');

        /********************BLOGS********************/
        Route::get('/blogs', [App\Http\Controllers\Admin\BlogAdminController::class, 'index'])->name('admin.blogs.index');
        Route::get('/blogs/add', [App\Http\Controllers\Admin\BlogAdminController::class, 'add'])->name('admin.blogs.add');
        Route::post('/blogs/store', [App\Http\Controllers\Admin\BlogAdminController::class, 'store'])->name('admin.blogs.store');
        Route::get('/blogs/edit/{id}', [App\Http\Controllers\Admin\BlogAdminController::class, 'edit'])->name('admin.blogs.edit');
        Route::put('/blogs/update/{id}', [App\Http\Controllers\Admin\BlogAdminController::class, 'update'])->name('admin.blogs.update');
        Route::delete('/blogs/delete/{id}', [App\Http\Controllers\Admin\BlogAdminController::class, 'destroy'])->name('admin.blogs.destroy');
        Route::post('/blogs/upload-image', [App\Http\Controllers\Admin\BlogAdminController::class, 'uploadImage'])->name('admin.blogs.upload_image');

        /********************WATCH & SHOP (REELS)********************/
        Route::get('/watch-and-shops', [App\Http\Controllers\Admin\WatchAndShopController::class, 'index'])->name('admin.watch_and_shops.index');
        Route::get('/watch-and-shops/add', [App\Http\Controllers\Admin\WatchAndShopController::class, 'add'])->name('admin.watch_and_shops.add');
        Route::post('/watch-and-shops/store', [App\Http\Controllers\Admin\WatchAndShopController::class, 'store'])->name('admin.watch_and_shops.store');
        Route::get('/watch-and-shops/edit/{id}', [App\Http\Controllers\Admin\WatchAndShopController::class, 'edit'])->name('admin.watch_and_shops.edit');
        Route::put('/watch-and-shops/update/{id}', [App\Http\Controllers\Admin\WatchAndShopController::class, 'update'])->name('admin.watch_and_shops.update');
        Route::delete('/watch-and-shops/delete/{id}', [App\Http\Controllers\Admin\WatchAndShopController::class, 'delete'])->name('admin.watch_and_shops.delete');

        /********************CATALOGUE CATEGORIES********************/
        Route::get('/catalogue-categories', [App\Http\Controllers\Admin\CatalogueCategoryController::class, 'index'])->name('admin.catalogue-categories.index');
        Route::get('/catalogue-categories/add', [App\Http\Controllers\Admin\CatalogueCategoryController::class, 'add'])->name('admin.catalogue-categories.add');
        Route::post('/catalogue-categories/store', [App\Http\Controllers\Admin\CatalogueCategoryController::class, 'store'])->name('admin.catalogue-categories.store');
        Route::get('/catalogue-categories/edit/{id}', [App\Http\Controllers\Admin\CatalogueCategoryController::class, 'edit'])->name('admin.catalogue-categories.edit');
        Route::put('/catalogue-categories/update/{id}', [App\Http\Controllers\Admin\CatalogueCategoryController::class, 'update'])->name('admin.catalogue-categories.update');
        Route::delete('/catalogue-categories/delete/{id}', [App\Http\Controllers\Admin\CatalogueCategoryController::class, 'destroy'])->name('admin.catalogue-categories.destroy');

        /********************CATALOGUES********************/
        Route::get('/catalogues', [App\Http\Controllers\Admin\CatalogueController::class, 'index'])->name('admin.catalogues.index');
        Route::get('/catalogues/add', [App\Http\Controllers\Admin\CatalogueController::class, 'add'])->name('admin.catalogues.add');
        Route::post('/catalogues/store', [App\Http\Controllers\Admin\CatalogueController::class, 'store'])->name('admin.catalogues.store');
        Route::get('/catalogues/edit/{id}', [App\Http\Controllers\Admin\CatalogueController::class, 'edit'])->name('admin.catalogues.edit');
        Route::put('/catalogues/update/{id}', [App\Http\Controllers\Admin\CatalogueController::class, 'update'])->name('admin.catalogues.update');
        Route::delete('/catalogues/delete/{id}', [App\Http\Controllers\Admin\CatalogueController::class, 'destroy'])->name('admin.catalogues.destroy');



        /********************COLOR MASTERS********************/
        Route::get('/color-masters', [App\Http\Controllers\Admin\ColorMasterController::class, 'index'])->name('color_master_admin');
        Route::get('/color-masters/add', [App\Http\Controllers\Admin\ColorMasterController::class, 'add'])->name('color_master_admin.add');
        Route::post('/color-masters/insert', [App\Http\Controllers\Admin\ColorMasterController::class, 'insert'])->name('color_master_admin.insert');
        Route::get('/color-masters/edit/{any}', [App\Http\Controllers\Admin\ColorMasterController::class, 'edit'])->name('color_master_admin.edit');
        Route::post('/color-masters/update/{any}', [App\Http\Controllers\Admin\ColorMasterController::class, 'update'])->name('color_master_admin.update');
        Route::delete('/color-masters/delete/{any}', [App\Http\Controllers\Admin\ColorMasterController::class, 'delete'])->name('color_master_admin.delete');
        Route::get('/color-masters/information/{any}', [App\Http\Controllers\Admin\ColorMasterController::class, 'information'])->name('color_master_admin.information');

        /********************COLLECTIONS********************/
        Route::get('/collections', [CollectionController::class, 'index'])->name('admin.collections.index');
        Route::get('/collections/create', [CollectionController::class, 'create'])->name('admin.collections.create');
        Route::post('/collections', [CollectionController::class, 'store'])->name('admin.collections.store');
        Route::get('/collections/{collection}/edit', [CollectionController::class, 'edit'])->name('admin.collections.edit');
        Route::put('/collections/{collection}', [CollectionController::class, 'update'])->name('admin.collections.update');
        Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])->name('admin.collections.destroy');
        Route::patch('/collections/{collection}/toggle-active', [CollectionController::class, 'toggleActive'])->name('admin.collections.toggle-active');
        Route::post('/collections/{collection}/hero-section', [CollectionController::class, 'storeHeroSection'])->name('admin.collections.store-hero');
        Route::post('/collections/{collection}/parameters-section', [CollectionController::class, 'storeParametersSection'])->name('admin.collections.store-parameters');
        
        // Parameter Items CRUD
        Route::get('/collections/{collection}/parameter-items/add', [CollectionController::class, 'addParameterItem'])->name('admin.collections.parameter-items.add');
        Route::post('/collections/{collection}/parameter-items', [CollectionController::class, 'storeParameterItem'])->name('admin.collections.parameter-items.store');
        Route::get('/collections/{collection}/parameter-items/{item}/edit', [CollectionController::class, 'editParameterItem'])->name('admin.collections.parameter-items.edit');
        Route::put('/collections/{collection}/parameter-items/{item}', [CollectionController::class, 'updateParameterItem'])->name('admin.collections.parameter-items.update');
        Route::delete('/collections/{collection}/parameter-items/{item}', [CollectionController::class, 'deleteParameterItem'])->name('admin.collections.parameter-items.delete');

        // Compositions Section
        Route::post('/collections/{collection}/compositions-section', [CollectionController::class, 'storeCompositionsSection'])->name('admin.collections.store-compositions');
        
        // Composition Items CRUD
        Route::get('/collections/{collection}/composition-items/add', [CollectionController::class, 'addCompositionItem'])->name('admin.collections.composition-items.add');
        Route::post('/collections/{collection}/composition-items', [CollectionController::class, 'storeCompositionItem'])->name('admin.collections.composition-items.store');
        Route::get('/collections/{collection}/composition-items/{item}/edit', [CollectionController::class, 'editCompositionItem'])->name('admin.collections.composition-items.edit');
        Route::put('/collections/{collection}/composition-items/{item}', [CollectionController::class, 'updateCompositionItem'])->name('admin.collections.composition-items.update');
        Route::delete('/collections/{collection}/composition-items/{item}', [CollectionController::class, 'deleteCompositionItem'])->name('admin.collections.composition-items.delete');

        // Tones Section
        Route::post('/collections/{collection}/tones-section', [CollectionController::class, 'storeTonesSection'])->name('admin.collections.store-tones');
        
        // Tone Families CRUD
        Route::get('/collections/{collection}/tone-families/add', [CollectionController::class, 'addToneFamily'])->name('admin.collections.tone-families.add');
        Route::post('/collections/{collection}/tone-families', [CollectionController::class, 'storeToneFamily'])->name('admin.collections.tone-families.store');
        Route::get('/collections/{collection}/tone-families/{family}/edit', [CollectionController::class, 'editToneFamily'])->name('admin.collections.tone-families.edit');
        Route::put('/collections/{collection}/tone-families/{family}', [CollectionController::class, 'updateToneFamily'])->name('admin.collections.tone-families.update');
        Route::delete('/collections/{collection}/tone-families/{family}', [CollectionController::class, 'deleteToneFamily'])->name('admin.collections.tone-families.delete');

        // Places Section
        Route::post('/collections/{collection}/places-section', [CollectionController::class, 'storePlacesSection'])->name('admin.collections.store-places');
        
        // Place Items CRUD
        Route::get('/collections/{collection}/place-items/add', [CollectionController::class, 'addPlaceItem'])->name('admin.collections.place-items.add');
        Route::post('/collections/{collection}/place-items', [CollectionController::class, 'storePlaceItem'])->name('admin.collections.place-items.store');
        Route::get('/collections/{collection}/place-items/{item}/edit', [CollectionController::class, 'editPlaceItem'])->name('admin.collections.place-items.edit');
        Route::put('/collections/{collection}/place-items/{item}', [CollectionController::class, 'updatePlaceItem'])->name('admin.collections.place-items.update');
        Route::delete('/collections/{collection}/place-items/{item}', [CollectionController::class, 'deletePlaceItem'])->name('admin.collections.place-items.delete');

        // Spread & Drop Section
        Route::post('/collections/{collection}/spread-drop-section', [CollectionController::class, 'storeSpreadDropSection'])->name('admin.collections.store-spread-drop');

        // DEBUG ROUTES - REMOVE IN PRODUCTION
        Route::get('/debug/tone-families', [CollectionController::class, 'debugToneFamilies'])->name('admin.debug.tone-families');
        Route::get('/debug/tone-families/{family}/test-edit', [CollectionController::class, 'debugEditToneFamily'])->name('admin.debug.tone-families.edit');
        Route::post('/debug/tone-families/{family}/test-update', [CollectionController::class, 'debugUpdateToneFamily'])->name('admin.debug.tone-families.update');

    });
});
