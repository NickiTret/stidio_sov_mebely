# KBR Mebel — сводка по проекту

Сайт студии мебели на заказ в Нальчике: `kbr-mebel.ru` («Студия Современной Мебели» / KBR Mebel).
Локальный проект: `D:\redPromo\stidio_sov_mebely`, локальный домен `http://studio-mebel.loc`.
Дата составления: 2026-09-24. См. также `PROJECT_AUDIT.md` — журнал уже выполненных работ.

---

## 1. Стек и окружение

| Слой | Технология |
|---|---|
| Backend | Laravel 10, PHP ^8.1, MySQL |
| Auth | Собственная регистрация/логин (UserController) + `laravel/sanctum` |
| Админка | Кастомная, шаблоны Bootstrap-подобной сеткой (`container-fluid`, `row`, `col-*`), статика `public/adminlte` |
| Публичный фронт | Blade + собственная SCSS-сетка (`.container` + миксин `grid()`) |
| Сборка фронта | Gulp 4 + Webpack (`gulpfile.js`, author MaxGraph). Команды: `npm run dev`, `npm run build`, **`npm run backend`** — основная для пересборки ассетов |
| JS-библиотеки | jQuery, Swiper, Fancybox, GSAP, AOS, inputmask, just-validate, smooth-scroll, simplebar и др. |
| Уведомления о заявках | Telegram Bot API (`TELEGRAM_BOT_TOKEN`/`TELEGRAM_CHAT_ID` в `.env`) |
| Тесты | PHPUnit 10, но только стандартные ExampleTest (реальных тестов нет) |

**Важные правила (из PROJECT_AUDIT):**
- Собранные файлы в `public/css` и `public/js` вручную НЕ редактировать — исходники в `src/scss` и `src/js`, после изменений `npm run backend`.
- Сохранять текущий дизайн: новые блоки собирать из существующего визуального языка.
- Сетки админки (Bootstrap) и публичного сайта (SCSS grid) не смешивать на одной публичной странице.

---

## 2. Карта маршрутов (`routes/web.php`)

