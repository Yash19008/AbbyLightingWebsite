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
use App\Http\Controllers\Admin\IconAdminController;
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

Route::controller(WebsiteHomeController::class)->group(function () {
    Route::get('/', 'index')->name('pages.home');
    Route::get('subscribe-newsletter', 'subscribe')->name('subscribe');
    Route::get('verify_email/{any}', 'verify_email')->name('verify_email');
    Route::post('/catalog-download-user-form', 'catalogDownloadUserForm')->name('catalog-download-user-form');
});

// DEBUG: Test CSS URL generation
Route::get('/test-css-urls', function() {
    return view('test-css');
});

Route::controller(EventController::class)->group(function () {
    Route::get('fair-events', 'index')->name('page.fair-events');
    Route::get('event-detail/{any}', 'show')->name('event.detail');
});


Route::controller(ProjectController::class)->prefix('projects')->group(function () {
    Route::get('/', 'index')->name('page.projects');
    Route::get('category/{any}', 'projectsByFilter')->name('projects-with-filter');
    Route::get('{any}', 'show')->name('project-detail'); // Placed last to avoid intercepting `category/{any}`
});

Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'subTags')->name('sub-tags');
    Route::get('products/category/{any}', 'subTagsByFilter')->name('sub-tags-with-filter');
    Route::get('products/{any}', 'products')->name('products');
    
    Route::get('product/{any}', 'product')->name('product');
    Route::get('product/{any}/pdf', 'pdf')->name('product.pdf');
    Route::get('product/{any}/download-pdf', 'downloadPdf')->name('product.download-pdf');
    
    Route::get('search', 'search')->name('search');
    Route::get('category/{any}', 'search')->name('category');
});


// WebsiteHomeController routes moved to top block

Route::get('career', function () {
    $openings = JobOpening::all();
    return view('pages.career', compact('openings'));
})->name('page.career');

Route::get('contact', function () {
    return view('pages.contact');
})->name('page.contact');

Route::controller(DataCollectionController::class)->group(function () {
    Route::post('contact', 'contact')->name('mail.contact.send');
    Route::post('product-inquiry', 'productInquiry')->name('mail.product.send');
});

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

