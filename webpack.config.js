const Encore = require('@symfony/webpack-encore');

Encore
.addEntry('app', './assets/js/app.js')
.addEntry('login', './assets/js/login.js') // Ajout du fichier login.js
    // Répertoire de sortie
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    
    // Ajouter un fichier d'entrée
    .addEntry('app', './assets/app.js')

    // Activer Vue.js loader
    .enableVueLoader(() => {}, { version: 3 })

    // Activer le chargement de fichiers CSS et JS
    .enableSassLoader()
    .enablePostCssLoader()
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
;

module.exports = Encore.getWebpackConfig();
