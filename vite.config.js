import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify';
import path from 'path'; 

export default defineConfig({
	server: {
		hmr: {
			host: 'localhost',
		},
	},
	resolve: {
		alias: {
			'@': path.resolve(__dirname, 'resources/js'), // エイリアス設定を追加
			'@css': path.resolve(__dirname, 'resources/css'),
		},
	},
	plugins: [
		laravel({
			input: [
				'resources/css/app.css',
				'resources/js/app.js',
			],
			refresh: true,
			}),
			vue(),
			vuetify({ autoImport: true }), // Vuetifyプラグインを追加
		],
	});