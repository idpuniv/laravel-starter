import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

const assetsToMove = {
    'public/build/assets/emails.css': 'resources/views/vendor/mail/html/themes',
    'public/build/color-modes.js': 'public/js',
};

const noHashList = Object.keys(assetsToMove).map(p => path.basename(p, path.extname(p)));
const noHashFiles = Object.keys(assetsToMove).map(p => path.basename(p));

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/color-modes.js',
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/guest.css',
                'resources/css/emails.css',
                'resources/js/app.js',
                'resources/js/guest.js',
                'resources/js/admin.js',
                'resources/js/tom-select.js',
                'resources/js/datatable-manager.js',
                'resources/js/custom-select.js',
            ],
            refresh: true,
        }),
        {
            name: 'move-assets',
            closeBundle() {
                for (const [sourcePath, targetDirectory] of Object.entries(assetsToMove)) {
                    const source = path.resolve(__dirname, sourcePath);
                    const targetDir = path.resolve(__dirname, targetDirectory);
                    const target = path.join(targetDir, path.basename(source));

                    if (fs.existsSync(source)) {
                        fs.mkdirSync(targetDir, { recursive: true });
                        fs.copyFileSync(source, target);
                        console.log(`\nDéplacé : ${path.basename(source)} -> ${targetDirectory}`);
                    }
                }
            }
        }
    ],
    // --- CONFIGURATION POUR SUPPRIMER LES COMMENTAIRES ---
    esbuild: {
        legalComments: 'none', // Supprime TOUS les commentaires (y compris les licences comme @license)
    },
    build: {
        minify: 'esbuild', // Utilise esbuild pour minifier (plus rapide)
        cssMinify: true,   // Force aussi la minification du CSS
        rollupOptions: {
            output: {
                assetFileNames: (asset) => {
                    return noHashFiles.includes(asset.name) 
                        ? 'assets/[name][extname]' 
                        : 'assets/[name]-[hash][extname]';
                },
                entryFileNames: (chunk) => {
                    return noHashList.includes(chunk.name) 
                        ? '[name].js' 
                        : 'assets/[name]-[hash].js';
                }
            }
        }
    }
});