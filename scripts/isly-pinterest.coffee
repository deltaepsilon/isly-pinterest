$ = window.jQuery
window.ISLY =
  'IslyPinterest': null
window.ISLY.IslyPinterest = class IslyPinterest
  constructor: (options) ->
    @permalinkClass = options.permalinkClass || '.isly-pinterest-permalink'
    @minHeight = options.minHeight || 100
    @verticalOffset = options.verticalOffset || 0
    @horizontalOffset = options.horizontalOffset || 0
    @contentContainers = []
    @pin = $(document.createElement('a')).attr
      'id': 'isly-pinterest-pin'
      'title': 'Pin It!'
      'target': '_blank'
    this.build()
  build: () ->
    $(document.body).append @pin
    @window = $(window)
    @permalinks = $(@permalinkClass)
    @findContainer permalink for permalink in this.permalinks
    @setListeners()
    @pin.hover ->
      $(this).show()
    , ->
      $(this).hide()
  findContainer: (permalink) ->
    permalink = $(permalink)
    container =
      permalink: permalink
      entry: permalink.parent()
      description: permalink.attr('data-description')
      images: []
    container.images = container.entry.find('img')
    this.contentContainers.push container
  setListeners: ->
    for entry in this.contentContainers
      this.setListener image, entry for image in entry.images
  setListener: (image, entry) ->
    that = this
    image = $(image)
    permalink = entry.permalink.attr('href')
    if image.height() > @minHeight
      image.bind 'mouseenter', ->
        that.setPin(image, entry)
      image.bind 'mouseleave', ->
        that.removePin()
  setPin: (image, entry) ->
    that = this
    pinItLink = @getPinItLink image, entry
    position = image.offset()
    @pin.attr('href', pinItLink).css
      top: position.top + @verticalOffset,
      left: position.left + @horizontalOffset,
      display: 'block'
    @window.bind 'resize', @removePin
  removePin: =>
    @pin.hide()
    @window.unbind 'resize', @removePin
  getPinItLink: (image, entry) ->
    return 'http://pinterest.com/pin/create/button/?url=' +  encodeURIComponent(entry.permalink.attr('href')) + '&media=' + encodeURIComponent($(image).attr('src')) + '&description=' + encodeURIComponent(entry.description)