import eslintConfigPrettier from 'eslint-config-prettier';
import globals from 'globals';
import js from '@eslint/js';

export default [
  js.configs.recommended,
  eslintConfigPrettier,
  {
    ignores: ['demo/', 'dist/', 'public/'],
    languageOptions: {
      globals: globals.browser,
    },
  },
];
