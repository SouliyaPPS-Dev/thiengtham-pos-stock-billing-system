const CACHE_NAME = "miss-clean-v4";

const STATIC_ASSETS = [
  "./public/css/app.css",
  "./public/logo.jpg",
  "./public/icon-192.png",
  "./public/icon-512.png",
  "./public/manifest.json",
  "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css",
  "https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js",
  "https://cdn.jsdelivr.net/npm/sweetalert2@11",
];

self.addEventListener("install", (e) => {
  self.skipWaiting();
  e.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(STATIC_ASSETS).catch(() => {});
    }),
  );
});

self.addEventListener("activate", (e) => {
  e.waitUntil(
    Promise.all([
      caches
        .keys()
        .then((keys) =>
          Promise.all(
            keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)),
          ),
        ),
      self.clients.claim(),
    ]),
  );
});

self.addEventListener("fetch", (e) => {
  if (e.request.method !== "GET") return;
  if (e.request.mode === "navigate") return;

  const url = new URL(e.request.url);
  if (url.origin !== self.location.origin) return;

  // Only intercept cacheable static assets, not HTML pages or API requests
  const isCacheable =
    /\.(css|js|png|jpg|jpeg|gif|svg|ico|woff2?|ttf|eot)$/i.test(url.pathname);
  if (!isCacheable) return;

  e.respondWith(
    caches
      .match(e.request)
      .then((cached) => {
        return (
          cached ||
          fetch(e.request).then((res) => {
            if (res.ok) {
              const clone = res.clone();
              caches
                .open(CACHE_NAME)
                .then((cache) => cache.put(e.request, clone));
            }
            return res;
          })
        );
      })
      .catch(() => new Response("", { status: 504 })),
  );
});
