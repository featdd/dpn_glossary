import { rmSync } from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import * as sass from 'sass';
import { defineConfig } from 'vite';

const root = fileURLToPath(new URL('.', import.meta.url));
const scssEntry = path.resolve(root, 'Resources/Private/Assets/Scss/styles.scss');
const cssOutputDir = path.resolve(root, 'Resources/Public/Css');
const cssMapPath = path.resolve(cssOutputDir, 'styles.css.map');
const virtualRequest = 'virtual:dpn-glossary-styles';
const virtualId = '\0dpn-glossary-styles';

function cssOnly(isDev) {
  return {
    name: 'dpn-glossary-css-only',
    apply: 'build',
    enforce: 'post',
    resolveId(id) {
      if (id === virtualRequest) {
        return virtualId;
      }
    },
    load(id) {
      if (id === virtualId) {
        return `import ${JSON.stringify(scssEntry)};`;
      }
    },
    generateBundle(_options, bundle) {
      for (const fileName of Object.keys(bundle)) {
        if (fileName.endsWith('.js') || fileName.endsWith('.js.map')) {
          delete bundle[fileName];
        }
      }

      if (!isDev) {
        return;
      }

      const cssAsset = Object.values(bundle).find(
        (item) => item.type === 'asset' && item.fileName === 'Css/styles.css',
      );

      if (!cssAsset) {
        return;
      }

      // Vite keeps build CSS sourcemaps empty, so the dev build attaches the Sass map itself.
      const result = sass.compile(scssEntry, {
        style: 'expanded',
        sourceMap: true,
        sourceMapIncludeSources: true,
      });
      const map = result.sourceMap;

      map.file = 'styles.css';
      map.sources = map.sources.map((source) => {
        const filePath = source.startsWith('file:') ? fileURLToPath(source) : source;

        return path.relative(cssOutputDir, filePath).split(path.sep).join('/');
      });

      cssAsset.source = `${result.css.trimEnd()}\n/*# sourceMappingURL=styles.css.map */\n`;
      this.emitFile({
        type: 'asset',
        fileName: 'Css/styles.css.map',
        source: JSON.stringify(map),
      });
    },
    closeBundle() {
      if (!isDev) {
        rmSync(cssMapPath, { force: true });
      }
    },
  };
}

export default defineConfig(({ mode }) => {
  const isDev = mode === 'development';

  return {
    plugins: [cssOnly(isDev)],
    build: {
      outDir: 'Resources/Public',
      emptyOutDir: false,
      ...(isDev ? { minify: false } : {}),
      rolldownOptions: {
        input: virtualRequest,
        output: {
          assetFileNames(assetInfo) {
            const name = assetInfo.names?.[0] ?? assetInfo.name ?? '';

            return name.endsWith('.css') ? 'Css/styles.css' : 'Css/[name][extname]';
          },
        },
      },
    },
  };
});
