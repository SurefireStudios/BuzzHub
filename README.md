# Review Manager

A WordPress plugin for manually curating and displaying customer reviews from multiple business locations, with full editorial control and no dependency on external review APIs.

Reviews are stored in your own database, so you decide what is published, how it reads, and how it looks. Includes Google, Yelp and Facebook platform icons for attribution.

- **Version:** 1.2.0
- **Requires WordPress:** 5.0 or later
- **Requires PHP:** 7.4 or later
- **License:** [GPL-2.0-or-later](LICENSE)

## Features

- **Manual review management** — add, edit and delete reviews from the WordPress admin, with no third-party API keys or rate limits.
- **Multiple locations** — group reviews by business location and filter any display by location.
- **User submissions** — let logged-in visitors submit reviews through a front-end form, held for approval before publishing.
- **Moderation workflow** — approve, feature or hide individual reviews.
- **Display layouts** — grid, slider, and grid-slider, plus a standalone statistics block.
- **Platform attribution** — Google, Yelp and Facebook icons alongside each review.

## Installation

1. Download or clone this repository into `wp-content/plugins/`:

   ```bash
   cd wp-content/plugins
   git clone https://github.com/SurefireStudios/BuzzHub.git
   ```

2. Activate **Review Manager** from **Plugins** in the WordPress admin.
3. Open **Review Manager** in the admin sidebar to add your locations and first reviews.

Database tables are created automatically on activation.

## Shortcodes

### `[review_manager]`

The main display shortcode.

```
[review_manager layout="grid" columns="3" max_reviews="10"]
```

| Attribute | Default | Description |
| --- | --- | --- |
| `layout` | `grid` | Display layout to render. |
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

## Project structure

```
review-manager.php        Plugin bootstrap, constants, activation hooks
includes/
  class-admin.php         Admin pages and AJAX handlers
  class-database.php      Schema and queries
  class-frontend.php      Front-end rendering
  class-shortcodes.php    Shortcode registration
  class-user-reviews.php  Front-end submission handling
templates/                Admin screen markup
assets/                   Admin and front-end CSS / JS
```

## Contributing

Issues and pull requests are welcome. Please keep changes scoped and run `php -l` over any PHP you touch.

## License

Released under the GNU General Public License v2.0 or later. See [LICENSE](LICENSE) for the full text.
