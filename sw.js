/* 彩虹外链网盘静态资源缓存：PHP 页面始终走网络，静态资源离线/重复访问优先缓存。 */
const APP_VERSION = '1532';
const CACHE_NAME = 'rainbow-pan-static-' + APP_VERSION;
const CDN_HOST = 's4.zstatic.net';
const QR_HOST = 'api.qrserver.com';

const PRECACHE_URLS = [
  './assets/css/style.css?v=' + APP_VERSION,
  'https://s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
  'https://s4.zstatic.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css',
  'https://s4.zstatic.net/ajax/libs/bootstrap-material-design/0.5.10/css/bootstrap-material-design.min.css',
  'https://s4.zstatic.net/ajax/libs/bootstrap-material-design/0.5.10/css/ripples.min.css',
  'https://s4.zstatic.net/ajax/libs/jquery/1.12.4/jquery.min.js',
  'https://s4.zstatic.net/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js',
  'https://s4.zstatic.net/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js',
  'https://s4.zstatic.net/ajax/libs/bootstrap-material-design/0.5.10/js/ripples.min.js'
];

function isCacheableResponse(response) {
  return response && (response.ok || response.type === 'opaque');
}

function cacheFirst(request) {
  return caches.match(request).then(function (cached) {
    if (cached) return cached;
    return fetch(request).then(function (response) {
      if (isCacheableResponse(response)) {
        var copy = response.clone();
        caches.open(CACHE_NAME).then(function (cache) {
          cache.put(request, copy);
        });
      }
      return response;
    });
  });
}

function staleWhileRevalidate(request) {
  return caches.open(CACHE_NAME).then(function (cache) {
    return cache.match(request).then(function (cached) {
      var network = fetch(request).then(function (response) {
        if (isCacheableResponse(response)) {
          cache.put(request, response.clone());
        }
        return response;
      }).catch(function () {
        return cached || Response.error();
      });
      return cached || network;
    });
  });
}

self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open(CACHE_NAME).then(function (cache) {
      return Promise.all(PRECACHE_URLS.map(function (url) {
        return cache.add(url).catch(function () { return null; });
      }));
    }).then(function () {
      return self.skipWaiting();
    })
  );
});

self.addEventListener('activate', function (event) {
  event.waitUntil(
    caches.keys().then(function (keys) {
      return Promise.all(keys.map(function (key) {
        if (key !== CACHE_NAME) return caches.delete(key);
        return null;
      }));
    }).then(function () {
      return self.clients.claim();
    })
  );
});

self.addEventListener('fetch', function (event) {
  var request = event.request;
  if (request.method !== 'GET') return;

  var url = new URL(request.url);

  if (url.origin === self.location.origin) {
    if (url.pathname.indexOf('.php') !== -1 || url.pathname === '/install/') {
      return;
    }
    if (url.pathname.indexOf('/assets/') !== -1 || /\.(?:css|js|woff2?|ttf|eot|png|jpe?g|gif|ico|svg|webp)(?:$|\?)/i.test(url.pathname)) {
      event.respondWith(staleWhileRevalidate(request));
    }
    return;
  }

  if (url.host === CDN_HOST || url.host === QR_HOST) {
    event.respondWith(cacheFirst(request));
  }
});
