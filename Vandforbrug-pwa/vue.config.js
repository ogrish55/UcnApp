module.exports = {
    pwa: {
      workboxOptions: {
        skipWaiting: true,
        clientsClaim: true,
        runtimeCaching: [{
            urlPattern: new RegExp('^http://backend.test/api/login'),
            handler: 'NetworkFirst',
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
                handler: 'NetworkFirst',
                method: 'POST',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/monthlyMeasurements/hot'),
                handler: 'NetworkFirst',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/consumption/cold/json'),
                handler: 'NetworkFirst',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/consumption/hot/json'),
                handler: 'NetworkFirst',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/currentYearUsage/total'),
                handler: 'NetworkFirst',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/currentYearUsage/total/monthNumber'),
                handler: 'NetworkFirst',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/monthlyUsageInDkk'),
                handler: 'NetworkFirst',
                options: {
                    cacheName: 'api-cache'
                }
            },
            {
                urlPattern: new RegExp('^http://backend.test/api/data/region'),
                handler: 'NetworkFirst',
                options: {
                    cacheName: 'api-cache'
                }
            }
        ]
      }
    }
  }