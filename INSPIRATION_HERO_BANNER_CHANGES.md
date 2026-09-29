# Inspiration Banner Dynamic Feature — Backend & Frontend Changes Summary

This document provides a complete list of all backend files created and modified to make the **Inspiration Page Hero Banner** dynamic and manageable from the Admin Panel.

---

## 📁 1. Backend Files Created & Modified

### 1.1 Database Migration (New File)
- **File**: [`backend/database/migrations/2026_09_27_000001_create_inspiration_hero_section_table.php`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/backend/database/migrations/2026_09_27_000001_create_inspiration_hero_section_table.php)
- **Description**: Creates the `inspiration_hero_section` table with the following schema:
  - `id` (Primary Key)
  - `title` (Heading text, default: `"Ideas, stories & inspiration"`)
  - `title_highlight` (Italic accent/subheading text, default: `"Insights"`)
  - `breadcrumb_parent_text` (Parent breadcrumb text, default: `"Home"`)
  - `breadcrumb_parent_link` (Parent breadcrumb URL, default: `"/"`)
  - `breadcrumb_current_text` (Current page breadcrumb text, default: `"Inspiration"`)
  - `background_image` (Image file storage path, nullable)
  - `is_active` (Boolean status toggle for active/inactive state, default: `true`)
  - `created_by`, `updated_by`, `timestamps()`

---

### 1.2 Eloquent Model (New File)
- **File**: [`backend/app/Models/InspirationHeroSection.php`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/backend/app/Models/InspirationHeroSection.php)
- **Description**: Eloquent model mapped to the `inspiration_hero_section` table with `is_active` boolean casting and `$guarded = ['id']`.

---

### 1.3 Admin Controller (New File)
- **File**: [`backend/app/Http/Controllers/Admin/InspirationHeroSectionController.php`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/backend/app/Http/Controllers/Admin/InspirationHeroSectionController.php)
- **Methods**:
  - `index()`: Redirects directly to the edit page.
  - `edit($id = 1)`: Fetches or initializes default hero banner settings and renders the admin view.
  - `update(Request $request)`: Validates input, handles upload & removal of previous background images from storage (`uploads/inspiration`), updates active/deactive status, and saves settings.

---

### 1.4 Admin Blade View (New File)
- **File**: [`backend/resources/views/admin/inspiration_hero/edit.blade.php`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/backend/resources/views/admin/inspiration_hero/edit.blade.php)
- **Description**: Admin management form featuring:
  - Heading input field
  - Subheading / Italic Highlight input field
  - Breadcrumb customization fields
  - Image upload with custom file browser and current image preview
  - Active / Inactive toggle switch
  - Form validation error alerts & success messaging

---

### 1.5 API Controller (New File)
- **File**: [`backend/app/Http/Controllers/Api/InspirationHeroSectionApiController.php`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/backend/app/Http/Controllers/Api/InspirationHeroSectionApiController.php)
- **Description**: Exposes REST API endpoint `GET /api/inspiration-hero-section` returning JSON data with formatted public storage URLs for `background_image` and boolean `is_active` state.

---

### 1.6 Web Routes (Modified File)
- **File**: [`backend/routes/web.php`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/backend/routes/web.php)
- **Changes**: Added admin route group:
  ```php
  /********************INSPIRATION BANNER / HERO SECTION********************/
  Route::controller(App\Http\Controllers\Admin\InspirationHeroSectionController::class)->prefix('inspiration-hero')->group(function () {
      Route::get('/', 'index')->name('admin.inspiration_hero.index');
      Route::get('/edit/{id?}', 'edit')->name('admin.inspiration_hero.edit');
      Route::put('/update', 'update')->name('admin.inspiration_hero.update');
  });
  ```

---

### 1.7 API Routes (Modified File)
- **File**: [`backend/routes/api.php`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/backend/routes/api.php)
- **Changes**: Imported `InspirationHeroSectionApiController` and registered public endpoint:
  ```php
  // Inspiration Hero Banner
  Route::get('/inspiration-hero-section', [InspirationHeroSectionApiController::class, 'index']);
  ```

---

### 1.8 Admin Navigation Sidebar (Modified File)
- **File**: [`backend/resources/views/admin/page.blade.php`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/backend/resources/views/admin/page.blade.php)
- **Changes**: Added **Hero Banner** menu item under **Pages &rarr; Inspiration** sidebar menu and updated menu active state conditions.

---

## 🌐 2. Connected Frontend Files

For end-to-end integration, the following frontend files consume the dynamic API:

1. **API Client Helper**: [`frontend/lib/api/inspiration.ts`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/frontend/lib/api/inspiration.ts) *(New File)* — Fetches `/api/inspiration-hero-section` with fallback data.
2. **Hero Component**: [`frontend/components/inspiration/InspirationHero.tsx`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/frontend/components/inspiration/InspirationHero.tsx) *(Modified)* — Renders dynamic heading, italic highlight, breadcrumbs, dynamic background image, and respects `is_active` toggle.
3. **Inspiration Page**: [`frontend/app/inspiration/page.tsx`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/frontend/app/inspiration/page.tsx) *(Modified)* — Server-side data fetching of the banner settings alongside compositions.
4. **CSS Styles**: [`frontend/styles/inspiration.css`](file:///d:/all_project/abby-lighting/abby-lighting-latest/AbbyLightingWebsite/frontend/styles/inspiration.css) *(Modified)* — Added explicit cover, center position, and repeat rules for dynamic background images.



frontend/styles/product-details.css

