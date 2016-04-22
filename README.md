Bolt LESS Extension
===================

Compile `.less` files to `.css` files on the fly.

Initial Set Up
--------------

The first step is to edit your `bolt-extension-less.teneleven.yml` file and define the files you want
to compile.

An example would be:

```
convert:
  less/styles.less: css/styles.css
```

In this example, the file `less/styles.less` is compiled to the file `css/styles.css`.

**NOTE:** The file paths are relative to the theme directory.

You can also specify `lessc` path:

```
bin: /usr/bin/lessc
```
