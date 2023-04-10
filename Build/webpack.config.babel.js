import path from 'path';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import FixStyleOnlyEntriesPlugin from "webpack-fix-style-only-entries";

module.exports = {
  mode: process.env.NODE_ENV,
  entry: [
    path.resolve(__dirname, './../Resources/Private/Assets/Scss/Styles.scss')
  ],
  output: {
    path: path.resolve(__dirname, './../Resources/Public'),
  },
  plugins: [
    new FixStyleOnlyEntriesPlugin(),
    new MiniCssExtractPlugin({
      filename: './Css/styles.css'
    })
  ],
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: "css-loader",
            options: {
              sourceMap: true,
              importLoaders: 2
            }
          },
          {
            loader: "sass-loader",
            options: {
              sourceMap: true
            }
          },
        ],
      }
    ]
  }
};

if ('development' === process.env.NODE_ENV) {
  module.exports['devtool'] = 'source-map';
  module.exports['watch'] = true;
}
