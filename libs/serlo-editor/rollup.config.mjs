import { nodeResolve } from "@rollup/plugin-node-resolve"
import commonjs from '@rollup/plugin-commonjs'

export default {
  input: "serlo.mjs",
  output: [
    {
      sourcemap: false,
      file: "../../amd/src/serlo-lazy.js",
      format: "esm",
      inlineDynamicImports: true
    }
  ],
  plugins: [
    nodeResolve(),
    commonjs()
  ]
}
