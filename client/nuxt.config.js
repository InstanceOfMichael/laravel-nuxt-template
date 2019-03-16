import dotenv from 'dotenv'

dotenv.config()

// const polyfills = [
//   'Promise',
//   'Object.assign',
//   'Object.values',
//   'Array.prototype.find',
//   'Array.prototype.findIndex',
//   'Array.prototype.includes',
//   'String.prototype.includes',
//   'String.prototype.startsWith',
//   'String.prototype.endsWith'
// ]

module.exports = {
  // mode: 'spa',

  srcDir: __dirname,

  env: {
    apiUrl: process.env.APP_URL || 'http://api.lndebate.test',
    appName: process.env.APP_NAME || 'lndebate',
    appLocale: process.env.APP_LOCALE || 'en',
    githubAuth: !!process.env.GITHUB_CLIENT_ID
  },

  head: {
    title: process.env.APP_NAME,
    titleTemplate: '%s - ' + process.env.APP_NAME,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'Nuxt.js project' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      // { rel: 'stylesheet', href: '/css/bootstrap-darkly.min.css' },
      // { rel: 'stylesheet', href: '/css/font-awesome-4.7.0/css/font-awesome.min.css' },
      // { rel: 'stylesheet', href: '/css/more_dark.css' },
    ],
    script: [
      // { src: `https://cdn.polyfill.io/v2/polyfill.min.js?features=${polyfills.join(',')}` }
    ]
  },

  loading: { color: '#007bff' },

  router: {
    middleware: ['redirects', 'locale', 'check-auth']
  },

  css: [
    { src: '~assets/sass/app.scss', lang: 'scss' }
  ],

  plugins: [
    '~components/global',
    '~plugins/i18n',
    '~plugins/vform',
    '~plugins/axios',
    '~plugins/fontawesome',
    '~plugins/nuxt-client-init',
    { src: '~plugins/bootstrap', ssr: false },
    { src: '~plugins/breaks_ssr', ssr: false },
    '~plugins/directives',
    '~plugins/slug',
  ],

  modules: [
    ['@nuxtjs/google-analytics', {
      id: 'UA-130086131-3'
    }],
    '@nuxtjs/pwa',
    '@nuxtjs/sentry',
    // '@nuxtjs/proxy',
    // '@nuxtjs/separate-env',
  ],

  sentry: {
    dsn: 'https://ca5f7225c31f44ba844281149475c5af@sentry.io/1407940',
    config: {} // Additional config
  },

  build: {
    extractCSS: true
  },

  // proxy: {
  //   '/api': {
  //     pathRewrite: { '^/api' : '' },
  //     target: process.env.APP_URL || 'http://api.lndebate.test',
  //     ws: false,
  //   },
  // },

}
