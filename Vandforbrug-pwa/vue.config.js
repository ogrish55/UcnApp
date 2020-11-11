module.exports = {
    pwa: {
      workboxOptions: {
        skipWaiting: true,
        clientsClaim: true,
        runtimeCaching: [{
            urlPattern: new RegExp('^http://backend.test/api/login'),
            handler: 'CacheFirst',
            method: 'POST',
                options: {
                cacheName: 'api-cache'
            }
            },
            // {
            //     urlPattern: new RegExp('^http://backend.test/api/user'),
            //     handler: 'CacheFirst',
            //     options: {
            //         cacheName: 'api-cache'
            //     }
            // },
            {
                urlPattern: new RegExp('^http://backend.test/api/logout'),
                handler: 'CacheFirst',
                method: 'POST',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/monthlyMeasurements/hot'),
                handler: 'CacheFirst',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/consumption/cold/json'),
                handler: 'CacheFirst',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/consumption/hot/json'),
                handler: 'CacheFirst',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/currentYearUsage/total'),
                handler: 'CacheFirst',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/currentYearUsage/total/monthNumber'),
                handler: 'CacheFirst',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/monthlyUsageInDkk'),
                handler: 'CacheFirst',
                options: {
                    cacheName: 'api-cache'
                }
            }
        ]
      }
    }
  }