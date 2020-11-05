/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js");

importScripts(
  "/precache-manifest.0172317294bde8ecd008992236385afb.js"
);

workbox.core.setCacheNameDetails({prefix: "testafpwa"});

workbox.core.skipWaiting();

workbox.core.clientsClaim();

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [].concat(self.__precacheManifest || []);
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});

workbox.routing.registerRoute(/^http:\/\/backend.test\/api\/login/, new workbox.strategies.CacheFirst({ "cacheName":"api-cache", plugins: [] }), 'POST');
workbox.routing.registerRoute(/^http:\/\/backend.test\/api\/logout/, new workbox.strategies.CacheFirst({ "cacheName":"api-cache", plugins: [] }), 'POST');
workbox.routing.registerRoute(/^http:\/\/backend.test\/api\/data\/monthlyMeasurements\/hot/, new workbox.strategies.CacheFirst({ "cacheName":"api-cache", plugins: [] }), 'GET');
workbox.routing.registerRoute(/^http:\/\/backend.test\/api\/data\/consumption\/cold\/json/, new workbox.strategies.CacheFirst({ "cacheName":"api-cache", plugins: [] }), 'GET');
workbox.routing.registerRoute(/^http:\/\/backend.test\/api\/data\/consumption\/hot\/json/, new workbox.strategies.CacheFirst({ "cacheName":"api-cache", plugins: [] }), 'GET');
workbox.routing.registerRoute(/^http:\/\/backend.test\/api\/data\/currentYearUsage\/total/, new workbox.strategies.CacheFirst({ "cacheName":"api-cache", plugins: [] }), 'GET');
workbox.routing.registerRoute(/^http:\/\/backend.test\/api\/data\/currentYearUsage\/total\/monthNumber/, new workbox.strategies.CacheFirst({ "cacheName":"api-cache", plugins: [] }), 'GET');
workbox.routing.registerRoute(/^http:\/\/backend.test\/api\/data\/monthlyUsageInDkk/, new workbox.strategies.CacheFirst({ "cacheName":"api-cache", plugins: [] }), 'GET');