### Публичные
| Маршрут | Контроллер | Назначение |
|---|---|---|
| `GET /` | `MainController@index` | Главная (новая структура, компонент `new-home`) |
| `GET /gallery` | `MainController@gallery` | Галерея работ |
| `GET /gallery/{slug}` | `MainController@galleryCategory` | Категория галереи (8 slug'ов) |
| `GET /contacts` | `MainController@contacts` | Контакты |
| `GET /sitemap.xml` | `MainController@sitemap` | XML-карта сайта |
| `GET /{landingSlug}` | `MainController@servicePage` | SEO-лендинги услуг (8 штук, см. §5) |
| `POST /` | `Message\StoreController` | Прием заявок с форм |

### Авторизация
`/register`, `/login` (guest), `/logout` (auth) — `UserController`.

### Админка (`/admin`, middleware `admin` = `Auth::user()->is_admin`, иначе 404)
- `/admin` — дашборд (`IndexController`)
- `/admin/home-preview` — preview новой главной + `/admin/home-preview/edit` — редактор контента (PATCH)
- CRUD: `setings` (SEO-настройки страниц), `slider` (работы/фото), `group` (категории галереи), `post` (статьи)
- `mainset` — одиночная страница настроек (контакты, карта, футер)
- `/message` (вне префикса admin, но под middleware admin) — inbox заявок: поиск, сортировка, пагинация, удаление одной/всех

---

## 3. База данных (миграции)

| Таблица | Поля | Назначение |
|---|---|---|
| `users` | name, email, password, `is_admin` (tinyint) | Пользователи, флаг админа |
| `groups` | title | Категории галереи |
| `sliders` | title, description, image_src, `group_id` (FK→groups) | Фото работ, привязка к категории |
| `posts` | title, description, image_src | Статьи |
| `setings` | page, title, descriptions, keywords, favicon | SEO-настройки по страницам |
| `main_sets` | tel, email, telegram, watsap, map, footer | Глобальные контакты сайта |
| `messages` | name, tel, mail, content | Заявки с форм |

Опечатки в именах, к которым нужно привыкнуть: `Seting` (вместо Setting), `watsap` (вместо whatsapp), миграция `create_siders_table` (вместо sliders).

---

## 4. Модели и ключевая логика

- **`app/Models/Group.php`** — центр SEO-логики категорий. Метод `categoryDefinitions()` содержит 8 категорий со slug, алиасами, seo_title/description/keywords, benefits и текстами. `definitionByTitle()` мапит title из БД на определение через нормализацию алиасов. Для категории вне списка — дефолтный fallback.
- **`app/Support/SeoLandingPages.php`** — 8 SEO-лендингов услуг (кухни, шкафы-купе, гардеробные, детские, спальни, гостиные, кабинет, торговое оборудование) с полными текстами: tasks, benefits, process, faq. Маршруты вида `/kuhni-na-zakaz-nalchik` и т.п.
- **`app/Support/HomePreviewContent.php`** — контент главной/preview в JSON: `storage/app/home-preview-content.json`. Блоки: hero, faq, cta, footer, metrics, categories_section, approach, showcase. Редактируется из админки; в шаблоне доступен как `$previewContent`.
- **`MainController`** — кеширует mainset/seting/groups/sliders на 60 сек; `getGalleryGroups()` фильтрует группы без слайдов и сортирует по порядку `categoryDefinitions`.

---

## 5. Slug'и категорий галереи

`kuhni`, `shkafy-kupe`, `detskie`, `spalni`, `gostinye`, `garderobnye`, `kabinet`, `torgovoe-oborudovanie`

SEO-лендинги: `/kuhni-na-zakaz-nalchik`, `/shkafy-kupe-na-zakaz-nalchik`, `/garderobnye-na-zakaz-nalchik`, `/detskaya-mebel-na-zakaz-nalchik`, `/mebel-dlya-spalni-na-zakaz-nalchik`, `/mebel-dlya-gostinoj-na-zakaz-nalchik`, `/mebel-dlya-kabineta-na-zakaz-nalchik`, `/torgovoe-oborudovanie-na-zakaz-nalchik`

---

## 6. Фронтенд-структура

- **Шаблоны публики**: `resources/views/` — `welcome.blade.php` (главная), `gallery.blade.php`, `contacts.blade.php`, `service-page.blade.php`, `sitemap.blade.php`.
- **Компоненты**: `resources/views/ssm/components/` — `new-home` (каркас новой главной, ~295 строк), `hero`, `form`, `form-status`, `factoid`, `posts`, `realish`, `individual`, `design`, `category-benefits` (убран из галереи), `site-cta-new`, `site-footer-new`.
- **Окружение**: `resources/views/ssm/environment/` — `head`, `header`, `footer`.
- **SCSS**: `src/scss/main.scss`, сетка в `_settings.scss` + миксин `mixins/_breakpoint.scss`, компоненты в `src/scss/components/` (включая `_home-preview.scss`, `_service-page.scss`).
- **JS**: `src/js/main.js`, компоненты: slider, modal, validate, fancybox, grid, ymap (Яндекс.Карта), scroll, factoids и др.
- **Grid helper**: `src/js/components/grid.js` + конфиг `resources/grid/grid.json`.
- Лишний файл в корне: `main.css` (20 КБ) — не часть пайплайна, источник неясен.

---

## 7. Формы и безопасность

**Антиспам — 3 слоя** (хорошо сделано):
1. Honeypot-поле `website`
2. Минимальное время заполнения `form_started_at`
3. Rate limit по IP: 5 заявок / 10 минут (`Message\StoreController`)

Заявки пишутся в `messages` и дублируются в Telegram (если заданы токены). `redirect_to` валидируется (только свой домен или относительный путь).

**Проблемы безопасности:**
- `AdminMiddleware` при отказе отдает `abort(404)` вместо 403 — сознательный выбор «скрыть» админку, но затрудняет отладку.
- Регистрация `/register` открыта — любой может создать аккаунт (is_admin=0, так что в админку не попадет, но форму стоит закрыть).
- В корне `public/` лежит исторический `tel.php` (старый обработчик форм) — потенциальный мусор/риск, новая логика на него не опирается. Удалить на проде.
- `public/index.html`, `post.html`, `posts.html` — статические остатки верстки рядом с `index.php`, стоит почистить.
- Статический `public/sitemap.xml` может конфликтовать/устаревать рядом с динамическим маршрутом `/sitemap.xml`.

---

## 8. Сильные стороны

1. **Продуманный SEO-фундамент**: 8 категорий галереи + 8 посадочных страниц услуг с уникальными title/description/keywords, FAQ, schema.org (`Service` + `FurnitureStore`), динамический sitemap, перелинковка.
2. **Локальная SEO-нацеленность**: все тексты заточены под «на заказ в Нальчике» — правильно для регионального бизнеса.
3. **Управляемый контент главной**: JSON + редактор в админке (`home-preview`), без хардкода в Blade.
4. **Качественный антиспам** и Telegram-уведомления о заявках.
5. **Кеширование** основных выборок (60 сек) на публичных страницах.
6. **Админка доведена до рабочего состояния**: унифицированные списки с поиском/сортировкой/пагинацией, исправлены CRUD-баги (редиректы, потеря изображений, неверные route в формах).
7. **Inbox заявок** — не просто таблица, а рабочий интерфейс.
8. **Дисциплина фронта**: исходники в `src/`, сборка Gulp+Webpack, stylelint, editorconfig, типограф.

## 9. Слабые стороны и техдолг

1. **Нет тестов** — только заглушки ExampleTest; при рефакторинге SEO-логики (Group.php) легко сломать slug-маппинг незаметно.
2. **Контент зашит в код**: тексты категорий и лендингов живут в PHP-файлах (`Group.php`, `SeoLandingPages.php`), а не в БД — правка текстов требует деплоя. При этом часть контента (главная) уже в JSON — зоопарк подходов.
3. **Дублирование данных**: логика главной (`index`) и `homePreview` в MainController почти идентична — кандидат на вынос в приватный метод/сервис.
4. **Двойственность SEO-настроек**: таблица `setings` (page/title/descriptions/keywords) частично перекрывается seo-полями в `Group.php`/`SeoLandingPages.php` — риск рассинхрона.
5. **Опечатки как API**: `Seting`, `watsap`, `siders` — переименование потребует миграций, пока приходится помнить.
6. **Кеш с русскими ключами** (`Cache::remember('seting главная', ...)`) — работает, но непереносимо и хрупко; TTL 60 сек фиксирован, инвалидации при сохранении из админки нет (правки видны с задержкой).
7. **Мусор в `public/`**: `tel.php`, статические html-остатки, статичный `sitemap.xml` рядом с динамическим.
8. **Открытая регистрация** `/register` на боевом сайте.
9. **Git-история неинформативна** — все коммиты «fix commit».
10. **jQuery-стек фронта** (2023 год): тяжелый набор библиотек (GSAP + AOS + Swiper + Fancybox + smooth-scroll…) для по сути статического сайта — влияет на скорость загрузки и Core Web Vitals.
11. **`.phpunit.result.cache` и `.env` в репозитории** (проверить `.gitignore`).
12. **N+1-потенциал**: `Group::with('slides')` кешируется целиком — ок, но `optional($group->slides->first())->getImage()` в шаблонах при сбросе кеша может дать лишние запросы через accessors.

---

## 10. Быстрая навигация («где что искать»)

| Задача | Файлы |
|---|---|
| Тексты/SEO категории галереи | `app/Models/Group.php` → `categoryDefinitions()` |
| Тексты SEO-лендингов | `app/Support/SeoLandingPages.php` |
| Контент главной (hero, faq, cta…) | `storage/app/home-preview-content.json` + `app/Support/HomePreviewContent.php`, редактор: `/admin/home-preview/edit` |
| Шаблон главной | `resources/views/welcome.blade.php` + `ssm/components/new-home.blade.php` |
| Шаблон галереи/категории | `resources/views/gallery.blade.php` + `ssm/components/design.blade.php` |
| Шапка/футер/head | `resources/views/ssm/environment/` |
| Контакты, телефон, карта | таблица `main_sets`, админка `/admin/mainset` |
| Форма заявки | `ssm/components/form.blade.php` → `Message\StoreController` |
| Заявки (inbox) | `/message`, `Message\IndexController` |
| Маршруты | `routes/web.php` |
| Стили | `src/scss/**` (не `public/css`!), сборка `npm run backend` |
| Скрипты | `src/js/**` (не `public/js`!) |
| Картинки работ | таблица `sliders`, storage-симлинк `public/storage` |

## 11. Что проверять при изменении категорий/SEO (чек-лист из аудита)

1. `routes/web.php`
2. `app/Http/Controllers/MainController.php`
3. `app/Models/Group.php`
4. `app/Support/SeoLandingPages.php` (если трогаем лендинги)
5. `resources/views/gallery.blade.php`
6. `resources/views/ssm/components/design.blade.php`
7. `resources/views/ssm/components/category-benefits.blade.php`
8. Sitemap (`MainController@sitemap` подхватывает slug'и автоматически — проверить вывод)
