# ELK Analytics

A simple yet powerful analytics plugin for WordPress

## Filters
* `elk_analytics_enable_posts`: Filter public posts view recording (bool)
* `elk_analytics_enable_contact_form`: Filter Contact Form 7 submission recording (bool)
* `elk_analytics_enable_reservations`: Filter ELK Theme apartment reservation form submission recording (bool)
* `elk_analytics_post_types`: Filter the recorded post types (array of strings, post type slugs)
* `elk_analytics_form_data`: Filter the recorded data on Contact Form 7 submission (string|array|null, array of all fields values)
* `elk_analytics_reservation_details`: Filter the recorded data on ELK Theme apartment reservation form submission (array, array of all fields values)
