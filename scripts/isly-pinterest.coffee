class IslyPinterest
  constructor: (options) ->
    console.log 'hello world'
    @permalinkClass = options.permalinkClass || '.isly-pinterest-permalink'
    @contentContainers = []
    this.build()
  build: () ->
    this.permalinks = $(this.permalinkClass)
    this.findContainer permalink for permalink in this.permalinks
    console.log this.contentContainers
  findContainer: (permalink) ->
    permalink = $(permalink)
    container =
      permalink: permalink
      container: permalink.parent()
      images: []
    container.images = container.container.children('img')
    this.contentContainers.push container
$(document).ready ->
  options =
    permalinkClass: '.isly-pinterest-permalink'
  new IslyPinterest options