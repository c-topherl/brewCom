Modernizr.load([

    // Test need for polyfill
    {
        test: window.matchMedia,
        nope: "/assets/js/plugins/respond.js"
    },

    // Then load code
    "/assets/js/plugins/enquire.min.js",
    "/assets/js/global.js"
]);