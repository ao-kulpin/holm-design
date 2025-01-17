/* ========================================================================
 * Bootstrap: modal.js v3.1.1
 * http://getbootstrap.com/javascript/#modals
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
    'use strict';

    // MODAL CLASS DEFINITION
    // ======================

    var Modal = function (element, options) {
        this.options   = options
        this.$element  = $(element)
        this.$backdrop =
        this.isShown   = null

        if (this.options.remote) {
            this.$element
            .find('.modal-content')
            .load(
                this.options.remote, $.proxy(
                    function () {
                        this.$element.trigger('loaded.orion.modal')
                    }, this
                )
            )
        }
    }

    Modal.DEFAULTS = {
        backdrop: true,
        keyboard: true,
        show: true
    }

    Modal.prototype.toggle = function (_relatedTarget) {
        return this[!this.isShown ? 'show' : 'hide'](_relatedTarget)
    }

    Modal.prototype.show = function (_relatedTarget) {
        var that = this
        var e    = $.Event('show.orion.modal', { relatedTarget: _relatedTarget })

        this.$element.trigger(e)

        if (this.isShown || e.isDefaultPrevented()) { return

            this.isShown = true

            this.escape()

            this.$element.on('click.dismiss.orion.modal', '[data-dismiss="modal"]', $.proxy(this.hide, this))

            this.backdrop(
                function () {
                    var transition = $.support.oriontransition && that.$element.hasClass('fade')

                    if (!that.$element.parent().length) {
                        that.$element.appendTo(document.body) // don't move modals dom position
                    }

                    that.$element
                    .show()
                    .scrollTop(0)

                    if (transition) {
                        that.$element[0].offsetWidth // force reflow
                    }

                    that.$element
                    .addClass('in')
                    .attr('aria-hidden', false)

                    that.enforceFocus()

                    var e = $.Event('shown.orion.modal', { relatedTarget: _relatedTarget })

                    transition ?
                    that.$element.find('.modal-dialog') // wait for modal to slide in
                    .one(
                        $.support.oriontransition.end, function () {
                            that.$element.focus().trigger(e)
                        }
                    )
                    .emulateTransitionEnd(300) :
                    that.$element.focus().trigger(e)
                }
            )
        }
    }

    Modal.prototype.hide = function (e) {
        if (e) { e.preventDefault()

            e = $.Event('hide.orion.modal')

            this.$element.trigger(e)

            if (!this.isShown || e.isDefaultPrevented()) { return

                this.isShown = false

                this.escape()

                $(document).off('focusin.orion.modal')

                this.$element
                .removeClass('in')
                .attr('aria-hidden', true)
                .off('click.dismiss.orion.modal')

                $.support.oriontransition && this.$element.hasClass('fade') ?
                this.$element
                .one($.support.oriontransition.end, $.proxy(this.hideModal, this))
                .emulateTransitionEnd(300) :
                this.hideModal()
            }
        }
    }

    Modal.prototype.enforceFocus = function () {
        $(document)
        .off('focusin.orion.modal') // guard against infinite focus loop
        .on(
            'focusin.orion.modal', $.proxy(
                function (e) {
                    if (this.$element[0] !== e.target && !this.$element.has(e.target).length) {
                        this.$element.focus()
                    }
                }, this
            )
        )
    }

    Modal.prototype.escape = function () {
        if (this.isShown && this.options.keyboard) {
            this.$element.on(
                'keyup.dismiss.orion.modal', $.proxy(
                    function (e) {
                        e.which == 27 && this.hide()
                    }, this
                )
            )
        } else if (!this.isShown) {
            this.$element.off('keyup.dismiss.orion.modal')
        }
    }

    Modal.prototype.hideModal = function () {
        var that = this
        this.$element.hide()
        this.backdrop(
            function () {
                that.removeBackdrop()
                that.$element.trigger('hidden.orion.modal')
            }
        )
    }

    Modal.prototype.removeBackdrop = function () {
        this.$backdrop && this.$backdrop.remove()
        this.$backdrop = null
    }

    Modal.prototype.backdrop = function (callback) {
        var animate = this.$element.hasClass('fade') ? 'fade' : ''

        if (this.isShown && this.options.backdrop) {
            var doAnimate = $.support.oriontransition && animate

            this.$backdrop = $('<div class="omodal-backdrop ' + animate + '" />')
            .appendTo(document.body)

            this.$element.on(
                'click.dismiss.orion.modal', $.proxy(
                    function (e) {
                        if (e.target !== e.currentTarget) { return
                            this.options.backdrop == 'static'
                            ? this.$element[0].focus.call(this.$element[0])
                            : this.hide.call(this)
                        }
                    }, this
                )
            )

            if (doAnimate) { this.$backdrop[0].offsetWidth // force reflow

                this.$backdrop.addClass('in')

                if (!callback) { return

                    doAnimate ?
                      this.$backdrop
                    .one($.support.oriontransition.end, callback)
                    .emulateTransitionEnd(150) :
                      callback()

                }
            } } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.removeClass('in')

            $.support.oriontransition && this.$element.hasClass('fade') ?
              this.$backdrop
              .one($.support.oriontransition.end, callback)
              .emulateTransitionEnd(150) :
              callback()

            } else if (callback) {
                callback()
            }
    }


    // MODAL PLUGIN DEFINITION
    // =======================

    var old = $.fn.omodal

    $.fn.omodal = function (option, _relatedTarget) {
        return this.each(
            function () {
                var $this   = $(this)
                var data    = $this.data('orion.modal')
                var options = $.extend({}, Modal.DEFAULTS, $this.data(), typeof option == 'object' && option)

                if (!data) $this.data('orion.modal', (data = new Modal(this, options)))
                if (typeof option == 'string') data[option](_relatedTarget)
                else if (options.show) { data.show(_relatedTarget)
                }
            }
        )
    }

    $.fn.omodal.Constructor = Modal


    // MODAL NO CONFLICT
    // =================

    $.fn.omodal.noConflict = function () {
        $.fn.omodal = old
        return this
    }


    // MODAL DATA-API
    // ==============

    $(document).on(
        'click.orion.modal.data-api', '[data-toggle="o-modal"]', function (e) {
            var $this   = $(this)
            var href    = $this.attr('href')
            var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) //strip for ie7
            var option  = $target.data('orion.modal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())

            if ($this.is('a')) { e.preventDefault()

                $target
                .omodal(option, this)
                .one(
                    'hide', function () {
                        $this.is(':visible') && $this.focus()
                    }
                )
            }
        }
    )

    $(document)
    .on(
        'show.orion.modal', '.modal', function () {
            $(document.body).addClass('modal-open') }
    )
    .on(
        'hidden.orion.modal', '.modal', function () {
            $(document.body).removeClass('modal-open') }
    )

}(jQuery);

/* ========================================================================
 * Bootstrap: transition.js v3.1.1
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
    'use strict';

    // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
    // ============================================================

    function transitionEnd()
    {
        var el = document.createElement('bootstrap')

        var transEndEventNames = {
            'WebkitTransition' : 'webkitTransitionEnd',
            'MozTransition'    : 'transitionend',
            'OTransition'      : 'oTransitionEnd otransitionend',
            'transition'       : 'transitionend'
        }

        for (var name in transEndEventNames) {
            if (el.style[name] !== undefined) {
                return { end: transEndEventNames[name] }
            }
        }

        return false // explicit for ie8 (  ._.)
    }

    // http://blog.alexmaccaw.com/css-transitions
    $.fn.emulateTransitionEnd = function (duration) {
        var called = false, $el = this
        $(this).one(
            $.support.oriontransition.end, function () {
                called = true }
        )
        var callback = function () {
            if (!called) { $($el).trigger($.support.oriontransition.end) }
        }
        setTimeout(callback, duration)
        return this
    }

    $(
        function () {
            $.support.oriontransition = transitionEnd()
        }
    )

}(jQuery);
