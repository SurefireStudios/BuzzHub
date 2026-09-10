<div align="center">

<img src="Review-Manager.png" alt="Review Manager" width="110">

# Review Manager

**A WordPress plugin for manually curating and displaying customer reviews from multiple business locations — with full editorial control and no dependency on external review APIs.**

[![License](https://img.shields.io/github/license/SurefireStudios/BuzzHub?color=blue)](LICENSE)
[![Version](https://img.shields.io/badge/version-1.2.0-blue)](README.txt)
[![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-21759B?logo=wordpress&logoColor=white)](https://wordpress.org)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net)

[Surefire Studios](https://surefirestudios.io/) · [Report an issue](https://github.com/SurefireStudios/BuzzHub/issues)

</div>

---

Reviews are stored in your own database, so you decide what is published, how it reads, and how it looks. Includes Google, Yelp and Facebook platform icons for attribution.

Unlike review plugins that pull from external APIs, nothing here depends on a third-party service: no API keys, no rate limits, no quota, and no outage that takes your testimonials offline.

## Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick start](#quick-start)
- [Shortcodes](#shortcodes)
- [User review submissions](#user-review-submissions)
- [Settings](#settings)
- [Search engine markup](#search-engine-markup)
- [Project structure](#project-structure)
- [FAQ](#faq)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Manual review management** — add, edit and delete reviews from the WordPress admin, with no third-party API keys or rate limits.
- **Multiple locations** — group reviews by business location and filter any display by location.
- **User submissions** — let logged-in visitors submit reviews through a front-end form, with optional photo upload, held for approval before publishing.
- **Moderation workflow** — approve, feature or hide individual reviews, with an email notification to the admin on each new submission.
- **Four display layouts** — grid, list, slider and grid-slider, plus a standalone statistics block.
- **Platform attribution** — Google, Yelp and Facebook icons alongside each review.
- **Bulk text replacement** — search and replace across review text, scoped to a location if needed. Useful when a business rebrands.
- **Structured data** — JSON-LD markup so search engines can read your reviews.
- **Theming** — light, dark or automatic, seven button colours, and two reviewer photo sizes.
- **Star ratings** — 1–5 stars with an aggregate breakdown available via `[review_stats]`.

## Requirements

| | |
| --- | --- |
| **WordPress** | 5.0 or later |
| **PHP** | 7.4 or later |
| **Plugin version** | 1.2.0 |
| **License** | [GPL-2.0-or-later](LICENSE) |

## Installation

### From this repository

```bash
cd wp-content/plugins
git clone https://github.com/SurefireStudios/BuzzHub.git
```

Then activate **Review Manager** from **Plugins** in the WordPress admin.

### From a zip

1. Upload the plugin files to `/wp-content/plugins/review-manager/`.
2. Activate **Review Manager** through the **Plugins** menu in WordPress.

Database tables are created automatically on activation.

## Quick start

1. Go to **Review Manager → Locations** and add your business locations.
2. Go to **Review Manager → Add Review** to add reviews manually, or enable user submissions.
3. Drop a shortcode onto any page or post to display them.
4. Adjust appearance under **Review Manager → Settings**.

## Shortcodes

### `[review_manager]`

The main display shortcode.

```
[review_manager layout="grid" columns="3" max_reviews="10"]
```

| Attribute | Default | Description |
| --- | --- | --- |
| `layout` | `grid` | `grid`, `list`, `slider` or `grid_slider`. |
| `columns` | `3` | Number of columns in grid layouts. |
| `max_reviews` | `10` | Maximum reviews to display. |
| `min_rating` | `1` | Hide reviews rated below this value. |
| `platform` | `all` | Filter by source platform. |
| `location_id` | `0` | Restrict to one location. `0` shows all. |
| `sort_by` | `review_date` | Field to sort on. |
| `order` | `DESC` | `ASC` or `DESC`. |
| `show_photos` | `true` | Show reviewer photos. |
| `show_dates` | `true` | Show review dates. |
| `show_platform` | `true` | Show the platform icon. |
| `truncate` | `50` | Word count before truncating review text. |
| `theme` | — | Optional theme variant. |
| `photo_size` | — | Optional reviewer photo size. |
| `show_review_button` | `false` | Show a submission button to logged-in users. |

### `[review_slider]`

A carousel of reviews.

```
[review_slider max_reviews="20" autoplay="true" speed="5000"]
```

Accepts the filtering and display attributes above, plus:

| Attribute | Default | Description |
| --- | --- | --- |
| `autoplay` | `true` | Advance slides automatically. |
| `speed` | `5000` | Milliseconds between slides. |
| `arrows` | `true` | Show previous / next arrows. |
| `dots` | `true` | Show pagination dots. |

Note that `max_reviews` defaults to `20` here rather than `10`.

### `[review_grid_slider]`

A grid that pages through reviews, combining the grid and slider behaviours.

### `[review_stats]`

Aggregate rating statistics.

```
[review_stats show_average="true" show_breakdown="true"]
```

| Attribute | Default | Description |
| --- | --- | --- |
| `location_id` | `0` | Restrict to one location. `0` covers all. |
| `show_total` | `true` | Show the total review count. |
| `show_average` | `true` | Show the average rating. |
| `show_breakdown` | `false` | Show the per-star distribution. |
| `theme` | — | Optional theme variant. |

## User review submissions

Add `show_review_button="true"` to any display shortcode to show a **Leave Your Own Review** button:

```
[review_manager layout="grid" show_review_button="true"]
```

The flow:

1. A visitor clicks the button.
2. They log in — required, as a spam control.
3. They complete the review form, optionally uploading a photo.
4. The review is stored unapproved and the site admin is emailed.
5. You approve, edit or reject it from the admin.
6. Approved reviews appear on the site.

You keep full editorial control over submitted reviews, including text, rating and reviewer details.

## Settings

Configured under **Review Manager → Settings** and stored in the `mrm_display_settings` option.

| Setting | Values | Description |
| --- | --- | --- |
| `color_theme` | `light`, `dark`, `auto` | Colour scheme for review output. |
| `button_color` | `blue`, `black`, `red`, `green`, `purple`, `orange`, `grey` | Accent colour for buttons. |
| `photo_size` | `small`, `large` | Reviewer photo size. |
| `max_reviews` | number | Default maximum reviews per display. |
| `min_rating` | number | Default minimum rating to display. |
| `show_photos` | on / off | Show reviewer photos by default. |
| `show_dates` | on / off | Show review dates by default. |
| `show_platform` | on / off | Show platform icons by default. |
| `redirect_after_review` | URL | Where to send a user after they submit a review. Defaults to the site home. |

Shortcode attributes override these defaults per display.

## Search engine markup

Review output includes JSON-LD structured data — a `schema.org` `ItemList` of `Review` items, each carrying its author and rating. This is what lets search engines understand the reviews on the page.

## Project structure

```
review-manager.php        Plugin bootstrap, constants, activation hooks
includes/
  class-admin.php         Admin pages and AJAX handlers
  class-database.php      Schema and queries
  class-frontend.php      Front-end rendering and structured data
  class-shortcodes.php    Shortcode registration
  class-user-reviews.php  Front-end submission handling
templates/                Admin screen markup
assets/                   Admin and front-end CSS / JS
```

## FAQ

**Do I need API keys from Google or Yelp?**
No. Reviews are added manually from any source and stored in your own database.

**Can I edit user-submitted reviews?**
Yes — every field, including text, rating and reviewer information.

**Does it support multiple business locations?**
Yes. Create a location per site or branch, then filter any display with `location_id`.

**Are the reviews SEO friendly?**
Yes. See [Search engine markup](#search-engine-markup).

**Can I customise the appearance?**
Light, dark and automatic themes, seven button colours and two photo sizes are built in. Anything further can be done with custom CSS.

Full details, changelog and privacy notes are in [README.txt](README.txt).

## Contributing

Issues and pull requests are welcome. Please keep changes scoped and run `php -l` over any PHP you touch.

## License

Released under the GNU General Public License v2.0 or later. See [LICENSE](LICENSE) for the full text.

Built by [Surefire Studios](https://surefirestudios.io/).
