module.exports = {
    title: "Mirror's Photo API",
    base: "/server/",
    locales: {
        '/en/': {
            lang: 'en-US'
        },
        '/ru/': {
            lang: 'ru-RU'
        }
    },
    themeConfig: {
        locales: {
            '/en/': {
                selectText: 'Languages',
                label: 'English',
                nav: [
                    { text: 'Home', link: '/en/' }
                ],
                sidebar: [
                    '/en/'
                ]
            },
            '/ru/': {
                selectText: 'Языки',
                label: 'Русский',
                nav: [
                    { text: 'Главная', link: '/ru/' }
                ],
                sidebar: [
                    '/ru/',
                    '/ru/auth/',
                    '/ru/photo/',
                    '/ru/good/',
                    '/ru/copy/',
                    '/ru/lamination/',
                    '/ru/service/',
                    '/ru/sale/',
                    '/ru/socket/'
                ]
            }
        }
    }
}