// catalog-download-user-form moved to WebsiteHomeController block

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

        Route::group(['prefix' => 'decorative-spec-attributes'], function () {
            Route::get('/', [\App\Http\Controllers\Admin\DecorativeSpecAttributeController::class, 'index'])->name('decorative_spec_attributes_admin');
            Route::get('/create', [\App\Http\Controllers\Admin\DecorativeSpecAttributeController::class, 'create'])->name('decorative_spec_attributes_admin.add');
            Route::post('/', [\App\Http\Controllers\Admin\DecorativeSpecAttributeController::class, 'store'])->name('decorative_spec_attributes_admin.store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\DecorativeSpecAttributeController::class, 'edit'])->name('decorative_spec_attributes_admin.edit');
            Route::post('/{id}', [\App\Http\Controllers\Admin\DecorativeSpecAttributeController::class, 'update'])->name('decorative_spec_attributes_admin.update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\DecorativeSpecAttributeController::class, 'destroy'])->name('decorative_spec_attributes_admin.delete');
        });

        /********************CONTACT FORMS********************/
        Route::controller(ContactFormAdminController::class)->group(function () {
            Route::get('/contact-forms', 'index')->name('contact_form_admin');
            Route::post('/contact-forms/upload-banner', 'uploadBanner')->name('contact_form_admin.upload');
            Route::get('/contact-forms/list', 'list')->name('contact_form_admin.list');
            Route::get('/subscriptions', 'subscriptions')->name('subscriptions_admin');
            Route::get('/subscriptions/list', 'subscriptions_list')->name('subscriptions_admin.list');
        });

        /********************CATEGORIES********************/
        Route::controller(CategoryAdminController::class)->prefix('categories')->group(function () {
            Route::get('/', 'index')->name('category_admin');
            Route::get('/list', 'list')->name('category_admin.list');
            Route::get('/add', 'add')->name('category_admin.add');
            Route::get('/edit/{any}', 'edit')->name('category_admin.edit');
            Route::post('/insert', 'insert')->name('category_admin.insert');
            Route::post('/update/{any}', 'update')->name('category_admin.update');
            Route::get('/information/{any}', 'information')->name('category_admin.information');
            Route::post('/toggleFeatured', 'toggleFeatured')->name('category_admin.toggleFeatured');
        });

        /********************FAMILIES********************/
        Route::controller(FamilyAdminController::class)->prefix('families')->group(function () {
            Route::get('/', 'index')->name('family_admin');
            Route::get('/add', 'add')->name('family_admin.add');
            Route::get('/add_product', 'add_product')->name('add_product.add');
            Route::get('/edit/{any}', 'edit')->name('family_admin.edit');
        });

        /********************TAGS********************/
        Route::controller(TagAdminController::class)->prefix('tags')->group(function () {
            Route::get('/', 'index')->name('tag_admin');
            Route::get('/add', 'add')->name('tag_admin.add');
            Route::post('/insert', 'insert')->name('tag_admin.insert');
            Route::get('/edit/{any}', 'edit')->name('tag_admin.edit');
            Route::post('/update/{any}', 'update')->name('tag_admin.update');
        });

        /********************SUB TAGS********************/
        Route::controller(SubTagAdminController::class)->prefix('sub_tags')->group(function () {
            Route::get('/', 'index')->name('sub_tag_admin');
            Route::get('/add', 'add')->name('sub_tag_admin.add');
            Route::post('/insert', 'insert')->name('sub_tag_admin.insert');
            Route::get('/edit/{any}', 'edit')->name('sub_tag_admin.edit');
            Route::post('/update/{any}', 'update')->name('sub_tag_admin.update');
            Route::post('/upload-banner', 'uploadBanner')->name('sub_tag_admin.upload');
            Route::get('/exports', 'exports'); // Assuming exports can use prefix
        });

        /********************PRODUCTS********************/
        Route::controller(ProductAdminController::class)->group(function () {
            Route::get('/product', 'index')->name('product_admin');
            Route::get('/product/list', 'list')->name('product_admin.list');
            Route::get('/product/add', 'add')->name('product_admin.add');
            Route::post('/product/insert', 'insert')->name('product_admin.insert');
            Route::get('/product/edit/{any}', 'edit')->name('product_admin.edit');
            Route::post('/product/update/{any}', 'update')->name('product_admin.update');
            Route::get('/product/information/{any}', 'information')->name('product_admin.information');
            Route::post('/product-variant/insert', 'product_variant_insert')->name('product_admin.variant_insert');
            Route::post('/delete-variant', 'delete_variant')->name('delete_variant');
            Route::post('/edit-variant/{any}', 'edit_variant')->name('edit_variant');
            Route::post('/update-variant/{any}', 'update_variant')->name('update_variant');
            Route::get('/products_exports', 'exports');
            Route::post('/product/duplicate/{id}', 'duplicate')->name('product_admin.duplicate');
        });


        /********************CATALOG DOWNLOADS********************/
        Route::controller(CatalogDownloadAdminController::class)->prefix('catalog')->group(function () {
            Route::get('/', 'index')->name('catalog_admin');
            Route::get('/list', 'list')->name('catalog_admin.list');
            Route::delete('/delete/{id}', 'destroy')->name('catalog_admin.destroy');
            Route::post('/upload-catalog', 'uploadCatalog')->name('catalog_admin.upload'); // Note: previously /upload-catalog, keeping as /catalog/upload-catalog would change URL, wait. Original was Route::post('/upload-catalog', ...) which is not prefixed by /catalog. Let me not use prefix here to be safe.
        });

        /********************COMPOSITIONS********************/
        Route::controller(CompositionAdminController::class)->prefix('compositions')->group(function () {
            Route::get('/', 'index')->name('composition_admin');
            Route::get('/list', 'list')->name('composition_admin.list');
            Route::get('/add', 'add')->name('composition_admin.add');
            Route::post('/insert', 'insert')->name('composition_admin.insert');
            Route::get('/edit/{any}', 'edit')->name('composition_admin.edit');
            Route::post('/update/{any}', 'update')->name('composition_admin.update');
            Route::post('/delete', 'delete')->name('composition_admin.delete');
        });

        /********************COMPOSITION CATEGORIES********************/
        Route::controller(\App\Http\Controllers\Admin\CompositionCategoryController::class)->prefix('composition-categories')->group(function () {
            Route::get('/', 'index')->name('composition_categories_admin');
            Route::get('/add', 'add')->name('composition_categories_admin.add');
            Route::post('/insert', 'insert')->name('composition_categories_admin.insert');
            Route::get('/edit/{any}', 'edit')->name('composition_categories_admin.edit');
            Route::post('/update/{any}', 'update')->name('composition_categories_admin.update');
            Route::post('/delete', 'delete')->name('composition_categories_admin.delete');
        });
        /********************PROJECT********************/
        Route::controller(ProjectAdminController::class)->group(function () {
            Route::get('/project', 'index')->name('project_admin');
            Route::get('/project/list', 'list')->name('project_admin.list');
            Route::get('/project/add', 'add')->name('project_admin.add');
            Route::post('/project/insert', 'insert')->name('project_admin.insert');
            Route::get('/project/edit/{any}', 'edit')->name('project_admin.edit');
            Route::post('/project/update/{any}', 'update')->name('project_admin.update');
            Route::post('/project/toggle-featured', 'toggleFeatured')->name('project_admin.toggle_featured');
            Route::get('/project/information/{any}', 'information')->name('project_admin.information');
            Route::get('/project-images/{any}', 'project_images')->name('project_images');
            Route::get('/projects_exports', 'exports');
        });

        /********************UPLOAD CSV********************/
        Route::get('/upload-csv', [UploadCSVAdminController::class, 'index'])->name('upload_csv_admin');

        /********************ATTRIBUTE MASTER********************/
        Route::controller(AttributeAdminController::class)->prefix('attributes')->group(function () {
            Route::get('/', 'index')->name('attribute_admin');
            Route::get('/list', 'list')->name('attribute_admin.list');
            Route::get('/add', 'add')->name('attribute_admin.add');
            Route::post('/insert', 'insert')->name('attribute_admin.insert');
            Route::get('/edit/{any}', 'edit')->name('attribute_admin.edit');
            Route::post('/update/{any}', 'update')->name('attribute_admin.update');
        });

        /********************GROUP MASTER********************/
        Route::controller(GroupAdminController::class)->prefix('groups')->group(function () {
            Route::get('/', 'index')->name('group_admin');
            Route::get('/list', 'list')->name('group_admin.list');
            Route::get('/add', 'add')->name('group_admin.add');
            Route::post('/insert', 'insert')->name('group_admin.insert');
            Route::get('/edit/{any}', 'edit')->name('group_admin.edit');
            Route::post('/update/{any}', 'update')->name('group_admin.update');
        });

        /********************VARIANT MASTER********************/
        Route::controller(VariantAdminController::class)->group(function () {
            Route::get('/variants', 'index')->name('variant_admin');
            Route::get('/variants/list', 'list')->name('variant_admin.list');
            Route::get('/variants/add', 'add')->name('variant_admin.add');
            Route::post('/variants/insert', 'insert')->name('variant_admin.insert');
            Route::get('/variants/edit/{any}', 'edit')->name('variant_admin.edit');
            Route::post('/variants/update/{any}', 'update')->name('variant_admin.update');

            Route::get('/variant-attributes', 'variant_attribute_index')->name('variant_attribute_admin');
            Route::get('/variant-attributes/list', 'variant_attr_list')->name('variant_attribute_admin.list');
            Route::get('/variant-attributes/add', 'variant_attr_add')->name('variant_attribute_admin.add');
            Route::post('/variant-attributes/insert', 'variant_attr_insert')->name('variant_attribute_admin.insert');
            Route::get('/variant-attributes/edit/{any}', 'variant_attr_edit')->name('variant_attribute_admin.edit');
            Route::post('/variant-attributes/update/{any}', 'variant_attr_update')->name('variant_attribute_admin.update');
        });

        Route::get('/change-password', [ProfileAdminController::class, 'index'])->name('change_pass');
        Route::post('/change-password/update', [ProfileAdminController::class, 'update'])->name('change_pass.update');

        /********************ICONS********************/
        Route::controller(IconAdminController::class)->prefix('icons')->group(function () {
            Route::get('/', 'index')->name('icon_admin');
            Route::get('/list', 'list')->name('icon_admin.list');
            Route::get('/add', 'add')->name('icon_admin.add');
            Route::post('/insert', 'insert')->name('icon_admin.insert');
            Route::get('/edit/{any}', 'edit')->name('icon_admin.edit');
            Route::post('/update/{any}', 'update')->name('icon_admin.update');
            Route::get('/information/{any}', 'information')->name('icon_admin.information');
        });

        /********************EVENTS********************/
        Route::controller(EventAdminController::class)->prefix('events')->group(function () {
            Route::get('/', 'index')->name('event_admin');
            Route::get('/list', 'list')->name('event_admin.list');
            Route::get('/add', 'add')->name('event_admin.add');
            Route::post('/insert', 'insert')->name('event_admin.insert');
            Route::get('/edit/{any}', 'edit')->name('event_admin.edit');
            Route::post('/update/{any}', 'update')->name('event_admin.update');
            Route::get('/information/{any}', 'information')->name('event_admin.information');
        });

        /********************JOBS********************/
        Route::controller(JobAdminController::class)->prefix('jobs')->group(function () {
            Route::get('/', 'index')->name('job_admin');
            Route::get('/list', 'list')->name('job_admin.list');
            Route::get('/add', 'add')->name('job_admin.add');
            Route::get('/edit/{any}', 'edit')->name('job_admin.edit');
            Route::post('/insert', 'insert')->name('job_admin.insert');
            Route::post('/update/{any}', 'update')->name('job_admin.update');
            Route::get('/information/{any}', 'information')->name('job_admin.information');
            Route::post('/upload-banner', 'uploadBanner')->name('job_admin.upload');
        });

        /********************CLIENTS********************/
        Route::controller(ClientAdminController::class)->prefix('clients')->group(function () {
            Route::get('/', 'index')->name('client_admin');
            Route::get('/list', 'list')->name('client_admin.list');
            Route::get('/add', 'add')->name('client_admin.add');
            Route::get('/edit/{any}', 'edit')->name('client_admin.edit');
            Route::post('/insert', 'insert')->name('client_admin.insert');
            Route::post('/update/{any}', 'update')->name('client_admin.update');
            Route::get('/information/{any}', 'information')->name('client_admin.information');
            Route::post('/upload-banner', 'uploadBanner')->name('client_admin.upload');
        });

        /********************HOME SLIDERS********************/
        Route::controller(AdminHomeSliderController::class)->prefix('homeslider')->group(function () {
            Route::get('/', 'index')->name('homeslider_admin');
            Route::get('/add', 'add')->name('homeslider_admin.add');
            Route::get('/edit/{any}', 'edit')->name('homeslider_admin.edit');
            Route::post('/insert', 'insert')->name('homeslider_admin.insert');
            Route::post('/update/{any}', 'update')->name('homeslider_admin.update');
        });

        /********************LIGHT WORLDS********************/
        Route::controller(App\Http\Controllers\Admin\LightWorldController::class)->prefix('light-worlds')->group(function () {
            Route::get('/', 'index')->name('light_worlds_admin');
            Route::get('/add', 'add')->name('light_worlds_admin.add');
            Route::get('/edit/{id}', 'edit')->name('light_worlds_admin.edit');
            Route::post('/insert', 'insert')->name('light_worlds_admin.insert');
            Route::post('/update/{id}', 'update')->name('light_worlds_admin.update');
            Route::delete('/delete/{id}', 'delete')->name('light_worlds_admin.delete');
        });

        /********************MANUFACTURING SECTION / HOMEPAGE SETTINGS********************/
        Route::controller(App\Http\Controllers\Admin\ManufacturingSectionController::class)->group(function () {
            Route::get('/manufacturing-section', 'index')->name('admin.manufacturing.index');
            Route::get('/homepage-settings/{id?}', 'edit')->name('admin.manufacturing.edit');
            Route::put('/homepage-settings/update', 'update')->name('admin.manufacturing.update');
        });

        /********************HOME CATALOGUE SECTION********************/
        Route::get('/home-catalogue-section', [App\Http\Controllers\Admin\HomeCatalogueSectionController::class, 'index'])->name('admin.home_catalogue.index');
        Route::get('/home-catalogue-settings/{id?}', [App\Http\Controllers\Admin\HomeCatalogueSectionController::class, 'edit'])->name('admin.home_catalogue.edit');
        Route::put('/home-catalogue-settings/update', [App\Http\Controllers\Admin\HomeCatalogueSectionController::class, 'update'])->name('admin.home_catalogue.update');

        /********************NEWS SECTION********************/
        Route::controller(App\Http\Controllers\Admin\NewsSectionController::class)->group(function () {
            Route::get('/news-section-settings/{id?}', 'edit')->name('admin.news-section.edit');
            Route::put('/news-section-settings/update', 'update')->name('admin.news-section.update');
        });

        /********************NEWS ITEMS********************/
        Route::controller(App\Http\Controllers\Admin\NewsItemController::class)->prefix('news-items')->group(function () {
            Route::get('/', 'index')->name('admin.news-items.index');
            Route::get('/add', 'add')->name('admin.news-items.add');
            Route::post('/store', 'store')->name('admin.news-items.store');
            Route::get('/edit/{id}', 'edit')->name('admin.news-items.edit');
            Route::put('/update/{id}', 'update')->name('admin.news-items.update');
            Route::delete('/delete/{id}', 'delete')->name('admin.news-items.delete');
        });
        /********************BLOG CATEGORIES********************/
        Route::controller(App\Http\Controllers\Admin\BlogCategoryController::class)->prefix('blog-categories')->group(function () {
            Route::get('/', 'index')->name('admin.blog-categories.index');
            Route::get('/add', 'add')->name('admin.blog-categories.add');
            Route::post('/store', 'store')->name('admin.blog-categories.store');
            Route::get('/edit/{id}', 'edit')->name('admin.blog-categories.edit');
            Route::put('/update/{id}', 'update')->name('admin.blog-categories.update');
            Route::delete('/delete/{id}', 'destroy')->name('admin.blog-categories.destroy');
        });

        /********************BLOGS********************/
        Route::controller(App\Http\Controllers\Admin\BlogAdminController::class)->prefix('blogs')->group(function () {
            Route::get('/', 'index')->name('admin.blogs.index');
            Route::get('/add', 'add')->name('admin.blogs.add');
            Route::post('/store', 'store')->name('admin.blogs.store');
            Route::get('/edit/{id}', 'edit')->name('admin.blogs.edit');
            Route::put('/update/{id}', 'update')->name('admin.blogs.update');
            Route::delete('/delete/{id}', 'destroy')->name('admin.blogs.destroy');
            Route::post('/upload-image', 'uploadImage')->name('admin.blogs.upload_image');
        });

        /********************WATCH & SHOP (REELS)********************/
        Route::controller(App\Http\Controllers\Admin\WatchAndShopController::class)->prefix('watch-and-shops')->group(function () {
            Route::get('/', 'index')->name('admin.watch_and_shops.index');
            Route::get('/add', 'add')->name('admin.watch_and_shops.add');
            Route::post('/store', 'store')->name('admin.watch_and_shops.store');
            Route::get('/edit/{id}', 'edit')->name('admin.watch_and_shops.edit');
            Route::put('/update/{id}', 'update')->name('admin.watch_and_shops.update');
            Route::delete('/delete/{id}', 'delete')->name('admin.watch_and_shops.delete');
        });

        /********************CATALOGUE CATEGORIES********************/
        Route::controller(App\Http\Controllers\Admin\CatalogueCategoryController::class)->prefix('catalogue-categories')->group(function () {
            Route::get('/', 'index')->name('admin.catalogue-categories.index');
            Route::get('/add', 'add')->name('admin.catalogue-categories.add');
            Route::post('/store', 'store')->name('admin.catalogue-categories.store');
            Route::get('/edit/{id}', 'edit')->name('admin.catalogue-categories.edit');
            Route::put('/update/{id}', 'update')->name('admin.catalogue-categories.update');
            Route::delete('/delete/{id}', 'destroy')->name('admin.catalogue-categories.destroy');
        });
        /********************CATALOGUES********************/
        Route::controller(App\Http\Controllers\Admin\CatalogueController::class)->prefix('catalogues')->group(function () {
            Route::get('/', 'index')->name('admin.catalogues.index');
            Route::get('/add', 'add')->name('admin.catalogues.add');
            Route::post('/store', 'store')->name('admin.catalogues.store');
            Route::get('/edit/{id}', 'edit')->name('admin.catalogues.edit');
            Route::put('/update/{id}', 'update')->name('admin.catalogues.update');
            Route::delete('/delete/{id}', 'destroy')->name('admin.catalogues.destroy');
        });



        /********************COLOR MASTERS********************/
        Route::controller(App\Http\Controllers\Admin\ColorMasterController::class)->prefix('color-masters')->group(function () {
            Route::get('/', 'index')->name('color_master_admin');
            Route::get('/add', 'add')->name('color_master_admin.add');
            Route::post('/insert', 'insert')->name('color_master_admin.insert');
            Route::get('/edit/{any}', 'edit')->name('color_master_admin.edit');
            Route::post('/update/{any}', 'update')->name('color_master_admin.update');
            Route::delete('/delete/{any}', 'delete')->name('color_master_admin.delete');
            Route::get('/information/{any}', 'information')->name('color_master_admin.information');
        });

        /********************COLLECTIONS********************/
        Route::controller(CollectionController::class)->prefix('collections')->group(function () {
            Route::get('/', 'index')->name('admin.collections.index');
            Route::get('/create', 'create')->name('admin.collections.create');
            Route::post('/', 'store')->name('admin.collections.store');
            Route::get('/{collection}/edit', 'edit')->name('admin.collections.edit');
            Route::put('/{collection}', 'update')->name('admin.collections.update');
            Route::delete('/{collection}', 'destroy')->name('admin.collections.destroy');
            Route::patch('/{collection}/toggle-active', 'toggleActive')->name('admin.collections.toggle-active');
            Route::post('/{collection}/hero-section', 'storeHeroSection')->name('admin.collections.store-hero');
            Route::post('/{collection}/parameters-section', 'storeParametersSection')->name('admin.collections.store-parameters');
            
            // Parameter Items CRUD
            Route::get('/{collection}/parameter-items/add', 'addParameterItem')->name('admin.collections.parameter-items.add');
            Route::post('/{collection}/parameter-items', 'storeParameterItem')->name('admin.collections.parameter-items.store');
            Route::get('/{collection}/parameter-items/{item}/edit', 'editParameterItem')->name('admin.collections.parameter-items.edit');
            Route::put('/{collection}/parameter-items/{item}', 'updateParameterItem')->name('admin.collections.parameter-items.update');
            Route::delete('/{collection}/parameter-items/{item}', 'deleteParameterItem')->name('admin.collections.parameter-items.delete');

            // Compositions Section
            Route::post('/{collection}/compositions-section', 'storeCompositionsSection')->name('admin.collections.store-compositions');
            
            // Composition Items CRUD
            Route::get('/{collection}/composition-items/add', 'addCompositionItem')->name('admin.collections.composition-items.add');
            Route::post('/{collection}/composition-items', 'storeCompositionItem')->name('admin.collections.composition-items.store');
            Route::get('/{collection}/composition-items/{item}/edit', 'editCompositionItem')->name('admin.collections.composition-items.edit');
            Route::put('/{collection}/composition-items/{item}', 'updateCompositionItem')->name('admin.collections.composition-items.update');
            Route::delete('/{collection}/composition-items/{item}', 'deleteCompositionItem')->name('admin.collections.composition-items.delete');

            // Tones Section
            Route::post('/{collection}/tones-section', 'storeTonesSection')->name('admin.collections.store-tones');
            
            // Tone Families CRUD
            Route::get('/{collection}/tone-families/add', 'addToneFamily')->name('admin.collections.tone-families.add');
            Route::post('/{collection}/tone-families', 'storeToneFamily')->name('admin.collections.tone-families.store');
            Route::get('/{collection}/tone-families/{family}/edit', 'editToneFamily')->name('admin.collections.tone-families.edit');
            Route::put('/{collection}/tone-families/{family}', 'updateToneFamily')->name('admin.collections.tone-families.update');
            Route::delete('/{collection}/tone-families/{family}', 'deleteToneFamily')->name('admin.collections.tone-families.delete');

            // Places Section
            Route::post('/{collection}/places-section', 'storePlacesSection')->name('admin.collections.store-places');
            
            // Place Items CRUD
            Route::get('/{collection}/place-items/add', 'addPlaceItem')->name('admin.collections.place-items.add');
            Route::post('/{collection}/place-items', 'storePlaceItem')->name('admin.collections.place-items.store');
            Route::get('/{collection}/place-items/{item}/edit', 'editPlaceItem')->name('admin.collections.place-items.edit');
            Route::put('/{collection}/place-items/{item}', 'updatePlaceItem')->name('admin.collections.place-items.update');
            Route::delete('/{collection}/place-items/{item}', 'deletePlaceItem')->name('admin.collections.place-items.delete');
            // Spread & Drop Section
            Route::post('/{collection}/spread-drop-section', 'storeSpreadDropSection')->name('admin.collections.store-spread-drop');
        });

        // DEBUG ROUTES - REMOVE IN PRODUCTION
        Route::controller(CollectionController::class)->prefix('debug')->group(function () {
            Route::get('/tone-families', 'debugToneFamilies')->name('admin.debug.tone-families');
            Route::get('/tone-families/{family}/test-edit', 'debugEditToneFamily')->name('admin.debug.tone-families.edit');
            Route::post('/tone-families/{family}/test-update', 'debugUpdateToneFamily')->name('admin.debug.tone-families.update');
        });

    });
});
