## Images

Images are uploaded to Cloudflare R2 and served from the subdomain images.randomcod.com.

Cloudflare R2 dashboard: https://dash.cloudflare.com/c1f5a4996e441001a254485dd314d1fd/r2/overview

When setting up, make sure these .env fields have values:

```
CLOUDFLARE_R2_ACCESS_KEY_ID
CLOUDFLARE_R2_SECRET_ACCESS_KEY
CLOUDFLARE_R2_BUCKET=codmaps
CLOUDFLARE_R2_URL=https://images.randomcod.com
CLOUDFLARE_R2_ENDPOINT
CLOUDFLARE_R2_USE_PATH_STYLE_ENDPOINT
```

When a user selects an image to upload, Livewire automatically uploads it to R2 and gets a temporary URL for preview purposes. When the form is submitted, the temporary file is moved to a permanent location in R2 (the root of the bucket).

Images are stored as `{slug}-{game}.{ext}` where `{slug}` is a URL-friendly version of the map name, `{game}` is the game short name (e.g. `mwiii`). If a file with that name already exists, a number is appended to the filename (e.g. `-{i}`) until a unique name is found.
