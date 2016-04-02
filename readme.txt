=== Date & Time Picker for Advanced Custom Fields ===
Contributors: toszcze
Tags: advanced custom fields,acf,custom field,datepicker,timepicker
Requires at least: 3.5
Tested up to: 4.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Date & Time Picker field for Advanced Custom Fields 4 and 5.

== Description ==

This is an add-on for [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) and [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/), which allows to create a Date and Time Picker field.

The plugin uses [Timepicker Addon](http://trentrichardson.com/examples/timepicker/) for jQuery UI Datepicker by [Trent Richardson](http://trentrichardson.com/examples/timepicker/), licensed under the MIT license.

= Compatibility =

This ACF field type is compatible with ACF 5 (Pro) and ACF 4.

== Installation ==

1. Copy the `acf-date-time-picker` folder into your `wp-content/plugins` folder
2. Activate the Date & Time Picker for Advanced Custom Fields plugin via the plugins admin page
3. Create a new field via ACF and select the Date & Time Picker type
4. Please refer to the description for more info regarding the field type settings

== Frequently Asked Questions  ==

**How do I format the time?**

You can format the time by creating a mask using the following characters:
`
H	Hour with no leading 0 (24 hour)
HH	Hour with leading 0 (24 hour)
h	Hour with no leading 0 (12 hour)
hh	Hour with leading 0 (12 hour)
m	Minute with no leading 0
mm	Minute with leading 0
s	Second with no leading 0
ss	Second with leading 0
t	a or p for AM/PM
T	A or P for AM/PM
tt	am or pm for AM/PM
TT	AM or PM for AM/PM
`
The default time format is `HH:mm`.

You can read more about formatting the time [here](http://trentrichardson.com/examples/timepicker/#tp-formatting).

**How do I format the date?**

You can format the date by creating a mask using the following characters:
`
d	day of month (no leading zero)
dd	day of month (two digit)
o	day of the year (no leading zeros)
oo	day of the year (three digit)
D	day name short
DD	day name long
m	month of year (no leading zero)
mm	month of year (two digit)
M	month name short
MM	month name long
y	year (two digit)
yy	year (four digit)
`
The default date format is `yy-mm-dd`.

You can read more about formatting the date [here](http://api.jqueryui.com/datepicker/#utility-formatDate).

**How do I format the date and time to display it on the frontend?**

The plugin saves the date and time in the following format: `YYYY-MM-DD hh:mm:ss` (for example `2016-04-01 16:57:00`). This is the format used by WordPress in `wp_posts` table, so it's easy to use this field value in custom meta queries. However the Advanced Custom Fields API returns the date and time in the format set in the field settings, so you can just use `the_field()` or `get_field()` function in your theme.

To display the date and time in a different format, you can use [strtotime()](http://php.net/manual/en/function.strtotime.php) and [date()](http://php.net/manual/en/function.date.php) functions, for example:
`
echo date('d/m/Y g:i a', strtotime(get_field('date_and_time_field')));
`

== Changelog ==

= 1.0.0 =
* Initial release.
