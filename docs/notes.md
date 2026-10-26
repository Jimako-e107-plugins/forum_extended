# Notes: forum_extended

Generated: 2026-10-23

What the plugin does, how its shortcode is used and where it is used on this
site. Code comments stay limited to details for callers.

---

## 1. What it is

Site extensions of the forum plugin. The forum plugin itself is not changed,
so it can be updated from upstream / e107 Lite.

| Part | File | Purpose |
|---|---|---|
| Alphabetical page | `alpha.php`, `includes/forum_extended_alpha.php` | topics of one forum sorted by name, letter tabs "All", A-Z, "Other" |
| SEF route | `e_url.php` | `/forum-alpha/<forum_sef>/` -> `alpha.php?sef=<forum_sef>` |
| Shortcode | `e_shortcode.php` | `{FORUM_EXT_ALPHA_LINK}` - button / URL to the alphabetical page |
| Admin: Topics | `admin/admin_threads.php` | flat list of all topics (no forum tree): edit name, forum, open/locked, sticky/announcement, delete |
| Admin: Alphabetical links | `admin/admin_links.php` | every forum with the URL of its alphabetical page and its shortcode |

`installRequired="false"`: nothing to install. After adding or changing an
addon (`e_url.php`, `e_shortcode.php`) refresh the plugin list in the Plugin
Manager, and enable the SEF URL for Forum Extended under Admin -> URLs.

There are no preferences: any forum can be shown alphabetically. The page
checks the visitor's `view` permission on the forum
(`e107forum::checkPerm()`); an unknown and a forbidden forum get the same
answer.

## 2. Why

The old site sorted forums 67, 70 and 73 by topic name through a hardcoded
`if($forumId == 67) ...` in a patched `forum_viewforum.php`
(`forumGetThreadsByAlphabet()` in `forum_class.php`). Upstream dropped it, and
patching the forum plugin again would be lost with the next update. These
forums hold one topic per author of an original (permission requests,
consents, refusals), so they are looked up by name. Letter tabs follow the
unnuke categories page: the bucket is the first byte of the name upper-cased,
so names starting with a national character (Č, Š, Ž ...) are under "Other".

## 3. `{FORUM_EXT_ALPHA_LINK}`

Renders `<a class="btn btn-sm btn-outline-primary forum-ext-alpha-link">`
with the text "Abecedný zoznam" (LAN `LAN_FORUM_EXT_ALPHA_LINK`), linking to
`/forum-alpha/<forum_sef>/` (or `alpha.php?id=N` while the SEF URL is off,
the forum has no SEF name, or `e_url.php` is not registered yet).

| Parameter | Meaning |
|---|---|
| `id=67` | the forum. Required outside the forum pages. |
| `forums=67,70,73` | show the link only for these forums. Use it in templates shared by every forum. |
| `type=url` | return only the URL, e.g. for your own markup. |

Without `id=` the forum comes from the page:

| Page | Source | Cost |
|---|---|---|
| topic list, `forum_viewforum.php` | `$_REQUEST['id']` and `?sef=`, set by the forum plugin also for SEF URLs | no query |
| topic detail, `forum_viewtopic.php` | `?id=` is the TOPIC id; its forum is read | one query per page |
| anywhere else | - | nothing is shown |

Examples:

```
{FORUM_EXT_ALPHA_LINK: forums=67,70,73}     forum templates (list and detail)
{FORUM_EXT_ALPHA_LINK: id=67}               a page, menu or sticky post
{FORUM_EXT_ALPHA_LINK: id=67&type=url}      bare URL
```

The shortcode only builds a link; it never shows topic data, so it needs no
permission check of its own.

## 4. Where it is used (theme aragorn)

Both forum templates are loaded with `e107::getTemplate('forum', ...)`
without merge, so the theme overrides are whole copies of the upstream files.
When the forum plugin is updated, diff them against upstream.

| Template | Place |
|---|---|
| `ethemes/aragorn/templates/forum/forum_viewforum_template.php` | `['header']`, before `{NEWTHREADBUTTONX}`: `{FORUM_EXT_ALPHA_LINK: forums=67,70,73}` |
| `ethemes/aragorn/templates/forum/forum_viewtopic_template.php` | `['start']`, before `{TRACK} {BUTTONSX}`: `{FORUM_EXT_ALPHA_LINK: forums=67,70,73}` |

To add or remove a forum, change the `forums=` list in both templates.

The same overrides also fix the layout for the 14/24 content column of the
3columns layout: title on the rest of the row, buttons on their own width
(`col-12 col-md` / `col-12 col-md-auto`), and the viewforum pagination no
longer sits in `col-lg-2`.

## 5. Not here any more

Removed in the 2.0.0 rewrite: `{FORUM_WELCOME}` and `{FORUM_WELCOME_INFO}`
(unused in any template), the duplicate root `admin_config.php`, the GitHub
sync page and `e_update.php` (both pointed at the unrelated `jmadmin` repo).
Image insertion was not part of this plugin.
